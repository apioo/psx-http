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

/**
 * Response
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class Response extends Message implements ResponseInterface
{
    protected int $code;
    protected ?string $reasonPhrase;

    public function __construct(?int $code = null, array $headers = [], mixed $body = null)
    {
        parent::__construct($headers, $body);

        if ($code !== null) {
            $this->setStatus($code);
        } else {
            $this->setStatus(200);
        }
    }

    /**
     * Returns the http response code
     */
    public function getStatusCode(): int
    {
        return $this->code;
    }

    /**
     * Returns the http response message. That means the last part of the status
     * line i.e. "OK" from an 200 response
     */
    public function getReasonPhrase(): ?string
    {
        return $this->reasonPhrase;
    }

    /**
     * Sets the status code and reason phrase. If no reason phrase is provided
     * the standard message according to the status code is used
     */
    public function setStatus(int $code, ?string $reasonPhrase = null): void
    {
        $this->code = $code;

        if ($reasonPhrase !== null) {
            $this->reasonPhrase = $reasonPhrase;
        } elseif (isset(Http::CODES[$this->code])) {
            $this->reasonPhrase = Http::CODES[$this->code];
        } else {
            $this->reasonPhrase = null;
        }
    }

    /**
     * Converts the response object to an http response string
     */
    public function toString(): string
    {
        $response = StringBuilder::responseStatusLine($this) . Http::NEW_LINE;
        $headers  = StringBuilder::headerFromMessage($this);

        foreach ($headers as $header) {
            $response.= $header . Http::NEW_LINE;
        }

        $response.= Http::NEW_LINE;
        $response.= $this->getBody();

        return $response;
    }

    public function __toString()
    {
        return $this->toString();
    }
}
