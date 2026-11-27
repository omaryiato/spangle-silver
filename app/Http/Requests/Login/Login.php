<?php

declare(strict_types=1);

namespace App\Http\Requests\Login;

use App\Http\Requests\Base\BaseRequest;
use App\Rules\PasswordInput;

class Login extends BaseRequest
{
    protected function prepareForValidation(): void
    {
        if (! $this->has('email_address') && $this->has('email')) {
            $this->merge(['email_address' => $this->input('email')]);
        }
    }

    public function rules(): array
    {
        return ['email_address' => ['required', 'email', 'max:255'], 'password' => [new PasswordInput, 'required', 'string', 'max:255']];
    }
}
