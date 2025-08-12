<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemCategory extends Model
{
    protected $fillable = [
        'name',
    ];

    public $timestamps = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->makeHidden('id');
    }

    public function getNameAttribute(): string
    {
        return $this->attributes['name'];
    }

    // Relationships

    public function itemTypes(): HasMany
    {
        return $this->hasMany(ItemType::class);
    }
}
