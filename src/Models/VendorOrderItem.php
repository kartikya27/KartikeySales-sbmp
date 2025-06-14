<?php

namespace Kartikey\Sales\Models;

use Kartikey\Sales\Interfaces\OrderVendor;
use Kartikey\Sales\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class VendorOrderItem extends Model
{
    use SoftDeletes;
    protected $table = VENDOR_ORDER_ITEM_TABLE;
    protected $guarded = ['id'];


    public function vendorOrder()
    {
        return $this->belongsTo(OrderVendor::class, 'vendor_order_id');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }


}
