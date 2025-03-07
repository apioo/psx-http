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

use PSX\Http\StreamInterface;

/**
 * Stream which represents an uploaded file
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class FileStream implements StreamInterface
{
    use StreamWrapperTrait;

    private string $tmpName;
    private string $name;
    private string $type;
    private int $size;
    private int $error;

    public function __construct(StreamInterface $stream, string $tmpName, string $name, string $type, int $size, int $error)
    {
        $this->stream  = $stream;
        $this->tmpName = $tmpName;
        $this->name    = $name;
        $this->type    = $type;
        $this->size    = $size;
        $this->error   = $error;
    }

    public function getTmpName(): string
    {
        return $this->tmpName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getError(): int
    {
        return $this->error;
    }

    /**
     * Moves the uploaded file to a new location
     * 
     */
    public function move(string $toFile): bool
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

        $this->tmpName = '';
        $this->name    = '';
        $this->type    = '';
        $this->size    = 0;
        $this->error   = 0;

        return $this->stream->detach();
    }
}
