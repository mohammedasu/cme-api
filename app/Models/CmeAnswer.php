<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class CmeAnswer extends Model
{
    // use SoftDeletes;
    protected $guarded = [];
    protected $table = 'cme_member_answers';

    public function questionBank()
    {
        return $this->hasOne(QuestionBank::class, 'id', 'question_id');
    }

}
