<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class QuestionBank  extends Model
{
    protected $table = 'question_bank';

    public function totalAnswers()
    {
        return $this->hasMany(CmeHistoryDetail::class, 'question_bank_id', 'id')->select('*', DB::raw('count(member_answer) as option_count'))->groupBy('member_answer');
    }

}
