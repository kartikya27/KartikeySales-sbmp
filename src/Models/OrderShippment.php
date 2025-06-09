<?php

namespace Arky\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderShippment extends Model
{
    use SoftDeletes;
    protected $table = ORDER_SHIPMENT_TABLE;
    protected $guarded = ['id'];

    public function shippment_items()
    {
        return $this->hasMany(OrderShippmentItem::class, 'shipment_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function order_address()
    {
        return $this->belongsTo(OrderAddress::class, 'order_address_id');
    }
}
