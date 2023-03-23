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

use PSX\Uri\Uri;
use PSX\Uri\UriInterface;

/**
 * Request
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class Request extends Message implements RequestInterface
{
    protected ?string $requestTarget = null;
    protected string $method;
    protected UriInterface $uri;
    protected array $attributes = [];

    public function __construct(UriInterface|string $uri, string $method, array $headers = [], mixed $body = null)
    {
        parent::__construct($headers, $body);

        if (is_string($uri)) {
            $uri = Uri::parse($uri);
        }

        $this->uri = $uri;
        $this->method = $method;
    }

    /**
     * Returns the request target
     */
    public function getRequestTarget(): string
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
     */
    public function setRequestTarget(string $requestTarget): void
    {
        $this->requestTarget = $requestTarget;
    }

    /**
     * Returns the request method
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Sets the request method
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    /**
     * Returns the request uri
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * Sets the request uri
     */
    public function setUri(UriInterface $uri): void
    {
        $this->uri = $uri;
    }

    /**
     * Converts the request object to an http request string
     */
    public function toString(): string
    {
        $request = Parser\RequestParser::buildStatusLine($this) . Http::NEW_LINE;
        $headers = Parser\RequestParser::buildHeaderFromMessage($this);

        foreach ($headers as $header) {
            $request.= $header . Http::NEW_LINE;
        }

        $request.= Http::NEW_LINE;
        $request.= $this->getBody();

        return $request;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute($name): mixed
    {
        return $this->attributes[$name] ?? null;
    }

    public function setAttribute($name, $value): void
    {
        $this->attributes[$name] = $value;
    }

    public function removeAttribute($name): void
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
