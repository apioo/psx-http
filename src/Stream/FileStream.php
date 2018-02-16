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

use PSX\Http\StreamInterface;

/**
 * Stream which represents an uploaded file
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class FileStream implements StreamInterface
{
    use StreamWrapperTrait;

    /**
     * @var string
     */
    protected $tmpName;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var integer
     */
    protected $size;

    /**
     * @var integer
     */
    protected $error;

    /**
     * @param \PSX\Http\StreamInterface $stream
     * @param string $tmpName
     * @param string $name
     * @param string $type
     * @param integer $size
     * @param integer $error
     */
    public function __construct(StreamInterface $stream, $tmpName, $name, $type, $size, $error)
    {
        $this->stream  = $stream;
        $this->tmpName = $tmpName;
        $this->name    = $name;
        $this->type    = $type;
        $this->size    = $size;
        $this->error   = $error;
    }

    /**
     * @return string
     */
    public function getTmpName()
    {
        return $this->tmpName;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return int
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Moves the uploaded file to a new location
     * 
     * @param string $toFile
     * @return boolean
     */
    public function move($toFile)
    {
        if ($this->error == UPLOAD_ERR_OK) {
            return move_uploaded_file($this->tmpName, $toFile);
        } else {
            return false;
        }
    }

    public function detach()
    {
        $this->call();

        $this->tmpName = null;
        $this->name    = null;
        $this->type    = null;
        $this->size    = null;
        $this->error   = null;

        return $this->stream->detach();
    }
}
