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

namespace PSX\Http\Tests\Writer;

use PHPUnit\Framework\TestCase;
use PSX\Http\Response;
use PSX\Http\Writer\Multipart;

/**
 * MultipartTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class MultipartTest extends TestCase
{
    public function testWriteTo()
    {
        $response = new Response(200);

        $body = new Multipart('mixed', 'boundary42');
        $body->addPart(new Response(200, ['Content-Type' => 'text/plain'], 'foo'));
        $body->addPart(new Response(200, ['Content-Type' => 'text/plain'], 'bar'));
        $body->writeTo($response);

        $expect = "--boundary42\r\n";
        $expect.= "content-type: text/plain\r\n";
        $expect.= "\r\n";
        $expect.= "foo";
        $expect.= "\r\n";
        $expect.= "--boundary42\r\n";
        $expect.= "content-type: text/plain\r\n";
        $expect.= "\r\n";
        $expect.= "bar";
        $expect.= "\r\n";
        $expect.= "--boundary42--\r\n";

        $this->assertEquals(['content-type' => ['multipart/mixed; boundary="boundary42"']], $response->getHeaders());
        $this->assertEquals($expect, $response->getBody()->__toString());
    }
}
