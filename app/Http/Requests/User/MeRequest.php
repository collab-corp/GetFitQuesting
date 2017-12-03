<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class MeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() !== null;
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->has('relations') && ! relations_exists($this->user(), $this->get('relations'))) {
                $validator->errors()->add(
                    'relations',
                    trans('validation.exists', ['attribute' => 'relations'])
                );
            }
        });
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // the reason i chose relations over with or load,
            // is because it sounds better on the validation error.
            // "The selected with is invalid" vs "the selected relations is invalid".
            'relations' => 'nullable|array',
            'relations.*' => 'string'
        ];
    }
}
