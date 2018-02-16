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

namespace PSX\Http\Stream;

/**
 * StreamWrapperTrait
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
trait StreamWrapperTrait
{
    /**
     * @var \PSX\Http\StreamInterface
     */
    protected $stream;
    
    public function close()
    {
        $this->call();
        
        $this->stream->close();
    }

    public function detach()
    {
        $this->call();
        
        return $this->stream->detach();
    }

    public function getSize()
    {
        $this->call();
        
        return $this->stream->getSize();
    }

    public function tell()
    {
        $this->call();
        
        return $this->stream->tell();
    }

    public function eof()
    {
        $this->call();
        
        return $this->stream->eof();
    }

    public function rewind()
    {
        $this->call();
        
        return $this->stream->rewind();
    }

    public function isSeekable()
    {
        $this->call();
        
        return $this->stream->isSeekable();
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        $this->call();
        
        return $this->stream->seek($offset, $whence);
    }

    public function isWritable()
    {
        $this->call();
        
        return $this->stream->isWritable();
    }

    public function write($string)
    {
        $this->call();
        
        return $this->stream->write($string);
    }

    public function isReadable()
    {
        $this->call();
        
        return $this->stream->isReadable();
    }

    public function read($length)
    {
        $this->call();
        
        return $this->stream->read($length);
    }

    public function getContents()
    {
        $this->call();
        
        return $this->stream->getContents();
    }

    public function getMetadata($key = null)
    {
        $this->call();
        
        return $this->stream->getMetadata($key);
    }

    public function __toString()
    {
        $this->call();

        return $this->stream->__toString();
    }

    protected function call()
    {
    }
}
