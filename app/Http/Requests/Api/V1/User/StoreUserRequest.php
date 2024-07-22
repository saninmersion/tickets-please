<?php

namespace App\Http\Requests\Api\V1\User;

class StoreUserRequest extends BaseUserRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'data.attributes.name'      => ['required', 'string'],
            'data.attributes.email'     => ['required', 'email'],
            'data.attributes.password'  => ['required', 'string'],
            'data.attributes.isManager' => ['required', 'boolean'],
        ];

        return $rules;
    }
}
