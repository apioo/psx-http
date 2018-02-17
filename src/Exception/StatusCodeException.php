<?php
/*
 * PSX is a open source PHP framework to develop RESTful APIs.
 * For the current version and informations visit <http://phpsx.org>
 *
 * Copyright 2010-2018 Christoph Kappestein <christoph.kappestein@gmail.com>
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

use InvalidArgumentException;
use PSX\Http\Authentication;
use PSX\Http\Http;
use PSX\Http\ResponseInterface;
use RuntimeException;

/**
 * StatusCodeException
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class StatusCodeException extends RuntimeException
{
    protected $statusCode;

    public function __construct($message, $statusCode)
    {
        parent::__construct($message);

        if (isset(Http::$codes[$statusCode])) {
            $this->statusCode = $statusCode;
        } else {
            throw new InvalidArgumentException('Invalid http status code');
        }
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function isInformational()
    {
        return $this->statusCode >= 100 && $this->statusCode < 200;
    }

    public function isSuccessful()
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }

    public function isRedirection()
    {
        return $this->statusCode >= 300 && $this->statusCode < 400;
    }

    public function isClientError()
    {
        return $this->statusCode >= 400 && $this->statusCode < 500;
    }

    public function isServerError()
    {
        return $this->statusCode >= 500 && $this->statusCode < 600;
    }

    public static function throwOnRedirection(ResponseInterface $response)
    {
        $statusCode = $response->getStatusCode();
        $location   = $response->getHeader('Location');

        switch ($statusCode) {
            case 301:
                throw new MovedPermanentlyException($location);

            case 302:
                throw new FoundException($location);

            case 303:
                throw new SeeOtherException($location);

            case 304:
                throw new NotModifiedException($location);

            case 307:
                throw new TemporaryRedirectException($location);
        }
        
        if ($statusCode >= 300 && $statusCode < 400) {
            throw new RedirectionException($statusCode);
        }
    }

    public static function throwOnError(ResponseInterface $response)
    {
        $code = $response->getStatusCode();
        if ($code >= 400 && $code < 500) {
            self::throwOnClientError($response);
        } elseif ($code >= 500 && $code < 600) {
            self::throwOnServerError($response);
        }
    }

    public static function throwOnClientError(ResponseInterface $response)
    {
        $statusCode = $response->getStatusCode();
        $message    = $response->getReasonPhrase();

        switch ($statusCode) {
            case 400:
                throw new BadRequestException($message);
            case 401:
                $parts = explode(' ', $response->getHeader('WWW-Authenticate'), 2);
                $type  = isset($parts[0]) ? $parts[0] : null;
                $data  = isset($parts[1]) ? $parts[1] : null;

                $params = [];
                if (!empty($data)) {
                    $params = Authentication::decodeParameters($data);
                }

                throw new UnauthorizedException($message, $type, $params);
            case 403:
                throw new ForbiddenException($message);
            case 404:
                throw new NotFoundException($message);
            case 405:
                $allow = $response->getHeader('Allow');
                $allow = explode(',', $allow);
                $allow = array_map('trim', $allow);
                $allow = array_filter($allow);

                throw new MethodNotAllowedException($message, $allow);
            case 406:
                throw new NotAcceptableException($message);
            case 409:
                throw new ConflictException($message);
            case 410:
                throw new GoneException($message);
            case 412:
                throw new PreconditionFailedException($message);
            case 415:
                throw new UnsupportedMediaTypeException($message);
        }

        if ($statusCode >= 400 && $statusCode < 500) {
            throw new ClientErrorException($message, $statusCode);
        }
    }

    public static function throwOnServerError(ResponseInterface $response)
    {
        $statusCode = $response->getStatusCode();
        $message    = $response->getReasonPhrase();

        switch ($statusCode) {
            case 500:
                throw new InternalServerErrorException($message);
            case 501:
                throw new NotImplementedException($message);
            case 503:
                throw new ServiceUnavailableException($message);
        }

        if ($statusCode >= 500 && $statusCode < 600) {
            throw new ServerErrorException($message, $statusCode);
        }
    }
}
