<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionBankMap extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function QuestionBank(): BelongsTo
    {
        return $this->BelongsTo(QuestionBank::class, 'question_bank_id', 'id');
    }

}
