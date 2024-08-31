<?php

namespace App\Http\Resources\Cme;

use Illuminate\Http\Request;
use App\Http\Resources\CmeMap\CmeMapResource;
use App\Http\Resources\CmeAnswer\CmeHistoryResource;
use App\Http\Resources\QuestionBank\QuestionBankMapResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CmeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $questionBankMap = QuestionBankMapResource::customCollection($this->questions,$this->show_rand_questions);
        if($this->show_rand_questions){
            $questionBankMap = $questionBankMap->shuffle();
        }
        return [
            'id'                        => $this->id,
            'type'                      => $this->type,
            'name'                      => $this->name,
            'description'               => $this->description,
            'points'                    => $this->points,
            'passing_criteria'          => $this->passing_criteria,
            'show_result'               => $this->show_result,
            'allow_back'                => $this->allow_back,
            'survey_url'                => $this->survey_url,
            'survey_background_image'   => !empty($this->survey_background_image) ? (config('constants.cme_path').$this->survey_background_image) : null,
            'pass_image'                => !empty($this->pass_image) ? (config('constants.cme_path').$this->pass_image) : null,
            'pass_text'                 => $this->pass_text,
            'pass_button_text'          => $this->pass_button_text,
            'fail_image'                => !empty($this->fail_image) ? (config('constants.cme_path').$this->fail_image) : null,
            'fail_text'                 => $this->fail_text,
            'fail_button_text'          => $this->fail_button_text,
            'show_landing_page'         => $this->show_landing_page,
            'download_certificate'      => $this->download_certificate,
            'allow_retest'              => $this->allow_retest,
            'timer_status'              => $this->timer_status,
            'time_in_seconds'           => $this->time_in_seconds,
            'negative_marks_status'     => $this->negative_marks_status,
            'negative_mark'             => $this->negative_mark,
            'positive_mark'             => $this->positive_mark,
            'landing_page_image'        => !empty($this->landing_page_image) ? (config('constants.cme_path').$this->landing_page_image) : null,
            'survey_background_mobile_image'=> !empty($this->survey_background_mobile_image) ? (config('constants.cme_path').$this->survey_background_mobile_image) : null,
            'landing_page_button_text'  => $this->landing_page_button_text,
            'status'                    => $this->status,
            'show_rand_questions'       => $this->show_rand_questions,
            'coins'                     => $this->coins,
            'coins_type'                => $this->coins_type,
            'heading_text'              => $this->heading_text,
            'heading_text'              => $this->heading_text,
            'certificate_id'            => $this->certificate_template_id,
            'forum_url'                 => $this->forum_url ?? null,
            'survey_complete'           => !empty($this->survey_complete) ? $this->survey_complete : null,
            'question_mapped'           => $questionBankMap,
            'cme_mapped'                => new CmeMapResource($this->cmeMap),
            'cme_member_history'        => isset($this->cmeMemberHistory) ? new CmeHistoryResource($this->cmeMemberHistory) : null,
        ];
    }
}
