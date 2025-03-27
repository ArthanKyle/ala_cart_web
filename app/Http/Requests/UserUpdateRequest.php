<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'sometimes|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users')->ignore($this->route('id')),
                function ($attribute, $value, $fail) {
                    if (auth()->user()->email !== $value) {
                        if (!request()->has('current_password') || !\Hash::check(request('current_password'), auth()->user()->password)) {
                            return $fail('You must provide your current password to change your email.');
                        }
                    }
                },
            ],
            'current_password' => 'sometimes|required_with:email|string',
            'phone_number' => 'sometimes|string|max:20',
            'address' => 'nullable|string|max:500',
        ];
    }
    
}
