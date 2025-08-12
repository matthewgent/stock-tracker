<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Administrator extends Model
{
    protected $fillable = [
        'member_id',
    ];

    public $timestamps = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getMemberIdAttribute(): int
    {
        return intval($this->attributes['member_id']);
    }

    // Relationships

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
