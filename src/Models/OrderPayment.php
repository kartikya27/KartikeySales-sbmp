<?php

namespace Kartikey\Sales\Models;

use Kartikey\Sales\Interfaces\OrderPayment as OrderPaymentInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPayment extends Model implements OrderPaymentInterface
{
    use SoftDeletes;
    protected $table = ORDER_PAYMENT_TABLE;
    protected $guarded = ['id'];
}
