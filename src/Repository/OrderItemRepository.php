<?php

namespace Kartikey\Sales\Repository;

use Kartikey\Core\Eloquent\Repository;
use Kartikey\Sales\Models\Order;
use Kartikey\Sales\Models\OrderItem;

class OrderItemRepository extends Repository
{
    /**
    * Specify model class name.
    */
    public function model(): string
    {
        return OrderItem::class;
    }

    /**
     * Manage inventory.
     *
     * @param  \Kartikey\Sales\Contracts\OrderItem  $orderItem
     * @return void
     */
    public function manageInventory($orderItem)
    {
        $orderItems = [];

        if ($orderItem->getTypeInstance()->isComposite()) {
            foreach ($orderItem->children as $child) {
                if (! $child->product->manage_stock) {
                    continue;
                }

                $orderItems[] = $child;
            }
        } else {

            if ($orderItem->product->manage_stock) {
                $orderItems[] = $orderItem;
            }
        }
        foreach ($orderItems as $item) {

            if (! $item->product) {
                continue;
            }

            if ($item->product->inventories->count()) {
                $orderedInventory = $item->product->ordered_inventories()
                    ->where('channel_id', $orderItem->order->channel_id)
                    ->first();

                if (isset($item->qty_ordered)) {
                    $qty = $item->qty_ordered;
                } else {
                    $qty = $item?->parent?->qty_ordered ?? 1;
                }

                if ($orderedInventory) {
                    $orderedInventory->update([
                        'qty' => $orderedInventory->qty + $qty,
                    ]);
                } else {
                    $item->product->ordered_inventories()->create([
                        'qty'        => $qty,
                        'product_id' => $item->product_id,
                        'channel_id' => $orderItem->order->channel->id,
                    ]);
                }
            }
        }
    }
}
