<?php

namespace Kartikey\Sales\Repository;

use Kartikey\Sales\Interfaces\OrderPayment;
use Illuminate\Container\Container;
use Stegback\Core\Eloquent\Repository;

class OrderPaymentRepository extends Repository
{
    /**
    * Specify model class name.
    */
    public function model(): string
    {
        return OrderPayment::class;
    }
 
    /**
     * Create a new repository instance.
     *
     * @return void
     */

}