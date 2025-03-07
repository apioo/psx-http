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

namespace PSX\Http\Tests\Server;

use PSX\Http\Response;
use PSX\Http\Server\Sender;
use PSX\Http\Stream\Stream;
use PSX\Http\Stream\StringStream;

/**
 * SenderTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class SenderTest extends SenderTestCase
{
    public function testSend()
    {
        $response = new Response();
        $response->setBody(new StringStream('foobar'));

        $sender = new Sender();
        $actual = $this->captureOutput($sender, $response);

        $this->assertEquals('foobar', $actual);
    }

    public function testSendHeaders()
    {
        $response = new Response();
        $response->setHeader('Content-Type', 'application/xml');
        $response->setHeader('X-Some-Header', 'foobar');
        $response->setBody(new StringStream('<foo />'));

        $sender = new Sender();

        $actual = $this->captureOutput($sender, $response);

        $this->assertEquals('<foo />', $actual);
    }

    /**
     * If we have an location header we only send the location header and no
     * other content
     */
    public function testSendHeaderLocation()
    {
        $response = new Response();
        $response->setHeader('Content-Type', 'application/xml');
        $response->setHeader('Location', 'http://localhost.com');
        $response->setBody(new StringStream('<foo />'));

        $sender = new Sender();

        $actual = $this->captureOutput($sender, $response);

        $this->assertEquals('', $actual);
    }

    public function testSendBody()
    {
        $response = new Response();
        $response->setBody(new StringStream('foobarfoobarfoobarfoobar'));

        $sender = new Sender();

        $actual = $this->captureOutput($sender, $response);

        $this->assertEquals('foobarfoobarfoobarfoobar', $actual);
    }

    public function testSendBodyCopy()
    {
        $fp = fopen('php://temp', 'r+');
        fwrite($fp, 'foobarfoobarfoobarfoobar');

        $response = new Response();
        $response->setBody(new Stream($fp));

        $sender = new Sender();

        $actual = $this->captureOutput($sender, $response);

        $this->assertEquals('foobarfoobarfoobarfoobar', $actual);
    }

    public function testEmpyBodyStatusCode()
    {
        $emptyCodes = [100, 101, 204, 304];

        foreach ($emptyCodes as $statusCode) {
            $response = new Response($statusCode);
            $response->setBody(new StringStream('foobar'));

            $sender = new Sender();

            $actual = $this->captureOutput($sender, $response);

            $this->assertEmpty($actual);
        }
    }

    public function testSendStatusCode()
    {
        $response = new Response(404);
        $response->setBody(new StringStream('foobar'));

        $sender = new Sender();

        $actual = $this->captureOutput($sender, $response);

        $this->assertEquals('foobar', $actual);
    }
}
