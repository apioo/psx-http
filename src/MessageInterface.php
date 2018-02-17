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

use Psr\Http\Message\StreamInterface as PsrStreamInterface;

/**
 * This is a mutable version of the PSR HTTP message interface
 *
 * HTTP messages consist of requests from a client to a server and responses
 * from a server to a client. This interface defines the methods common to
 * each.
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 * @link    http://www.ietf.org/rfc/rfc7230.txt
 * @link    http://www.ietf.org/rfc/rfc7231.txt
 * @see     https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md
 */
interface MessageInterface
{
    /**
     * Retrieves the HTTP protocol version as a string. The string MUST contain 
     * only the HTTP version number (e.g., "1.1", "1.0").
     *
     * @return string
     */
    public function getProtocolVersion();

    /**
     * Sets the specified HTTP protocol version. The version string MUST contain 
     * only the HTTP version number (e.g., "1.1", "1.0").
     *
     * @param string $protocol
     * @return void
     */
    public function setProtocolVersion($protocol);

    /**
     * Returns an associative array of the message's headers. Each key MUST be a 
     * header name, and each value MUST be an array of strings for that header
     *
     * @return array.
     */
    public function getHeaders();

    /**
     * Sets all message headers which overwrites all existing headers. Each key 
     * MUST be a header name, and each value MUST be an array of strings for 
     * that header
     *
     * @param array $headers
     * @return void
     */
    public function setHeaders(array $headers);

    /**
     * Checks if a header exists by the given case-insensitive name. Returns 
     * true if any header names match the given header name using a 
     * case-insensitive string comparison. Returns false if no matching header 
     * name is found in the message.
     * 
     * @param string $name
     * @return boolean
     */
    public function hasHeader($name);

    /**
     * Retrieves a message header value by the given case-insensitive name. This 
     * method returns a string. If a header has multiple values they will be 
     * concatenated with a comma. If the header does not appear in the message, 
     * this method MUST return null
     *
     * @param string $name
     * @return string|null
     */
    public function getHeader($name);

    /**
     * Retrieves a header by the given case-insensitive name as an array of
     * strings
     *
     * @param string $name
     * @return array
     */
    public function getHeaderLines($name);

    /**
     * Sets a new header, replacing any existing values of any headers with the
     * same case-insensitive name
     *
     * @param string $name
     * @param string|array $value
     */
    public function setHeader($name, $value);

    /**
     * Adds a new header, the value gets appended if such a header already
     * exists
     *
     * @param string $name
     * @param string|array $value
     */
    public function addHeader($name, $value);

    /**
     * Removes the given header name
     *
     * @param string $name
     */
    public function removeHeader($name);

    /**
     * Gets the body of the message
     *
     * @return \Psr\Http\Message\StreamInterface
     */
    public function getBody();

    /**
     * Sets the specified message body
     *
     * @param \Psr\Http\Message\StreamInterface $body
     */
    public function setBody(PsrStreamInterface $body);
}
