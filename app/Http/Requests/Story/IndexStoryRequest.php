<?php

namespace App\Http\Requests\Story;

use Illuminate\Foundation\Http\FormRequest;

class IndexStoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
            'perPage' => 'nullable|integer|max:25',
            'columns' => 'nullable|array',
            'pageName' => 'nullable|string',
            'page' => 'nullable|integer',

            'search' => 'string|min:2|max:40'

            'relations' => 'nullable|array',
            'relations.*' => 'string'
        ];
    }
}
