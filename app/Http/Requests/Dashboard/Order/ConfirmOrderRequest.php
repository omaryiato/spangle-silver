<?php

declare(strict_types=1);

namespace App\Http\Requests\Dashboard\Order;

use App\Http\Requests\Base\BaseRequest;

class ConfirmOrderRequest extends BaseRequest
{
    public function rules(): array
    {
        return ['status' => ['required', 'integer', 'in:2,3,4,5']];
    }
}
