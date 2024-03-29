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

namespace PSX\Http\Stream;

use PSX\Http\StreamInterface;

/**
 * Stream which works on a string
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class StringStream implements StreamInterface
{
    private ?string $data;
    private int $length;
    private int $pointer = 0;

    public function __construct(string $data = '')
    {
        $this->data   = $data;
        $this->length = mb_strlen($data);
    }

    public function close()
    {
        $this->data   = null;
        $this->length = 0;
    }

    public function detach()
    {
        if ($this->data !== null) {
            $handle = fopen('php://memory', 'r+');
            fwrite($handle, $this->data);
            fseek($handle, 0);

            $this->close();

            return $handle;
        }

        return null;
    }

    public function getSize()
    {
        return $this->length;
    }

    public function tell()
    {
        return $this->pointer;
    }

    public function eof()
    {
        if ($this->data !== null) {
            return $this->pointer >= $this->length;
        }

        return true;
    }

    public function rewind()
    {
        if ($this->data !== null) {
            $this->pointer = 0;
        }

        return true;
    }

    public function isSeekable()
    {
        return $this->data !== null;
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        if ($this->isSeekable()) {
            if ($whence === SEEK_SET) {
                $this->pointer = $offset;
            } elseif ($whence === SEEK_CUR) {
                $this->pointer+= $offset;
            } elseif ($whence === SEEK_END) {
                $this->pointer = $this->length + $offset;
            }
        }

        return false;
    }

    public function isWritable()
    {
        return $this->data !== null;
    }

    public function write($string)
    {
        if ($this->isWritable()) {
            $length = mb_strlen($string);
            $pre    = mb_substr($this->data ?? '', 0, $this->pointer);
            $post   = mb_substr($this->data ?? '', $this->pointer + $length);

            $this->data = $pre . $string . $post;

            $this->pointer+= $length;
            $this->length = mb_strlen($this->data);

            return $length;
        }

        return 0;
    }

    public function isReadable()
    {
        return $this->data !== null;
    }

    public function read($length)
    {
        if ($this->isReadable()) {
            $data = mb_substr($this->data ?? '', $this->pointer, $length);

            $this->pointer+= $length;

            return $data;
        }

        return '';
    }

    public function getContents()
    {
        if ($this->data === null) {
            return '';
        }

        $data = mb_substr($this->data, $this->pointer);

        $this->pointer = $this->length;

        return $data;
    }

    public function getMetadata($key = null)
    {
        return $key === null ? array() : null;
    }

    public function __toString()
    {
        $this->pointer = $this->length;

        return $this->data === null ? '' : $this->data;
    }
}
