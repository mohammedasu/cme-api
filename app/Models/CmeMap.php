<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CmeMap extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];

    public function cmes(): BelongsTo
    {
        return $this->BelongsTo(Cme::class, 'cme_id', 'id');
    }

    public function forum()
    {
        return $this->hasOne(PartnerDivision::class, 'id', 'map_type_id');
    }
}
