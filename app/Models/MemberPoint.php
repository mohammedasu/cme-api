<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberPoint extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

}
