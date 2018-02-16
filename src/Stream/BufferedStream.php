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
 * Buffers the complete content of the stream into a string and works from
 * there on with the buffered data
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class BufferedStream implements StreamInterface
{
    use StreamWrapperTrait;

    /**
     * @var \PSX\Http\StreamInterface
     */
    protected $source;

    /**
     * @var boolean
     */
    protected $filled = false;

    public function __construct(StreamInterface $stream)
    {
        $this->source = $stream;
    }

    protected function call()
    {
        if ($this->filled) {
            return;
        }

        $source = $this->source->detach();
        $buffer = fopen('php://temp', 'r+');

        stream_copy_to_stream($source, $buffer, -1, 0);
        rewind($buffer);

        $this->stream = new Stream($buffer);
        $this->filled = true;
    }
}
