<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cme extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];

    public function questions()
    {
        return $this->hasMany(QuestionBankMap::class, 'map_id', 'id')->where('map_type', 'cme');
    }

    public function cmeMap()
    {
        return $this->hasOne(CmeMap::class, 'cme_id', 'id');
    }

    public function cmeMemberHistory()
    {
        return $this->hasOne(CmeHistory::class, 'cme_id', 'id')->where('member_id',request()->member_id);
    }

}
