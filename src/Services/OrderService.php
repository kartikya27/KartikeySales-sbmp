<?php

namespace Kartikey\Sales\Services;

use Kartikey\Sales\Models\OrderVendor;
use Illuminate\Support\Facades\DB;

class OrderService
{
    function getVendorOrderList(int $vendor)
    {
        return OrderVendor::where('seller_id',$vendor)->get();
    }

    function getVendorOrderSupportQuery(int $vendor)
    {
        $orders = OrderVendor::join(SUPPORT_TABLE, function ($join) {
            $join->on('order_vendors.vendor_order_number', 'LIKE', DB::raw("CONCAT(" . SUPPORT_TABLE . ".order_id, '/%')"));
        })
        ->where('order_vendors.seller_id', $vendor)
        ->select('order_vendors.*', SUPPORT_TABLE . '.ticket_number', SUPPORT_TABLE . '.support_type', SUPPORT_TABLE . '.subcategory',SUPPORT_TABLE . '.name',SUPPORT_TABLE . '.email',SUPPORT_TABLE . '.phone') // Fetch necessary fields
        ->get();

        return $orders;

    }

}

