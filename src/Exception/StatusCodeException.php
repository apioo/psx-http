<?php
/*
 * PSX is an open source PHP framework to develop RESTful APIs.
 * For the current version and information visit <https://phpsx.org>
 *
 * Copyright (c) Christoph Kappestein <christoph.kappestein@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace PSX\Http\Exception;

use PSX\Http\ExceptionThrower;
use PSX\Http\ResponseInterface;
use RuntimeException;

/**
 * StatusCodeException
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class StatusCodeException extends RuntimeException
{
    private int $statusCode;

    public function __construct(string $message, int $statusCode, \Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);

        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function isInformational(): bool
    {
        return $this->statusCode >= 100 && $this->statusCode < 200;
    }

    public function isSuccessful(): bool
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }

    public function isRedirection(): bool
    {
        return $this->statusCode >= 300 && $this->statusCode < 400;
    }

    public function isClientError(): bool
    {
        return $this->statusCode >= 400 && $this->statusCode < 500;
    }

    public function isServerError(): bool
    {
        return $this->statusCode >= 500 && $this->statusCode < 600;
    }

    /**
     * @deprecated
     * @see ExceptionThrower::throwOnRedirection
     */
    public static function throwOnRedirection(ResponseInterface $response): void
    {
        ExceptionThrower::throwOnRedirection($response);
    }

    /**
     * @deprecated
     * @see ExceptionThrower::throwOnError
     */
    public static function throwOnError(ResponseInterface $response): void
    {
        ExceptionThrower::throwOnError($response);
    }

    /**
     * @deprecated
     * @see ExceptionThrower::throwOnClientError
     */
    public static function throwOnClientError(ResponseInterface $response): void
    {
        ExceptionThrower::throwOnClientError($response);
    }

    /**
     * @deprecated
     * @see ExceptionThrower::throwOnServerError
     */
    public static function throwOnServerError(ResponseInterface $response): void
    {
        ExceptionThrower::throwOnServerError($response);
    }
}
