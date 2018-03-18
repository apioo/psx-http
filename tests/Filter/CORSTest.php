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

namespace PSX\Http\Tests\Filter;

use PSX\Http\Filter\CORS;
use PSX\Http\Filter\FilterChain;
use PSX\Http\Request;
use PSX\Http\Response;
use PSX\Uri\Url;

/**
 * CORSTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class CORSTest extends FilterTestCase
{
    /**
     * @dataProvider corsProvider
     */
    public function testHandle($allowOrigin, array $allowMethods, array $allowHeaders, $allowCredentials, $method, array $headers, array $expectHeaders)
    {
        $request  = new Request(new Url('http://localhost'), $method, $headers);
        $response = new Response();

        $handle = new CORS($allowOrigin, $allowMethods, $allowHeaders, $allowCredentials);
        $handle->handle($request, $response, $this->getFilterChain(true, $request, $response));

        $this->assertEquals($expectHeaders, $response->getHeaders());
    }

    public function corsProvider()
    {
        $originTrue = function($origin) {
            return true;
        };

        $originFalse = function($origin) {
            return false;
        };

        return [
            // no origin
            // no credentials
            [null, ['GET', 'POST'], ['Content-Type'], false, 'GET', [
            ], [
            ]],
            ['*', ['GET', 'POST'], ['Content-Type'], false, 'GET', [
            ], [
            ]],
            [$originTrue, ['GET', 'POST'], ['Content-Type'], false, 'GET', [
            ], [
            ]],
            [$originFalse, ['GET', 'POST'], ['Content-Type'], false, 'GET', [
            ], [
            ]],

            // with credentials
            [null, ['GET', 'POST'], ['Content-Type'], true, 'GET', [
            ], [
            ]],
            ['*', ['GET', 'POST'], ['Content-Type'], true, 'GET', [
            ], [
            ]],
            [$originTrue, ['GET', 'POST'], ['Content-Type'], true, 'GET', [
            ], [
            ]],
            [$originFalse, ['GET', 'POST'], ['Content-Type'], true, 'GET', [
            ], [
            ]],

            // simple requests
            // no credentials
            [null, ['GET', 'POST'], ['Content-Type'], false, 'GET', [
                'Origin' => 'http://foo.example'
            ], [
            ]],
            ['*', ['GET', 'POST'], ['Content-Type'], false, 'GET', [
                'Origin' => 'http://foo.example'
            ], [
                'access-control-allow-origin' => ['*']
            ]],
            [$originTrue, ['GET', 'POST'], ['Content-Type'], false, 'GET', [
                'Origin' => 'http://foo.example'
            ], [
                'access-control-allow-origin' => ['http://foo.example'],
                'vary' => ['Origin']
            ]],
            [$originFalse, ['GET', 'POST'], ['Content-Type'], false, 'GET', [
                'Origin' => 'http://foo.example'
            ], [
            ]],

            // with crendetials
            [null, ['GET', 'POST'], ['Content-Type'], true, 'GET', [
                'Origin' => 'http://foo.example'
            ], [
            ]],
            ['*', ['GET', 'POST'], ['Content-Type'], true, 'GET', [
                'Origin' => 'http://foo.example'
            ], [
                'access-control-allow-origin' => ['*'],
                'access-control-allow-credentials' => ['true'],
            ]],
            [$originTrue, ['GET', 'POST'], ['Content-Type'], true, 'GET', [
                'Origin' => 'http://foo.example'
            ], [
                'access-control-allow-origin' => ['http://foo.example'],
                'vary' => ['Origin'],
                'access-control-allow-credentials' => ['true'],
            ]],
            [$originFalse, ['GET', 'POST'], ['Content-Type'], true, 'GET', [
                'Origin' => 'http://foo.example'
            ], [
            ]],

            // prefligh requests
            // no credentials
            [null, ['GET', 'POST'], ['Content-Type'], false, 'OPTIONS', [
                'Origin' => 'http://foo.example',
                'Access-Control-Request-Method' => 'POST',
                'Access-Control-Request-Headers' => 'X-PINGOTHER, Content-Type'
            ],[
            ]],
            ['*', ['GET', 'POST'], ['Content-Type'], false, 'OPTIONS', [
                'Origin' => 'http://foo.example',
                'Access-Control-Request-Method' => 'POST',
                'Access-Control-Request-Headers' => 'X-PINGOTHER, Content-Type'
            ],[
                'access-control-allow-origin' => ['*'],
                'access-control-allow-methods' => ['GET, POST'],
                'access-control-allow-headers' => ['Content-Type']
            ]],
            [$originTrue, ['GET', 'POST'], ['Content-Type'], false, 'OPTIONS', [
                'Origin' => 'http://foo.example',
                'Access-Control-Request-Method' => 'POST',
                'Access-Control-Request-Headers' => 'X-PINGOTHER, Content-Type'
            ],[
                'access-control-allow-origin' => ['http://foo.example'],
                'access-control-allow-methods' => ['GET, POST'],
                'access-control-allow-headers' => ['Content-Type'],
                'vary' => ['Origin']
            ]],
            [$originFalse, ['GET', 'POST'], ['Content-Type'], false, 'OPTIONS', [
                'Origin' => 'http://foo.example',
                'Access-Control-Request-Method' => 'POST',
                'Access-Control-Request-Headers' => 'X-PINGOTHER, Content-Type'
            ],[
            ]],

            // with credentials
            [null, ['GET', 'POST'], ['Content-Type'], true, 'OPTIONS', [
                'Origin' => 'http://foo.example',
                'Access-Control-Request-Method' => 'POST',
                'Access-Control-Request-Headers' => 'X-PINGOTHER, Content-Type'
            ],[
            ]],
            ['*', ['GET', 'POST'], ['Content-Type'], true, 'OPTIONS', [
                'Origin' => 'http://foo.example',
                'Access-Control-Request-Method' => 'POST',
                'Access-Control-Request-Headers' => 'X-PINGOTHER, Content-Type'
            ],[
                'access-control-allow-origin' => ['*'],
                'access-control-allow-methods' => ['GET, POST'],
                'access-control-allow-headers' => ['Content-Type'],
                'access-control-allow-credentials' => ['true'],
            ]],
            [$originTrue, ['GET', 'POST'], ['Content-Type'], true, 'OPTIONS', [
                'Origin' => 'http://foo.example',
                'Access-Control-Request-Method' => 'POST',
                'Access-Control-Request-Headers' => 'X-PINGOTHER, Content-Type'
            ],[
                'access-control-allow-origin' => ['http://foo.example'],
                'access-control-allow-methods' => ['GET, POST'],
                'access-control-allow-headers' => ['Content-Type'],
                'vary' => ['Origin'],
                'access-control-allow-credentials' => ['true'],
            ]],
            [$originFalse, ['GET', 'POST'], ['Content-Type'], true, 'OPTIONS', [
                'Origin' => 'http://foo.example',
                'Access-Control-Request-Method' => 'POST',
                'Access-Control-Request-Headers' => 'X-PINGOTHER, Content-Type'
            ],[
            ]],
        ];
    }

    public function testAllowOrigin()
    {
        $request  = new Request(new Url('http://localhost'), 'GET', ['Origin' => 'http://foo.example']);
        $response = new Response();

        $handle = CORS::allowOrigin('*');
        $handle->handle($request, $response, $this->getFilterChain(true, $request, $response));

        $this->assertTrue($response->hasHeader('Access-Control-Allow-Origin'));
        $this->assertEquals('*', $response->getHeader('Access-Control-Allow-Origin'));
    }
}
