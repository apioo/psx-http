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

/**
 * The HyperText Transfer Protocol (HTTP) 500 Internal Server Error server error response code indicates that the server
 * encountered an unexpected condition that prevented it from fulfilling the request.
 *
 * This error response is a generic "catch-all" response. Usually, this indicates the server cannot find a better 5xx
 * error code to response. Sometimes, server administrators log error responses like the 500 status code with more
 * details about the request to prevent the error from happening again in the future.
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class InternalServerErrorException extends ServerErrorException
{
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, 500, $previous);
    }
}
