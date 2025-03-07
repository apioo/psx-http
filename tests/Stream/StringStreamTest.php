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

namespace PSX\Http\Tests\Stream;

use PSX\Http\Stream\StringStream;

/**
 * StringStreamTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class StringStreamTest extends StreamTestCase
{
    protected function getStream()
    {
        return new StringStream('foobar');
    }

    public function testStreamMultiByte()
    {
        $stream = new StringStream('foobär');

        $this->assertEquals(6, $stream->getSize());

        $stream->rewind();
        $chars = '';
        while (!$stream->eof()) {
            $chars.= $stream->read(1);
        }

        $this->assertEquals('foobär', $chars);
    }
}
