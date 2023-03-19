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

namespace PSX\Http\Parser;

use InvalidArgumentException;
use PSX\Http\Http;
use PSX\Http\MessageInterface;

/**
 * ParserAbstract
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
abstract class ParserAbstract
{
    const MODE_STRICT = 0x1;
    const MODE_LOOSE  = 0x2;

    protected int $mode;

    /**
     * The mode indicates how the header is detected in strict mode we search
     * exactly for CRLF CRLF in loose mode we look for the first empty line. In
     * loose mode we can parse an header wich was defined in the code means is
     * not strictly seperated by CRLF
     */
    public function __construct(int $mode = self::MODE_STRICT)
    {
        if ($mode == self::MODE_STRICT || $mode == self::MODE_LOOSE) {
            $this->mode = $mode;
        } else {
            throw new InvalidArgumentException('Invalid parse mode');
        }
    }

    /**
     * Converts an raw http message into an PSX\Http\Message object
     */
    abstract public function parse(string $content): MessageInterface;

    /**
     * Splits an given http message into the header and body part
     */
    protected function splitMessage(string $message): array
    {
        if ($this->mode == self::MODE_STRICT) {
            $pos    = strpos($message, Http::NEW_LINE . Http::NEW_LINE);
            $header = substr($message, 0, $pos);
            $body   = trim(substr($message, $pos + 1));
        } elseif ($this->mode == self::MODE_LOOSE) {
            $lines  = explode("\n", $message);
            $header = '';
            $body   = '';
            $found  = false;
            $count  = count($lines);

            foreach ($lines as $i => $line) {
                $line = trim($line);

                if (!$found && empty($line)) {
                    $found = true;
                    continue;
                }

                if (!$found) {
                    $header.= $line . Http::NEW_LINE;
                } else {
                    $body.= $line . ($i < $count - 1 ? "\n" : '');
                }
            }
        }

        return array($header, $body);
    }

    protected function normalize(string $content): string
    {
        if (empty($content)) {
            throw new InvalidArgumentException('Empty message');
        }

        if ($this->mode == self::MODE_LOOSE) {
            $content = str_replace(array("\r\n", "\n", "\r"), "\n", $content);
        }

        return $content;
    }

    /**
     * Parses an raw http header string into an Message object
     */
    protected function headerToArray(MessageInterface $message, string $header)
    {
        $lines = explode(Http::NEW_LINE, $header);

        foreach ($lines as $line) {
            $parts = explode(':', $line, 2);

            if (isset($parts[0]) && isset($parts[1])) {
                $key   = $parts[0];
                $value = substr($parts[1], 1);

                $message->addHeader($key, $value);
            }
        }
    }

    protected function getStatusLine(string $message): string|false
    {
        if ($this->mode == self::MODE_STRICT) {
            $pos = strpos($message, Http::NEW_LINE);
        } elseif ($this->mode == self::MODE_LOOSE) {
            $pos = strpos($message, "\n");
        } else {
            throw new InvalidArgumentException('Provided an invalid mode');
        }

        return $pos !== false ? substr($message, 0, $pos) : false;
    }

    public static function buildHeaderFromMessage(MessageInterface $message): array
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
