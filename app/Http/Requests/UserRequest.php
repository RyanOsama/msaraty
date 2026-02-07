<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($this->user),
            ],
            'password' => [
                $this->isMethod('post') ? 'required' : 'nullable',
                'min:8',
            ],
            'role_id' => [
                'required',
                'exists:roles,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'username.unique' => 'اسم المستخدم مستخدم بالفعل',
            'password.min'    => 'كلمة المرور يجب أن تكون 8 أحرف أو أكثر',
        ];
    }
}
