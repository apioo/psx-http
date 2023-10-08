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

namespace PSX\Http\Server;

use PSX\Http\Http;
use PSX\Http\ResponseInterface;
use PSX\Http\Stream\StringStream;
use PSX\Http\StringBuilder;

/**
 * Basic sender which handles file stream bodies, content encoding and transfer encoding
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class Sender implements SenderInterface
{
    public function send(ResponseInterface $response): void
    {
        if (in_array($response->getStatusCode(), [100, 101, 204, 304])) {
            // remove body on specific status codes
            $response->setBody(new StringStream(''));
        } elseif ($response->hasHeader('Location')) {
            // if we have a location header we dont send any content
            $response->setBody(new StringStream(''));
        }

        if ($this->shouldSendHeader()) {
            // send status line
            $this->sendStatusLine($response);

            // send headers
            $this->sendHeaders($response);
        }

        // send body
        $this->sendBody($response);
    }

    private function shouldSendHeader(): bool
    {
        return PHP_SAPI != 'cli' && !headers_sent();
    }

    private function sendStatusLine(ResponseInterface $response): void
    {
        $scheme = $response->getProtocolVersion();
        if (empty($scheme)) {
            $scheme = 'HTTP/1.1';
        }

        $code = $response->getStatusCode();
        if (!isset(Http::CODES[$code])) {
            $code = 200;
        }

        header($scheme . ' ' . $code . ' ' . Http::CODES[$code]);
    }

    private function sendHeaders(ResponseInterface $response): void
    {
        $headers = StringBuilder::headerFromMessage($response);

        foreach ($headers as $header) {
            header($header);
        }
    }

    private function sendBody(ResponseInterface $response): void
    {
        echo $response->getBody()->__toString();
    }
}
