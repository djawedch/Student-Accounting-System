<?php

namespace App\Http\Requests\Admin\User;

class StoreUserRequest extends BaseUserRequest
{
    public function rules(): array
    {
        return array_merge($this->commonRules(), [
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
    }
}
