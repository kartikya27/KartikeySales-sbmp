<?php

namespace Arky\Sales\Repository;

use Arky\Sales\Interfaces\OrderPayment;
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