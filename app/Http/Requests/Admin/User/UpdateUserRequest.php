<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Validation\Rule;

class UpdateUserRequest extends BaseUserRequest
{
    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return array_merge($this->commonRules(), [
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);
    }
}
