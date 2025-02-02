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

namespace PSX\Http\Stream;

use InvalidArgumentException;
use PSX\Http\Exception\StreamException;
use PSX\Http\StreamInterface;

/**
 * Stream which operates on a PHP resource
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class Stream implements StreamInterface
{
    private mixed $resource;
    private bool $seekable = false;
    private bool $readable = false;
    private bool $writable = false;

    public function __construct($resource)
    {
        if (!is_resource($resource)) {
            throw new InvalidArgumentException('Must be an resource');
        }

        $this->setResource($resource);
    }

    public function close(): void
    {
        if ($this->resource) {
            fclose($this->resource);
        }

        $this->detach();
    }

    public function detach()
    {
        $handle = $this->resource;

        $this->resource = null;
        $this->seekable = $this->writable = $this->readable = false;

        return $handle;
    }

    public function getSize(): ?int
    {
        if ($this->resource) {
            $stat = fstat($this->resource);
            if ($stat === false) {
                throw new StreamException('Unable to get stats from stream');
            }

            return $stat['size'] ?? null;
        }

        return null;
    }

    public function tell(): int
    {
        if ($this->resource) {
            $return = ftell($this->resource);
            if ($return === false) {
                throw new StreamException('Unable to tell the position from stream');
            }

            return $return;
        }

        return 0;
    }

    public function eof(): bool
    {
        if ($this->resource) {
            return feof($this->resource);
        }

        return true;
    }

    public function rewind(): void
    {
        if ($this->resource) {
            rewind($this->resource);
        }
    }

    public function isSeekable(): bool
    {
        return $this->seekable;
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        if ($this->resource && $this->seekable) {
            fseek($this->resource, $offset, $whence);
        }
    }

    public function isWritable(): bool
    {
        return $this->writable;
    }

    public function write(string $string): int
    {
        if ($this->resource && $this->writable) {
            $result = fwrite($this->resource, $string);
            if ($result === false) {
                throw new StreamException('Unable to write stream');
            }

            return $result;
        }

        return 0;
    }

    public function isReadable(): bool
    {
        return $this->readable;
    }

    public function read(int $length): string
    {
        if ($this->resource && $this->readable && $length > 0) {
            $content = fread($this->resource, $length);
            if ($content === false) {
                throw new StreamException('Unable to read stream');
            }

            return $content;
        }

        return '';
    }

    public function getContents(): string
    {
        if ($this->resource && $this->readable) {
            return (string) stream_get_contents($this->resource);
        }

        return '';
    }

    public function getMetadata(?string $key = null)
    {
        if ($this->resource) {
            $meta = stream_get_meta_data($this->resource);

            if ($key === null) {
                return $meta;
            } else {
                return $meta[$key] ?? null;
            }
        }

        return $key === null ? array() : null;
    }

    public function __toString(): string
    {
        if ($this->resource && $this->readable) {
            return (string) stream_get_contents($this->resource, -1, 0);
        }

        return '';
    }

    protected function setResource($resource): void
    {
        $meta = stream_get_meta_data($resource);
        $mode = $meta['mode'] . ' ';

        $this->resource = $resource;
        $this->seekable = $meta['seekable'];
        $this->writable = $mode[0] != 'r' || $mode[1] == '+';
        $this->readable = $mode[0] == 'r' || $mode[1] == '+';
    }
}
