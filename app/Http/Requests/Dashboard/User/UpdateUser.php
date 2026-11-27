<?php

namespace App\Http\Requests\Dashboard\User;

use App\Http\Requests\Base\BaseRequest;
use App\Rules\PasswordInput;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateUser extends BaseRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    protected function prepareForValidation(): void
    {
        if ($this->input('password') === null || $this->input('password') === '') {
            $this->request->remove('password');
            $this->json()->remove('password');
        }
    }

    public function rules(): array
    {
        $id = $this->route('user');

        return [
            'full_name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],

            'user_name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'user_name')
                    ->ignore($id),
            ],

            'phone_number' => [
                'sometimes',
                'nullable',
                'string',
                'max:20',
            ],

            'email_address' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email_address')
                    ->ignore($id),
            ],

            'password' => [new PasswordInput,
                'sometimes',
                'required',
                'string',
                'min:8',
                'max:255',
            ],

            'status' => [
                'sometimes',
                'required',
                'integer',
                'in:0,1',
            ],

            'user_type' => [
                'sometimes',
                'required',
                'integer',
                'in:0,1,2',
            ],
        ];
    }
}
