<?php

namespace App\Http\Requests\Api\V1\User;

use Illuminate\Foundation\Http\FormRequest;

class BaseUserRequest extends FormRequest
{
    public function mappedAttributes(array $otherAttributes = []): array
    {
        $attributeMap = array_merge([
            'data.attributes.email'     => 'email',
            'data.attributes.name'      => 'name',
            'data.attributes.password'  => 'password',
            'data.attributes.isManager' => 'is_manager'
        ], $otherAttributes);

        $attributesToUpdate = [];

        foreach ($attributeMap as $key => $attribute) {
            if ( $this->has($key) ) {
                $value = $this->input($key);

                if ( $attribute === 'password' ) {
                    $value = bcrypt($value);
                }

                $attributesToUpdate[$attribute] = $value;
            }
        }

        return $attributesToUpdate;
    }

    public function messages(): array
    {
        return [];
    }
}
