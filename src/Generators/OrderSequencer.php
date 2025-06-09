<?php

namespace Arky\Sales\Generators;

use Arky\Sales\Generators\Sequencer;
use Arky\Sales\Models\Order;

class OrderSequencer extends Sequencer
{

    /**
     * Create order sequencer instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setAllConfigs();
    }


    /**
     * Set all configs.
     *
     * @param  string  $configKey
     * @return void
     */
    public function setAllConfigs()
    {
        $this->prefix = 'DE';//core()->getConfigData('sales.order_settings.order_number.order_number_prefix');

        $this->length = 4 ; //core()->getConfigData('sales.order_settings.order_number.order_number_length');

        $this->suffix = null;//self::generateAlphanumericCode(); //core()->getConfigData('sales.order_settings.order_number.order_number_suffix');

        $this->date = now()->format('Ymd'); //core()->getConfigData('sales.order_settings.order_number.order_number_suffix');

        $this->generatorClass = 'IN';//core()->getConfigData('sales.order_settings.order_number.order_number_generator');

        $this->lastId = $this->getLastId();
    }

    /**
     * Get last id.
     *
     * @return int
     */
    public function getLastId()
    {
        $lastOrder = Order::query()->orderBy('id', 'desc')->limit(1)->first();

        return $lastOrder ? $lastOrder->id : 0;
    }

    function generateAlphanumericCode($length = 2) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $code;
    }

}
