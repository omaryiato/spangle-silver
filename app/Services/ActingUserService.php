<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory;

class ActingUserService
{
    public function __construct(private Factory $auth) {}

    public function id(): int
    {
        $id = $this->auth->guard()->id();
        if ($id === null) {
            throw new AuthenticationException;
        }

        return (int) $id;
    }

    public function attributes(array $data, bool $updating = false): array
    {
        unset($data['created_by'], $data['updated_by']);
        if (! $updating) {
            $data['created_by'] = $this->id();
        }
        $data['updated_by'] = $this->id();

        return $data;
    }
}
