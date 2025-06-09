<?php

namespace Kartikey\Sales\Http\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderAddressResource extends JsonResource
{
    public function toArray($request)
    {
        // dd($this->resource);
        return [
            'address_type' => $this->resource->address_type,
            'name'         => $this->resource->name ?? $this->customer->name, //todo Not Working resource  here for name
            'address'      => $this->resource->address,
            'city'         => $this->resource->city,
            'state'        => $this->resource->state,
            'country'      => $this->resource->country,
            'postcode'     => $this->resource->postcode,
            'phone'        => $this->resource->phone,
            'email'        => auth()->user()->email,
        ];
    }
}

