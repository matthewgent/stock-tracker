<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

abstract class Model extends EloquentModel
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->makeHidden(['created_at', 'updated_at']);
        $this->guarded[] = 'id';
    }

    public function getIdAttribute(): int
    {
        return intval($this->attributes['id']);
    }
}
