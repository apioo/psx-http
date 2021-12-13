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

namespace PSX\Http\Server;

use Psr\Http\Message\StreamInterface;
use PSX\Http\Http;
use PSX\Http\Parser\ResponseParser;
use PSX\Http\ResponseInterface;
use PSX\Http\Stream\StringStream;

/**
 * Basic sender which handles file stream bodies, content encoding and transfer
 * encoding
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class Sender implements SenderInterface
{
    public function send(ResponseInterface $response): void
    {
        if (in_array($response->getStatusCode(), array(100, 101, 204, 304))) {
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

    private function sendStatusLine(ResponseInterface $response)
    {
        $scheme = $response->getProtocolVersion();
        if (empty($scheme)) {
            $scheme = 'HTTP/1.1';
        }

        $code = $response->getStatusCode();
        if (!isset(Http::$codes[$code])) {
            $code = 200;
        }

        $this->sendHeader($scheme . ' ' . $code . ' ' . Http::$codes[$code]);
    }

    private function sendHeaders(ResponseInterface $response)
    {
        $headers = ResponseParser::buildHeaderFromMessage($response);

        foreach ($headers as $header) {
            $this->sendHeader($header);
        }
    }

    private function sendHeader($header)
    {
        header($header);
    }

    private function sendBody(ResponseInterface $response)
    {
        $body = $response->getBody();
        if ($body instanceof StreamInterface) {
            echo $body->__toString();
        }
    }
}
