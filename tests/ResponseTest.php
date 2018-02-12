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

namespace PSX\Http\Tests;

use PSX\Http\Http;
use PSX\Http\Response;
use PSX\Http\Stream\StringStream;

/**
 * ResponseTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $body = new StringStream();
        $body->write('foobar');

        $response = new Response(200);
        $response->setHeader('Content-Type', 'text/html; charset=UTF-8');
        $response->setBody($body);

        $httpResponse = 'HTTP/1.1 200 OK' . Http::NEW_LINE;
        $httpResponse.= 'content-type: text/html; charset=UTF-8' . Http::NEW_LINE;
        $httpResponse.= Http::NEW_LINE;
        $httpResponse.= 'foobar';

        $this->assertEquals($httpResponse, (string) $response);
    }
}
