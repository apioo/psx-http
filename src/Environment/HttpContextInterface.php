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
 * HTTP context which provides all needed parameters outside of the request 
 * body. This can be used as facade if you dont want to pass the raw HTTP 
 * request object to user-land functions
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
interface HttpContextInterface
{
    /**
     * Returns the HTTP request method i.e. GET
     */
    public function getMethod(): string;

    /**
     * Returns a specific header
     */
    public function getHeader(string $name): ?string;

    /**
     * Returns all available headers
     */
    public function getHeaders(): array;

    /**
     * Returns a specific fragment from the uri
     */
    public function getUriFragment(string $name): ?string;

    /**
     * Returns all available uri fragments
     */
    public function getUriFragments(): array;

    /**
     * Returns a query parameter from the uri. Those are parsed by the parse_str
     * function so the value is either a string or an array in case the
     * parameter uses a "[]" notation
     */
    public function getParameter(string $name): string|array;

    /**
     * Returns all available query parameters
     */
    public function getParameters(): array;
}
