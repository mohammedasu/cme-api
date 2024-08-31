<?php

namespace App\Http\Resources\CmeAnswer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Cme\CmeResource;

class CmeHistoryDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'cme_history_id'    => $this->cme_history_id,
            'question_bank_id'  => $this->question_bank_id,
            'correct_answer'    => $this->correct_answer,
            'member_answer'     => $this->member_answer,
        ];
    }
}
