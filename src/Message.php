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

use InvalidArgumentException;
use Psr\Http\Message\StreamInterface as PsrStreamInterface;
use PSX\Http\Stream;

/**
 * Message
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class Message implements MessageInterface
{
    protected array $headers;
    protected mixed $body;
    protected ?string $protocol = null;

    public function __construct(array $headers = [], mixed $body = null)
    {
        $this->headers = $this->prepareHeaders($headers);
        $this->body = $this->prepareBody($body);
    }

    public function getProtocolVersion(): ?string
    {
        return $this->protocol;
    }

    public function setProtocolVersion(string $protocol): void
    {
        $this->protocol = $protocol;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = $this->prepareHeaders($headers);
    }

    public function hasHeader(string $name): bool
    {
        return array_key_exists(strtolower($name), $this->headers);
    }

    public function getHeader(string $name): string
    {
        $lines = $this->getHeaderLines($name);

        return $lines ? implode(', ', $lines) : '';
    }

    public function getHeaderLines(string $name): array
    {
        $name = strtolower($name);

        if (!$this->hasHeader($name)) {
            return [];
        }

        return $this->headers[$name];
    }

    public function setHeader(string $name, string|array $value): void
    {
        $this->headers[strtolower($name)] = $this->normalizeHeaderValue($value);
    }

    public function addHeader(string $name, string|array $value): void
    {
        $name = strtolower($name);

        if ($this->hasHeader($name)) {
            $this->setHeader($name, array_merge($this->headers[$name], $this->normalizeHeaderValue($value)));
        } else {
            $this->setHeader($name, $value);
        }
    }

    public function removeHeader(string $name): void
    {
        $name = strtolower($name);

        if ($this->hasHeader($name)) {
            unset($this->headers[$name]);
        }
    }

    public function getBody(): PsrStreamInterface
    {
        return $this->body;
    }

    public function setBody(PsrStreamInterface $body): void
    {
        $this->body = $body;
    }

    protected function prepareHeaders(array $headers): array
    {
        return array_map(array($this, 'normalizeHeaderValue'), array_change_key_case($headers));
    }

    protected function normalizeHeaderValue($value): array
    {
        return is_array($value) ? array_map('strval', $value) : [(string) $value];
    }

    protected function prepareBody($body): PsrStreamInterface
    {
        if ($body instanceof PsrStreamInterface) {
            return $body;
        } elseif ($body === null) {
            return new Stream\StringStream();
        } elseif (is_string($body)) {
            return new Stream\StringStream($body);
        } elseif (is_resource($body)) {
            return new Stream\Stream($body);
        } else {
            throw new InvalidArgumentException('Body must be either a PSX\Http\StreamInterface, string or resource');
        }
    }
}
