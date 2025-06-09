<?php

namespace Kartikey\Sales\Interfaces;

interface Sequencer
{
    /**
     * Create and return the next sequence number for e.g. an order.
     */
    public function generate(): string;
}
