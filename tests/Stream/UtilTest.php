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

use PHPUnit\Framework\TestCase;
use PSX\Http\Stream\Stream;
use PSX\Http\Stream\Util;

/**
 * UtilTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class UtilTest extends TestCase
{
    public function testToString()
    {
        $handle = fopen('php://memory', 'r+');
        fwrite($handle, 'foobar');
        fseek($handle, 0);

        $stream = new Stream($handle);

        $this->assertEquals(0, $stream->tell());
        $this->assertEquals('foobar', Util::toString($stream));
        $this->assertEquals(0, $stream->tell());
        $this->assertEquals('foobar', $stream->getContents());
        $this->assertEquals('foobar', (string) $stream);
    }

    public function testToStringSeeked()
    {
        $handle = fopen('php://memory', 'r+');
        fwrite($handle, 'foobar');
        fseek($handle, 4);

        $stream = new Stream($handle);

        $this->assertEquals(4, $stream->tell());
        $this->assertEquals('foobar', Util::toString($stream));
        $this->assertEquals(4, $stream->tell());
        $this->assertEquals('ar', $stream->getContents());
        $this->assertEquals('foobar', (string) $stream);
    }

    public function testToStringNotReadable()
    {
        $handle = fopen(__DIR__ . '/StreamUtilTest.txt', 'w');
        fwrite($handle, 'foobar');

        $stream = new Stream($handle);

        $this->assertFalse($stream->isReadable());
        $this->assertEquals(6, $stream->tell());
        $this->assertEquals('', Util::toString($stream));
        $this->assertEquals(6, $stream->tell());
    }
}
