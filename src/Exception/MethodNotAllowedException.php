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
 * The HyperText Transfer Protocol (HTTP) 405 Method Not Allowed response status code indicates that the server knows
 * the request method, but the target resource doesn't support this method.
 *
 * The server must generate an Allow header field in a 405 status code response. The field must contain a list of
 * methods that the target resource currently supports.
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class MethodNotAllowedException extends ClientErrorException
{
    private array $allowedMethods;

    public function __construct(string $message, array $allowedMethods, ?\Throwable $previous = null)
    {
        parent::__construct($message, 405, $previous);

        $this->allowedMethods = $allowedMethods;
    }

    public function getAllowedMethods(): array
    {
        return $this->allowedMethods;
    }
}
