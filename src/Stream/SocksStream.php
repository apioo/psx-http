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
 * The socks stream is used by the socks http handler
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class SocksStream extends Stream
{
    protected $resource;
    protected $contentLength;
    protected $chunkedEncoding;

    public function __construct($resource, $contentLength, $chunkedEncoding = false)
    {
        parent::__construct($resource);

        $this->contentLength   = $contentLength;
        $this->chunkedEncoding = $chunkedEncoding;
    }

    public function detach()
    {
        $this->contentLength   = null;
        $this->chunkedEncoding = false;

        return parent::detach();
    }

    public function getSize()
    {
        return $this->contentLength;
    }

    public function isWritable()
    {
        return false;
    }

    public function isChunkEncoded()
    {
        return $this->chunkedEncoding;
    }

    public function getChunkSize()
    {
        return hexdec(trim(fgets($this->resource)));
    }

    public function readLine()
    {
        return fgets($this->resource);
    }

    public function __toString()
    {
        if (!$this->resource) {
            return '';
        }

        $this->seek(0);

        if ($this->contentLength > 0) {
            $body = $this->read($this->contentLength);
        } elseif ($this->chunkedEncoding) {
            $body = '';

            do {
                $size = $this->getChunkSize();
                $body.= $this->read($size);

                $this->readLine();
            } while ($size > 0);
        } else {
            $body = $this->getContents();
        }

        return $body;
    }
}
