<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class SovereignState extends Model
{
    protected $fillable = [
        'code_2',
        'code_3',
        'number,',
        'name',
    ];

    public $timestamps = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getCode2Attribute(): string
    {
        return $this->attributes['code_2'];
    }

    public function getCode3Attribute(): string
    {
        return $this->attributes['code_3'];
    }

    public function getNumberAttribute(): int
    {
        return intval($this->attributes['number']);
    }

    public function getNameAttribute(): string
    {
        return $this->attributes['name'];
    }

    // Relationships

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    public function wealthPercentileGroups(): HasMany
    {
        return $this->hasMany(WealthPercentileGroup::class);
    }

    public function securityExchanges(): HasMany
    {
        return $this->hasMany(SecurityExchange::class);
    }
}
