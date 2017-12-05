<?php

namespace App\Http\Requests\Story;

use Illuminate\Foundation\Http\FormRequest;

class LeaveStoryRequest extends FormRequest
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
            if ($this->has('team_id') && ! $this->user()->ownedTeams->contains($this->get('team_id'))) {
                $validator->errors()->add(
                    'team_id',
                    trans('validation.exists', ['attribute' => 'team_id'])
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
            'team_id' => 'integer|exists:teams,id'
        ];
    }
}
