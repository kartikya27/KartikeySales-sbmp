<?php

namespace Arky\Sales\Models;

use Arky\Sales\Interfaces\OrderItem as InterfacesOrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stegback\Product\Type\AbstractType;

class OrderItem extends Model implements InterfacesOrderItem
{
    use SoftDeletes;
    protected $table = ORDER_ITEM_TABLE;
    protected $guarded = [
        'id',
        'child',
        'children',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'additional' => 'array',
    ];

    public function isStockable(): bool
    {
        return $this->getTypeInstance()->isStockable();
    }

    /**
     * Define the type instance
     *
     * @var mixed
     */
    protected $typeInstance;

    /**
     * Retrieve type instance
     */
    public function getTypeInstance(): AbstractType
    {
        if ($this->typeInstance) {
            return $this->typeInstance;
        }

        $this->typeInstance = app(config('product_types.'.$this->type.'.class'));
        if ($this->product) {
            $this->typeInstance->setProduct($this->product);
        }

        return $this->typeInstance;
    }

    /**
     * Get the parent item record associated with the order item.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get the children items.
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }


    /**
     * Get the child item record associated with the order item.
     */
    public function child(): HasOne
    {
        return $this->hasOne(OrderItem::class, 'parent_id');
    }

     /**
     * Get the product record associated with the order item.
     */
    public function product(): MorphTo
    {
        return $this->morphTo();
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function tracking_details()
    {
        return $this->hasOne(OrderShippmentItem::class, 'order_item_id');
    }


}
