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
 * @link    http://phpsx.org
 * @see     https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md
 */
interface RequestInterface extends MessageInterface
{
    /**
     * Returns the message's request-target
     *
     * @return string
     */
    public function getRequestTarget();

    /**
     * Sets an specific request-target
     *
     * @link http://tools.ietf.org/html/rfc7230#section-2.7
     * @param string $requestTarget
     * @return void
     */
    public function setRequestTarget($requestTarget);

    /**
     * Retrieves the HTTP method of the request i.e. GET, POST
     *
     * @return string
     */
    public function getMethod();

    /**
     * Sets the provided HTTP method. While HTTP method names are typically all 
     * uppercase characters, HTTP method names are case-sensitive and thus 
     * implementations SHOULD NOT modify the given string.
     *
     * @param string $method
     * @return void
     * @throws \InvalidArgumentException
     */
    public function setMethod($method);

    /**
     * Retrieves the URI instance.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     * @return \PSX\Uri\UriInterface
     */
    public function getUri();

    /**
     * Sets the provided URI.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     * @param \PSX\Uri\UriInterface $uri
     * @return void
     */
    public function setUri(UriInterface $uri);

    /**
     * Retrieve attributes derived from the request. The request attributes 
     * should contain only additional information about the request i.e. 
     * "REMOTE_ADDR" from the $_SERVER variable which contains the ip address of
     * the client which has initiated the HTTP request
     *
     * @return array
     */
    public function getAttributes();

    /**
     * Retrieve a single derived request attribute
     *
     * @param string $name
     * @return mixed
     */
    public function getAttribute($name);

    /**
     * This method allows setting a single derived request attribute
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function setAttribute($name, $value);

    /**
     * Removes the specified derived request attribute
     *
     * @param string $name
     * @return void
     */
    public function removeAttribute($name);
}
