<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    /**
     * Get rel prefectures.
     *
     * @return HasMany
     */
    public function prefectures(): HasMany
    {
        return $this->hasMany('App\Models\Prefecture');
    }
}
