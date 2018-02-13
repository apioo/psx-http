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
 * Request
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class Request extends Message implements RequestInterface
{
    /**
     * @var string
     */
    protected $requestTarget;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var \PSX\Uri\UriInterface
     */
    protected $uri;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @param \PSX\Uri\UriInterface $uri
     * @param string $method
     * @param array $headers
     * @param string $body
     */
    public function __construct(UriInterface $uri, $method, array $headers = [], $body = null)
    {
        parent::__construct($headers, $body);

        $this->uri    = $uri;
        $this->method = $method;
    }

    /**
     * Returns the request target
     *
     * @return string
     */
    public function getRequestTarget()
    {
        if ($this->requestTarget !== null) {
            return $this->requestTarget;
        }

        $target = $this->uri->getPath();
        if (empty($target)) {
            $target = '/';
        }

        $query = $this->uri->getQuery();
        if (!empty($query)) {
            $target.= '?' . $query;
        }

        return $target;
    }

    /**
     * Sets the request target
     *
     * @param string $requestTarget
     */
    public function setRequestTarget($requestTarget)
    {
        $this->requestTarget = $requestTarget;
    }

    /**
     * Returns the request method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Sets the request method
     *
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * Returns the request uri
     *
     * @return \PSX\Uri\UriInterface
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Sets the request uri
     *
     * @param \PSX\Uri\UriInterface $uri
     */
    public function setUri(UriInterface $uri)
    {
        $this->uri = $uri;
    }

    /**
     * Converts the request object to an http request string
     *
     * @return string
     */
    public function toString()
    {
        $request = Parser\RequestParser::buildStatusLine($this) . Http::NEW_LINE;
        $headers = Parser\RequestParser::buildHeaderFromMessage($this);

        foreach ($headers as $header) {
            $request.= $header . Http::NEW_LINE;
        }

        $request.= Http::NEW_LINE;
        $request.= (string) $this->getBody();

        return $request;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getAttribute($name)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
    }

    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function removeAttribute($name)
    {
        if (isset($this->attributes[$name])) {
            unset($this->attributes[$name]);
        }
    }

    public function __toString()
    {
        return $this->toString();
    }
}
