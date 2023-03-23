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
 * StreamInterface
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 * @see     https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md
 */
class StringBuilder
{
    public static function responseStatusLine(ResponseInterface $response): string
    {
        $protocol = $response->getProtocolVersion();
        $code     = $response->getStatusCode();
        $phrase   = $response->getReasonPhrase();

        if (empty($code)) {
            throw new \RuntimeException('Status code not set');
        }

        $protocol = !empty($protocol) ? $protocol : 'HTTP/1.1';

        if (empty($phrase) && isset(Http::CODES[$code])) {
            $phrase = Http::CODES[$code];
        }

        if (empty($phrase)) {
            throw new \RuntimeException('No reason phrase provided');
        }

        return $protocol . ' ' . $code . ' ' . $phrase;
    }

    public static function requestStatusLine(RequestInterface $request): string
    {
        $method   = $request->getMethod();
        $target   = $request->getRequestTarget();
        $protocol = $request->getProtocolVersion();

        if (empty($target)) {
            throw new \RuntimeException('Target not set');
        }

        $method   = !empty($method) ? $method : 'GET';
        $protocol = !empty($protocol) ? $protocol : 'HTTP/1.1';

        return $method . ' ' . $target . ' ' . $protocol;
    }

    public static function headerFromMessage(MessageInterface $message): array
    {
        $headers = $message->getHeaders();
        $result  = array();

        foreach ($headers as $key => $value) {
            if ($key == 'set-cookie') {
                foreach ($value as $cookie) {
                    $result[] = $key . ': ' . $cookie;
                }
            } else {
                $result[] = $key . ': ' . implode(', ', $value);
            }
        }

        return $result;
    }
}
