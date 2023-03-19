<?php
/*
 * PSX is an open source PHP framework to develop RESTful APIs.
 * For the current version and information visit <https://phpsx.org>
 *
 * Copyright 2010-2023 Christoph Kappestein <christoph.kappestein@gmail.com>
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

use PSX\Uri\UriInterface;

/**
 * This is a mutable version of the PSR HTTP message interface
 *
 * Per the HTTP specification, this interface includes properties for
 * each of the following:
 *
 * - Protocol version
 * - HTTP method
 * - URI
 * - Headers
 * - Message body
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 * @see     https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md
 */
interface RequestInterface extends MessageInterface
{
    /**
     * Returns the message's request-target
     */
    public function getRequestTarget(): string;

    /**
     * Sets an specific request-target
     *
     * @link http://tools.ietf.org/html/rfc7230#section-2.7
     */
    public function setRequestTarget(string $requestTarget): void;

    /**
     * Retrieves the HTTP method of the request i.e. GET, POST
     */
    public function getMethod(): string;

    /**
     * Sets the provided HTTP method. While HTTP method names are typically all 
     * uppercase characters, HTTP method names are case-sensitive and thus 
     * implementations SHOULD NOT modify the given string.
     */
    public function setMethod(string $method): void;

    /**
     * Retrieves the URI instance.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     */
    public function getUri(): UriInterface;

    /**
     * Sets the provided URI.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     */
    public function setUri(UriInterface $uri): void;

    /**
     * Retrieve attributes derived from the request. The request attributes 
     * should contain only additional information about the request i.e. 
     * "REMOTE_ADDR" from the $_SERVER variable which contains the ip address of
     * the client which has initiated the HTTP request
     */
    public function getAttributes(): array;

    /**
     * Retrieve a single derived request attribute
     */
    public function getAttribute(string $name): mixed;

    /**
     * This method allows setting a single derived request attribute
     */
    public function setAttribute(string $name, mixed $value): void;

    /**
     * Removes the specified derived request attribute
     */
    public function removeAttribute(string $name): void;
}
