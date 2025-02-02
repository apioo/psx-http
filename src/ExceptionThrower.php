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

namespace PSX\Http;

use PSX\Http\Exception\BadRequestException;
use PSX\Http\Exception\ClientErrorException;
use PSX\Http\Exception\ConflictException;
use PSX\Http\Exception\ForbiddenException;
use PSX\Http\Exception\FoundException;
use PSX\Http\Exception\GoneException;
use PSX\Http\Exception\InternalServerErrorException;
use PSX\Http\Exception\MethodNotAllowedException;
use PSX\Http\Exception\MovedPermanentlyException;
use PSX\Http\Exception\NotAcceptableException;
use PSX\Http\Exception\NotFoundException;
use PSX\Http\Exception\NotImplementedException;
use PSX\Http\Exception\NotModifiedException;
use PSX\Http\Exception\PermanentRedirectException;
use PSX\Http\Exception\PreconditionFailedException;
use PSX\Http\Exception\RedirectionException;
use PSX\Http\Exception\SeeOtherException;
use PSX\Http\Exception\ServerErrorException;
use PSX\Http\Exception\ServiceUnavailableException;
use PSX\Http\Exception\TemporaryRedirectException;
use PSX\Http\Exception\UnauthorizedException;
use PSX\Http\Exception\UnsupportedMediaTypeException;

/**
 * Util class to handle Authentication header
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class ExceptionThrower
{
    public static function throw(ResponseInterface $response): void
    {
        $code = $response->getStatusCode();
        if ($code >= 300 && $code < 400) {
            self::throwOnRedirection($response);
        } elseif ($code >= 400 && $code < 600) {
            self::throwOnError($response);
        }
    }

    public static function throwOnRedirection(ResponseInterface $response): void
    {
        $code = $response->getStatusCode();
        $location = $response->getHeader('Location');

        switch ($code) {
            case 301:
                throw new MovedPermanentlyException($location);

            case 302:
                throw new FoundException($location);

            case 303:
                throw new SeeOtherException($location);

            case 304:
                throw new NotModifiedException();

            case 307:
                throw new TemporaryRedirectException($location);

            case 308:
                throw new PermanentRedirectException($location);
        }

        if ($code >= 300 && $code < 400) {
            throw new RedirectionException($code);
        }
    }

    public static function throwOnError(ResponseInterface $response): void
    {
        $code = $response->getStatusCode();
        if ($code >= 400 && $code < 500) {
            self::throwOnClientError($response);
        } elseif ($code >= 500 && $code < 600) {
            self::throwOnServerError($response);
        }
    }

    public static function throwOnClientError(ResponseInterface $response): void
    {
        $code = $response->getStatusCode();
        $message = $response->getReasonPhrase() ?? '';

        switch ($code) {
            case 400:
                throw new BadRequestException($message);
            case 401:
                $parts = explode(' ', $response->getHeader('WWW-Authenticate'), 2);
                $type  = $parts[0] ?? '';
                $data  = $parts[1] ?? '';

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

        if ($code >= 400 && $code < 500) {
            throw new ClientErrorException($message, $code);
        }
    }

    public static function throwOnServerError(ResponseInterface $response): void
    {
        $code = $response->getStatusCode();
        $message = $response->getReasonPhrase() ?? '';

        switch ($code) {
            case 500:
                throw new InternalServerErrorException($message);
            case 501:
                throw new NotImplementedException($message);
            case 503:
                throw new ServiceUnavailableException($message);
        }

        if ($code >= 500 && $code < 600) {
            throw new ServerErrorException($message, $code);
        }
    }
}
