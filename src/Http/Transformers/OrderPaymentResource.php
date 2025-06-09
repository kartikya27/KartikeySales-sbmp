<?php

namespace Kartikey\Sales\Http\Transformers;

use Kartikey\Sales\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'method'       => $this->method,
            'method_title' => $this->method_title,
            'payment_status' => Order::STATUS_PENDING_PAYMENT,
        ];
    }
}
