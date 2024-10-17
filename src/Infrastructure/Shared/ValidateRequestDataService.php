<?php

namespace App\Infrastructure\Shared;

use App\Infrastructure\Shared\Exceptions\RequestException;

class ValidateRequestDataService
{
    private const MAX_ALLOWED_PARAMS = 1;

    /**
     * Validates the request input data
     *
     * @param array $request
     * @param array $requiredFields
     * @throws RequestException
     * @return void
     */
    public function validate(
        array $request,
        array $requiredFields
    ): void {
        if (empty($request)) {
            throw new RequestException(['empty_body' => 'There is not request data.']);
        }

        $totalParams = 0;
        $message = [];

        foreach (\array_keys($request) as $requestKey) {
            if (!\in_array($requestKey, $requiredFields) || empty($requestKey)) {
                $message[$requestKey] = \ucfirst($requestKey) . ' is a required field';
            }
            $totalParams++;
        }

        if ($totalParams > self::MAX_ALLOWED_PARAMS) {
            $message['must_be_one'] = 'It must be only one parameter at the request data.';
        }

        if (!empty($message)) {
            throw new RequestException($message);
        }
    }
}
