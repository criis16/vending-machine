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

        $message = [];
        $requestParams = \array_keys($request);
        $totalParams = \count($requestParams);

        if ($totalParams > self::MAX_ALLOWED_PARAMS) {
            $message['must_be_one'] = 'It must be only one parameter at the request data.';
        }

        foreach ($requestParams as $requestKey) {
            if (!\in_array($requestKey, $requiredFields)) {
                $message[$requestKey] = \ucfirst($requestKey) . ' is not a required field';
            }

            if (empty($requestKey)) {
                $message[$requestKey] = \ucfirst($requestKey) . ' is a required field';
            }

            if (!\is_numeric($request[$requestKey])) {
                $message[$requestKey] = \ucfirst($requestKey) . ' is not a numeric value';
            }
        }

        if (!empty($message)) {
            throw new RequestException($message);
        }
    }
}
