<?php

namespace App\Http\Resources\QuestionBank;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionBankResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $answerHistories = $this->totalAnswers()->get();
        $totalCount = $answerHistories->sum(function ($sum)  {
            return $sum->option_count;
        });
        
        $options = $this->options;
        if($options){
            $options = !empty($this->options)  ? json_decode($options, true) : null;
            foreach ($options as $key => $value) {
                $options[$key]['is_correct'] = 0;
                $options[$key]['option_count'] = 0;
                $options[$key]['option_percent'] = 0;
                if($this->correct_option == $value['value']){
                    $options[$key]['is_correct'] = 1;
                }
                foreach ($answerHistories as $answerHistory) {
                    if($answerHistory->member_answer == $value['value']){
                        $options[$key]['option_count']  = $answerHistory->option_count;
                        $options[$key]['option_percent']= number_format(($answerHistory->option_count/$totalCount)*100,2,'.','');
                    }
                }
            }
        }

        return [
            'id'            => $this->id,
            'question'      => $this->question,
            'question_type' => $this->question_type,
            'options'       => $options,
            'correct_answer'=> $this->correct_option,
            'is_mandatory'  => $this->is_mandatory,
        ];
    }
}
