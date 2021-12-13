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

namespace PSX\Http\Environment;

/**
 * Represents an HTTP response which is generated i.e. by a controller
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
interface HttpResponseInterface
{
    /**
     * Returns the status code of the HTTP response
     *
     * @see https://tools.ietf.org/html/rfc7231#section-6
     */
    public function getStatusCode(): int;

    /**
     * Returns all available headers of the response. The header keys are all
     * lowercased
     */
    public function getHeaders(): array;

    /**
     * Returns a single header based on the provided header name or null if the
     * header does not exist. The name is case insensitive
     */
    public function getHeader(string $name): ?string;

    /**
     * Returns the body of the response
     */
    public function getBody(): mixed;
}
