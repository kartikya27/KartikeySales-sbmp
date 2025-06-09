<?php

namespace Kartikey\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderInvoice extends Model
{
    use SoftDeletes;
    protected $table = ORDER_INVOICE_TABLE;
    protected $guarded = ['id'];
}
