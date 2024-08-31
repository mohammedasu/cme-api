<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmeHistory extends Model
{
    protected $guarded = [];

    public function historyDetails()
    {
        return $this->hasMany(CmeHistoryDetail::class, 'cme_history_id', 'id');
    }

}
