<?php

declare(strict_types=1);

namespace App\Exception\User;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserNotFoundException extends NotFoundHttpException
{
    private const MESSAGE = 'User with id %s not found';

    public static function fromId(int $id)
    {
        throw new self(\sprintf(self::MESSAGE, $id));
    }
}