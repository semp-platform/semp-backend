<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

   public function rules(): array
{
    return [
        'name' => ['required', 'string', 'max:255'],

        'email' => [
            'required',
            'email',
            'max:255',
            'unique:users,email',
        ],

        'password' => [
            'required',
            'confirmed',
            Password::defaults(),
        ],

        'role' => [
            'required',
            'exists:roles,name',
        ],

        'political_party_id' => [
            'nullable',
            'exists:political_parties,id',
            Rule::requiredIf(
                $this->input('role') === 'Political Party Officer'
            ),
        ],
    ];
}

}
