<?php

namespace Arky\Sales\Repository;

use Stegback\Core\Eloquent\Repository;
use Arky\Sales\Models\Order as OrderModel;
use Illuminate\Container\Container;
use Arky\Sales\Generators\OrderSequencer;
use Arky\Sales\Interfaces\OrderItem;
use Arky\Sales\Models\OrderVendor;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Stegback\Checkout\Models\CartItem;
use Stegback\Core\Models\Coupon;
use Stegback\Core\Models\CouponUsage;
use Stripe\Customer;

class OrderRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected OrderItemRepository $orderItemRepository,
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
    * Specify model class name.
    */
   public function model(): string
   {
       return OrderModel::class;
   }

    /**
     * Generate increment id.
     *
     * @return int
     */
    public function generateIncrementId()
    {
        return app(OrderSequencer::class)->resolveGeneratorClass();
    }

    /**
     * This method will find order if id is given else pass the order as it is.
     *
     * @param  \Stegback\Sales\Models\Order|int  $orderOrId
     * @return \Stegback\Sales\Interface\Order
     */
    private function resolveOrderInstance($orderOrId)
    {
        return $orderOrId instanceof OrderModel
            ? $orderOrId
            : $this->findOrFail($orderOrId);
    }

     /**
     * Create order.
     *
     * @return \Webkul\Sales\Contracts\Order
     */
    public function create(array $data)
    {

        return $this->createOrderIfNotThenRetry($data);
    }

    /**
     * This method will try attempt to a create order.
     *
     * @return \Webkul\Sales\Contracts\Order
     */
    public function createOrderIfNotThenRetry(array $data)
    {
        DB::beginTransaction();

        try {
            // Event::dispatch('checkout.order.save.before', [$data]); //todo manage Event
            //todo update address in order address table

            $orderNumber = $this->generateIncrementId();
            $data['status'] = OrderModel::STATUS_PENDING;

            $order = $this->model->create(array_merge($data, ['order_number' => $orderNumber,'user_id' => $data['customer_id']]));

            $order->payment()->create($data['payment']);

            if (isset($data['billing_address'], $data['shipping_address'])) {
                $order->addresses()->create([
                    'shipping_address' => $data['shipping_address'],
                    'billing_address' => $data['billing_address'],
                    'order_id' => $order->id,  // Ensure order_id is passed correctly
                ]);
            }

            foreach(collect($data['items']) as $item)
            {
                // dd($item['coupon_code']);
                if (!empty($item['coupon_code'])) {
                    $coupon = Coupon::where('code', $item['coupon_code'])->value('id'); // Get only ID, no need to fetch full object

                    if ($coupon && auth()->check()) { // Ensure coupon exists and user is authenticated
                        $userCoupon = CouponUsage::updateOrCreate(
                            [
                                'user_id' => auth()->user()->id,
                                'coupon_id' => $coupon,
                            ],
                            [
                                'order_id' => $order->id,
                            ]
                        );
                        // dd($userCoupon);
                    }
                    // dd(1);
                }
                // dd(0);
            }

            foreach (collect($data['items'])->groupBy('vendor') as $vendorId => $items) {
                // Create or update sub-order for the vendor
                $vendorSubOrder = $this->createVendorSubOrder($order, $vendorId, $items);

                foreach ($items as $item) {
                    // Associate order items with the vendor-specific sub-order
                    $orderItem = $this->orderItemRepository->create(
                        array_merge($item, ['order_id' => $order->id, 'vendor_id' => $vendorId])
                    );

                    $this->associateVendorOrderItem($vendorSubOrder, $orderItem);

                    // Manage inventory for the order item
                    $this->orderItemRepository->manageInventory($orderItem);

                    // Handle child items, if any
                    if (!empty($item['children'])) {
                        foreach ($item['children'] as $child) {
                            $childOrderItem = $this->orderItemRepository->create(
                                array_merge($child, [
                                    'order_id' => $order->id,
                                    'parent_id' => $orderItem->id,
                                    'vendor_id' => $vendorId,
                                ])
                            );

                            $this->associateVendorOrderItem($vendorSubOrder, $childOrderItem);
                        }
                    }
                }
            }


            // todo Enable try catch
            // Event::dispatch('checkout.order.save.after', $order);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(
                'OrderRepository:createOrderIfNotThenRetry: '.$e->getMessage(),
                ['data' => $data]
            );
            $this->createOrderIfNotThenRetry($data);
        } finally {
            DB::commit();
        }
        return $order;
    }

    protected function associateVendorOrderItem($vendorSubOrder, $orderItem)
    {
        return $vendorSubOrder->items()->create([
            'order_item_id' => $orderItem->id,
        ]);
    }

    protected function createVendorSubOrder($order, $vendorId, $items)
    {
        $total = collect($items)->sum('total');
        $qtyShipped = collect($items)->sum('qty_ordered');

        return OrderVendor::updateOrCreate(
            ['order_id' => $order->id, 'seller_id' => $vendorId],
            [
                'order_number' => $order->order_number,
                'vendor_order_number' => $order->order_number . '/' . $vendorId,
                'status' => 'pending',
                'qty_shipped' => $qtyShipped,
                'total' => $total,
            ]
        );
    }

    public function cancelOrderItems($id)
    {
        $orderItem = $this->orderItemRepository->findOneWhere(['id' => $id]);

        if ($orderItem) {

            $this->orderItemRepository->delete($orderItem['id']);

            return true;
        }


        return false;
    }
    public function getOrderWithVendors($orderNumber, $vendorId = null)
    {

        if($vendorId)
        {
            return $this->model->with([
                'vendors' => function ($query) use ($vendorId) {
                    $query->where('seller_id', $vendorId);
                },
                'vendors.items.orderItem' => function ($query) {
                    $query->with('product.images');
                },
            ])->where('order_number', $orderNumber)->first();
        }else
        {
            return $this->model->with([
                'vendors',
                'vendors.items.orderItem' => function ($query) {
                    $query->with('product.images');
                },
            ])->where('order_number', $orderNumber)->first();
        }
    }

    public function getOrderProducts($orderNumber)
    {
        return $this->model->where('order_number',$orderNumber)->with('items')->first();
    }

    public function updatePaymentDetails($order, $paymentDetails)
    {
        // Fetch the related payment record
        $payment = $order->payment;
        if (!$payment) {
            return false; // or handle the missing payment case
        }
        $payment->update([
            'payment_status' => $paymentDetails['PaymentStatus'],
            'payment_id' => $paymentDetails['PaymentId'] ?? null,
            'payment_method' => $paymentDetails['PaymentMethod'] ?? null,
            'method_title' => $paymentDetails['PaymentMethodTitle'] ?? null,
            'additional' => $paymentDetails['AdditionalInformation'] ?? null,
        ]);
    
        return true; // Indicate success
    }

    public function updateShippingDetails($order, $shippingDetails)
    {
        dd($shippingDetails);

        // $order->shipping_details = $shippingDetails;
        // $order->save();
    }

    public function updateAddress($order, $addressDetails)
    {
        $orderAddress = $order->addresses;
    
        if (!$orderAddress) {
            return false; // Handle case where address doesn't exist
        }
        // Determine which address type to update
        $addressTypeKey = strtolower($addressDetails['AddressType']) === 'billingaddress' ? 'billing_address' : 'shipping_address';
        // Decode existing address JSON
        $existingAddress = $orderAddress->{$addressTypeKey};
        // Store old value before update (convert to JSON)
        $orderAddress->old_value = [$addressTypeKey => $existingAddress];
    
        // Merge new values with existing ones (only update changed values)
        $mappedNewAddress = [
            'address_type' => $existingAddress['address_type'] ?? 'new_address',
            'name' => $addressDetails['Address']['Name'] ?? $existingAddress['name'] ?? null,
            'address' => $addressDetails['Address']['FullAddress'] ?? $existingAddress['address'] ?? null,
            'city' => $addressDetails['Address']['City'] ?? $existingAddress['city'] ?? null,
            'state' => $existingAddress['state'] ?? null, // No value in new data, keep existing
            'country' => $addressDetails['Address']['Country'] ?? $existingAddress['country'] ?? null,
            'postcode' => $addressDetails['Address']['Postcode'] ?? $existingAddress['postcode'] ?? null,
            'phone' => $addressDetails['Address']['Phone'] ?? $existingAddress['phone'] ?? null,
            'email' => $addressDetails['Address']['Email'] ?? $existingAddress['email'] ?? null,
        ];

        // Update only the required address type column
        $orderAddress->update([
            $addressTypeKey => $mappedNewAddress,
            'old_value' => $orderAddress->old_value, // Store old value as JSON
        ]);
    
        return true;
    }
    

}
