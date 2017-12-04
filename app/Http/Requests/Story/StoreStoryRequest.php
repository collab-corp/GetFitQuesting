<?php

namespace App\Http\Requests\Story;

use App\Admin;
use Illuminate\Foundation\Http\FormRequest;

class StoreStoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return ! is_null($this->user());
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        if (! $this->has('creator_id')) {
            $this['creator_id'] = $this->user()->id;
        }

        $validator->after(function ($validator) {
            if ($this->has('creator_id') && $this['creator_id'] !== $this->user()->id) {
                if (! Admin::check($this->user())) {
                    $validator->errors()->add(
                        'creator_id',
                        trans('validation.exists', ['attribute' => 'creator_id'])
                    );
                }
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
            'creator_id' => 'nullable|integer|exists:users,id',
            'name' => 'required|string|min:4|max:60',
            'body' => 'required|string|max:255'
        ];
    }
}
