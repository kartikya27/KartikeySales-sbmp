<?php

namespace Kartikey\Sales\Observers;

use Kartikey\Sales\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        // Handle the order creation event
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        if ($order->isDirty('status')) {
            // Log status change
            Log::info("Order #{$order->id} status changed to {$order->status}");
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        // Handle the order deletion event
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        // Handle the order restoration event
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        // Handle the order force deletion event
    }
}

