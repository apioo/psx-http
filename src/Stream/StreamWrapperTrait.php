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
 * StreamWrapperTrait
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
trait StreamWrapperTrait
{
    private StreamInterface $stream;
    
    public function close(): void
    {
        $this->call();
        
        $this->stream->close();
    }

    public function detach()
    {
        $this->call();
        
        return $this->stream->detach();
    }

    public function getSize(): ?int
    {
        $this->call();
        
        return $this->stream->getSize();
    }

    public function tell(): int
    {
        $this->call();
        
        return $this->stream->tell();
    }

    public function eof(): bool
    {
        $this->call();
        
        return $this->stream->eof();
    }

    public function rewind(): void
    {
        $this->call();

        $this->stream->rewind();
    }

    public function isSeekable(): bool
    {
        $this->call();
        
        return $this->stream->isSeekable();
    }

    public function seek($offset, $whence = SEEK_SET): void
    {
        $this->call();

        $this->stream->seek($offset, $whence);
    }

    public function isWritable(): bool
    {
        $this->call();

        return $this->stream->isWritable();
    }

    public function write($string): int
    {
        $this->call();

        return $this->stream->write($string);
    }

    public function isReadable(): bool
    {
        $this->call();
        
        return $this->stream->isReadable();
    }

    public function read(int $length): string
    {
        $this->call();
        
        return $this->stream->read($length);
    }

    public function getContents(): string
    {
        $this->call();
        
        return $this->stream->getContents();
    }

    public function getMetadata($key = null)
    {
        $this->call();
        
        return $this->stream->getMetadata($key);
    }

    public function __toString(): string
    {
        $this->call();

        return $this->stream->__toString();
    }

    protected function call()
    {
    }
}
