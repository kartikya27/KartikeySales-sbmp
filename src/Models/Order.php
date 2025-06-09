<?php

namespace Kartikey\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kartikey\Sales\Models\OrderAddres;
use Kartikey\Sales\Models\OrderAddresUse;
use Kartikey\Sales\Models\OrderVendor as ModelsOrderVendor;
use Kartikey\Support\Models\SupportQuery;
use Kartikey\Support\Models\SupportTicket;
use Stegback\Checkout\Models\CartAddress;
use Kartikey\Core\Models\Channel;
use Stegback\Sales\Models\OrderVendor;
use Stegback\User\Models\User;

class Order extends Model
{
    use SoftDeletes;
    protected $table = ORDER_TABLE;
    protected $guarded = ['id'];

    /**
     * Order Status
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_PENDING_PAYMENT = 'pending_payment';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_CLOSED = 'closed';
    public const STATUS_FRAUD = 'fraud';
    public const STATUS_COD_CONFIRED = 'Cod Confirmed';
    public const STATUS_COD_PENDING_VERIFICATION = 'cod_pending_verification';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_REFUNDED = 'refunded';
    public const STATUS_RETURNED = 'returned';
    public const STATUS_RETURN_IN_PROCESS = 'return_in_process';
    public const STATUS_RETURN_REQUESTED = 'return_requested';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_OUT_FOR_DELIVERY = 'out_for_delivery';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_PAYMENT_RECEIVED = 'payment_received';
    public const STATUS_ON_HOLD = 'on_hold';
    public const STATUS_PAYMENT_FAILED = 'payment_failed';

    protected $statusLabel = [
        self::STATUS_PENDING                    => 'Pending',
        self::STATUS_PENDING_PAYMENT            => 'Pending Payment',
        self::STATUS_PROCESSING                 => 'Processing',
        self::STATUS_COMPLETED                  => 'Completed',
        self::STATUS_CANCELED                   => 'Canceled',
        self::STATUS_CLOSED                     => 'Closed',
        self::STATUS_FRAUD                      => 'Fraud',
        self::STATUS_COD_CONFIRED               => 'Cod Confirmed',
        self::STATUS_COD_PENDING_VERIFICATION   => 'Cod Pending Verification',
        self::STATUS_REJECTED                   => 'Rejected',
        self::STATUS_FAILED                     => 'Failed',
        self::STATUS_CANCELLED                  => 'Cancelled',
        self::STATUS_REFUNDED                   => 'Refunded',
        self::STATUS_RETURNED                   => 'Returned',
        self::STATUS_RETURN_IN_PROCESS          => 'Return In Process',
        self::STATUS_RETURN_REQUESTED           => 'Return Requested',
        self::STATUS_DELIVERED                  => 'Delivered',
        self::STATUS_OUT_FOR_DELIVERY           => 'Out For Delivery',
        self::STATUS_SHIPPED                    => 'Shipped',
        self::STATUS_CONFIRMED                  => 'Confirmed',
        self::STATUS_PAYMENT_RECEIVED           => 'Payment Paid',
        self::STATUS_ON_HOLD                    => 'On Hold',
        self::STATUS_PAYMENT_FAILED             => 'Payment Failed',
    ];

    public function getStatusLabel()
    {
        return $this->statusLabel[$this->status] ?? null;
    }


    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function supportQueries()
    {
        return $this->hasMany(SupportTicket::class, 'order_id');
    }

     /**
     * Get the payment for the order.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(OrderPayment::class);
    }

    /**
     * Get the payment for the order.
     */
    public function vendors(): HasMany
    {
        return $this->hasMany(ModelsOrderVendor::class);
    }

     /**
     * Get the addresses for the order.
     */
    public function addresses()
    {
        return $this->hasOne(OrderAddress::class);
    }

    public function orderAddressUsed(): HasMany
    {
        return $this->hasMany(OrderAddress::class);
    }

    public function channels()
    {
        return $this->belongsTo(Channel::class,'channel');
    }

    /**
     * Get the billing address for the cart.
    */

    public function getBillingAddress()
    {
        // return CartAddress::where('customer_id', $this->user_id)->where('use_for', CartAddress::ADDRESS_TYPE_BILLING)->first();
        return CartAddress::where('use_for', CartAddress::ADDRESS_TYPE_BILLING)->first();
    }

    /**
     * Get the shipping address for the cart.
     */
    public function getShippingAddress()
    {
        $address = CartAddress::where('use_for', CartAddress::ADDRESS_TYPE_SHIPPING)->first();
        if (!$address) {
            $address = CartAddress::where('use_for_shipping', CartAddress::ADDRESS_TYPE_SHIPPING)->first();
        }
        return $address;

    }


    /**
     * Get the billing address for the order.
     */
    public function billing_address()
    {
        return $this->hasOne(CartAddress::class)
            ->where('use_for', CartAddress::ADDRESS_TYPE_BILLING);
    }


    public function customer()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function getOrderBillingAddress(){
        return $this->hasOne(OrderAddress::class,'order_id');
    }

    public function shippments()
    {
        return $this->hasMany(OrderShippment::class);
    }

}
