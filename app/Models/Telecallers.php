<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Telecallers extends Authenticatable
{
    use SoftDeletes;

    protected $table = 'telecallers';

    public $timestamps = true;

    protected $fillable = [
        'username',
        'password',
        'email',
        'ip_address'
    ];


    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

}
