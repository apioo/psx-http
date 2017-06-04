<?php
/*
 * PSX is a open source PHP framework to develop RESTful APIs.
 * For the current version and informations visit <http://phpsx.org>
 *
 * Copyright 2010-2017 Christoph Kappestein <christoph.kappestein@gmail.com>
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
use PSX\Http\MultipartResponse;
use PSX\Http\Response;
use PSX\Http\Stream\StringStream;

/**
 * MultipartResponseTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class MultipartResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $response = new MultipartResponse(200, [], 'mixed', '92135d41');

        $subResponse = new Response();
        $subResponse->setHeader('Content-Type', 'text/plain');
        $subResponse->setBody(new StringStream('foobar'));
        $response->addPart($subResponse);

        $subResponse = new Response();
        $subResponse->setHeader('Content-Type', 'text/plain');
        $subResponse->setBody(new StringStream('foobar'));
        $response->addPart($subResponse);

        $httpResponse = 'HTTP/1.1 200 OK' . Http::NEW_LINE;
        $httpResponse.= 'content-type: multipart/mixed; boundary="92135d41"' . Http::NEW_LINE;
        $httpResponse.= Http::NEW_LINE;
        $httpResponse.= '--92135d41' . Http::NEW_LINE;
        $httpResponse.= 'content-type: text/plain' . Http::NEW_LINE;
        $httpResponse.= Http::NEW_LINE;
        $httpResponse.= 'foobar';
        $httpResponse.= Http::NEW_LINE;
        $httpResponse.= '--92135d41' . Http::NEW_LINE;
        $httpResponse.= 'content-type: text/plain' . Http::NEW_LINE;
        $httpResponse.= Http::NEW_LINE;
        $httpResponse.= 'foobar';
        $httpResponse.= Http::NEW_LINE;
        $httpResponse.= '--92135d41--' . Http::NEW_LINE;

        $this->assertEquals($httpResponse, (string) $response);
    }
}
