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
 * Stream which opens the stream only on actual usage
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class LazyStream extends Stream
{
    protected $uri;
    protected $mode;
    protected $opened = false;

    public function __construct($uri, $mode = 'rb')
    {
        $this->uri  = $uri;
        $this->mode = $mode;
    }

    public function close()
    {
        $this->open();

        parent::close();
    }

    public function detach()
    {
        $this->open();

        return parent::detach();
    }

    public function getSize()
    {
        $this->open();

        return parent::getSize();
    }

    public function tell()
    {
        $this->open();

        return parent::tell();
    }

    public function eof()
    {
        $this->open();

        return parent::eof();
    }

    public function rewind()
    {
        $this->open();

        return parent::rewind();
    }

    public function isSeekable()
    {
        $this->open();

        return parent::isSeekable();
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        $this->open();

        return parent::seek($offset, $whence);
    }

    public function isWritable()
    {
        $this->open();

        return parent::isWritable();
    }

    public function write($string)
    {
        $this->open();

        return parent::write($string);
    }

    public function isReadable()
    {
        $this->open();

        return parent::isReadable();
    }

    public function read($length)
    {
        $this->open();

        return parent::read($length);
    }

    public function getContents()
    {
        $this->open();

        return parent::getContents();
    }

    public function getMetadata($key = null)
    {
        $this->open();

        return parent::getMetadata($key);
    }

    public function __toString()
    {
        $this->open();

        return parent::__toString();
    }

    private function open()
    {
        if ($this->opened) {
            return;
        }

        $this->setResource(fopen($this->uri, $this->mode));

        $this->opened = true;
    }
}
