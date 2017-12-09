<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamRequest extends FormRequest
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
            if ($this->has('guild_id') && $this->userIsNotAGuildMember()) {
                $validator->errors()->add('guild_id', trans('validator.exists', ['attribute' => 'guild_id']));
            }
        });
    }

    protected function userIsNotAGuildMember()
    {
        return ! $this->user()->guilds->contains($this->guild_id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string|min:2|max:40|unique:teams,name',
            'guild_id' => 'exists:guilds,id'
        ];
    }
}
