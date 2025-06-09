<?php

namespace Kartikey\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderShippmentItem extends Model
{
    use SoftDeletes;
    protected $table = ORDER_SHIPMENT_ITEM_TABLE;
    protected $guarded = ['id'];


    public function shippment()
    {
        return $this->belongsTo(OrderShippment::class, 'shipment_id');
    }
   
    public function items()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }
}
