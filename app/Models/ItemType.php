<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemType extends Model
{
    protected $fillable = [
        'name',
    ];

    public $timestamps = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getItemCategoryIdAttribute(): int
    {
        return intval($this->attributes['item_category_id']);
    }

    public function getNameAttribute(): string
    {
        return $this->attributes['name'];
    }

    // Relationships

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function itemCategory(): BelongsTo
    {
        return $this->belongsTo(ItemCategory::class);
    }

    // Business logic
    public function getCategory(): ItemCategory
    {
        return $this->itemCategory()->first();
    }
}
