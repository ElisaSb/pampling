<?php

declare(strict_types=1);

namespace App\Service\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RequestService
{
    /**
     * @return mixed
     */
    public static function getField(Request $request, string $fieldName, bool $isRequired = true)
    {
        $requestData = \json_decode($request->getContent(), true);

        if (\array_key_exists($fieldName, $requestData)) {
            return $requestData[$fieldName];
        }

        if ($isRequired) {
            throw new BadRequestHttpException(\sprintf('Missing field %s', $fieldName));
        }

        return null;
    }
}