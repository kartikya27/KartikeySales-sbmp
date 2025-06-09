<?php

namespace Arky\Sales\Repository;

use Arky\Sales\Models\OrderShippment;
use Arky\Sales\Models\OrderShippmentItem;
use Illuminate\Container\Container;
use Stegback\Core\Eloquent\Repository;

class OrderShippmentRepository extends Repository
{
    /**
    * Specify model class name.
    */
    public function model(): string
    {
        return OrderShippment::class;
    }
 
    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        Container $container
    ) {
        parent::__construct($container);
    }

    public function getShipmentItem($shipmentId)
    {
        return OrderShippment::where('id',$shipmentId)->with('shippment_items');
    }
}