<?php

namespace App\Http\Resources\QuestionBank;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionBankMapResource extends JsonResource
{
    private static $showRandom;
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $questionBank = new QuestionBankResource($this->questionBank);
        if(self::$showRandom){
            $questionBank->options = collect(json_decode($questionBank->options, true))->shuffle();
        }
        return [
            'id'                    => $this->id,
            'question_bank_id'      => $this->question_bank_id,
            'map_type'              => $this->map_type,
            'map_type_id'           => $this->map_id,
            'show_answer'           => $this->show_answer,
            'show_answer_details'   => $this->show_answer_details,
            'questionBank'          => $questionBank,
        ];
    }

    //I made custom function that returns collection type
    public static function customCollection($resource, $showRandom): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        //you can add as many params as you want.
        self::$showRandom = $showRandom;
        return parent::collection($resource);
    }
}
