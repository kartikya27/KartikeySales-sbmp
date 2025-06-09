<?php

namespace Kartikey\Sales\Http\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $shippingInformation = [];

        if ($this->haveStockableItems()) {
            $shippingInformation = [
                'shipping_method'               => $this->selected_shipping_rate->method ?? 'FREE',
                // 'shipping_title'                => $this->selected_shipping_rate->carrier_title ?? ' '.' - '.$this->selected_shipping_rate->method_title ?? 'FREE SHIPPING',
                'shipping_description'          => $this->selected_shipping_rate->method_description ?? 'NA',
                'shipping_amount'               => $this->selected_shipping_rate->price ?? 0,
                'base_shipping_amount'          => $this->selected_shipping_rate->base_price ?? 0,
                'shipping_amount_incl_tax'      => $this->selected_shipping_rate->price_incl_tax ?? 0,
                'base_shipping_amount_incl_tax' => $this->selected_shipping_rate->base_price_incl_tax ?? 0,
                'shipping_discount_amount'      => $this->selected_shipping_rate->discount_amount ?? 0,
                'base_shipping_discount_amount' => $this->selected_shipping_rate->base_discount_amount ?? 0,
                'shipping_address'              => (new OrderAddressResource($this->customer->shipping_address->WhereNull('deleted_at')->first()))->jsonSerialize(),
            ];
        }

        return [
            'cart_id'                  => $this->id,
            'is_guest'                 => $this->is_guest,
            'customer_id'              => $this->customer_id,
            'customer_type'            => $this->customer ? get_class($this->customer) : null,
            'customer_email'           => $this->customer_email,
            'customer_name'            => $this->customer->name,
            'channel_id'               => core()->getCurrentChannel()?->id,
            'channel_name'             => core()->getCurrentChannel()?->code ?? 'default', // todo Channel Implement
            'total_item_count'         => $this->items_count,
            'total_qty_ordered'        => $this->items_qty,
            'base_currency_code'       => $this->base_currency_code,
            'channel_currency_code'    => $this->channel_currency_code,
            'order_currency_code'      => $this->cart_currency_code,
            'grand_total'              => $this->grand_total,
            'base_grand_total'         => $this->base_grand_total,
            'sub_total'                => $this->sub_total,
            'sub_total_incl_tax'       => $this->sub_total_incl_tax,
            'base_sub_total'           => $this->base_sub_total,
            'base_sub_total_incl_tax'  => $this->base_sub_total_incl_tax,
            'tax_amount'               => $this->tax_total,
            'base_tax_amount'          => $this->base_tax_total,
            'shipping_tax_amount'      => $this->selected_shipping_rate?->tax_amount ?? 0,
            'base_shipping_tax_amount' => $this->selected_shipping_rate?->base_tax_amount ?? 0,
            'coupon_code'              => $this->coupon_code,
            'applied_cart_rule_ids'    => $this->applied_cart_rule_ids,
            'discount_amount'          => $this->discount_amount,
            'base_discount_amount'     => $this->base_discount_amount,
            'billing_address'          => (new OrderAddressResource($this->customer->billing_address->WhereNull('deleted_at')->first()))->jsonSerialize(),
                                            $this->mergeWhen($this->haveStockableItems(), $shippingInformation),
            'payment'                  => (new OrderPaymentResource($this->payment))->jsonSerialize(),
            'items'                    => OrderItemResource::collection($this->items()->withStatus(0)->get())->jsonSerialize(),
        ];
    }
}
