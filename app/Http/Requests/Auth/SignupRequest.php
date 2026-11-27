<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Http\Requests\Base\BaseRequest;
use App\Rules\PasswordInput;

class SignupRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'user_name' => ['required', 'string', 'max:255', 'unique:users,user_name'],
            'email_address' => ['required', 'email', 'max:255', 'unique:users,email_address'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'password' => [new PasswordInput, 'required', 'string', 'min:8', 'max:255', 'confirmed'],
            'user_type' => ['prohibited'], 'status' => ['prohibited'],
            'created_by' => ['prohibited'], 'updated_by' => ['prohibited'],
        ];
    }
}
