<?php

namespace App\Http\Requests;

use App\Constants\Constants;
use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CmeAnswerStoreRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'cme_id'                => 'required|integer|exists:cmes,id',
            'member_id'             => 'required|integer|exists:members,id',
            'type'                  => 'string|nullable',
            'type_id'               => 'integer|nullable',
            'answers'               => 'required|array',
            'answers.*.question_id' => 'required|integer|exists:question_bank,id',
            'answers.*.answer'      => 'required',
        ];
    }


    /**
     * Failed Validation response
     *
     * @param Validator $validator [description]
     *
     * @return object
     */
    public function failedValidation(Validator $validator): object
    {
        throw new HttpResponseException(
            ApiResponse::validationFailure(
                Constants::RESPONSE_ERROR_MESSAGE,
                $validator->messages()
            )
        );
    }
}
