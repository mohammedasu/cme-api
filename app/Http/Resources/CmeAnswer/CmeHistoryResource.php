<?php

namespace App\Http\Resources\CmeAnswer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Cme\CmeResource;

class CmeHistoryResource extends JsonResource
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
            'cme_id'            => $this->cme_id,
            'member_id'         => $this->member_id,
            'type'              => $this->type,
            'type_id'           => $this->type_id,
            'result_in_percent' => $this->result_in_percent,
            'earned_points'     => $this->earned_points,
            'earned_coins'      => $this->earned_coins,
            'earned_coins_type' => $this->earned_coins_type,
            'is_passed'         => $this->is_passed,
            'total_questions'   => $this->total_questions,
            'correct_answers'   => $this->correct_answers,
            'attempt_number'    => $this->attempt_number,
            'history_details'   => CmeHistoryDetailResource::collection($this->historyDetails),
        ];
    }
}
