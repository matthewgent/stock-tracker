<?php

namespace App\Models;

use App\Collections\ItemCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'item_type_id',
        'name',
    ];

    public $timestamps = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->makeHidden('member_id');
    }

    public function getMemberIdAttribute(): int
    {
        return intval($this->attributes['member_id']);
    }

    public function getItemTypeIdAttribute(): int
    {
        return intval($this->attributes['item_type_id']);
    }

    public function getNameAttribute(): ?string
    {
        return $this->attributes['name'];
    }

    // Relationship

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function itemValues(): HasMany
    {
        return $this->hasMany(ItemValue::class);
    }

    public function itemType(): BelongsTo
    {
        return $this->belongsTo(ItemType::class);
    }

    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class);
    }

    // Business logic

    public function getType(): ItemType
    {
        return $this->itemType()->first();
    }

    public function isNotOwned(): bool
    {
        return $this->getMember()->getIdAttribute() !== member()->getIdAttribute();
    }

    public function getMember(): Member
    {
        return $this->member()->first();
    }

    public function getStock(): ?Stock
    {
        return $this->stock()->first();
    }

    public function getClass(): int
    {
        // 0 - Basic
        // 1 - Tracked stock
        $class = 0;
        $stock = $this->getStock();
        if ($stock !== null) {
            $class = 1;
        }
        return $class;
    }

    public function getName(): string
    {
        $name = $this->getNameAttribute();

        $stock = $this->getStock();
        if ($stock !== null) {
            $name = $stock->getTicker()->getNameAttribute();
        }

        return $name;
    }

    public function newCollection(array $models = []): ItemCollection
    {
        return new ItemCollection($models);
    }
}
