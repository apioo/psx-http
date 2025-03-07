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

namespace PSX\Http\Tests\Filter;

use PSX\Http\Exception\BadRequestException;
use PSX\Http\Exception\UnauthorizedException;
use PSX\Http\Filter\BasicAuthentication;
use PSX\Http\Filter\FilterChain;
use PSX\Http\Request;
use PSX\Http\Response;
use PSX\Uri\Url;

/**
 * BasicAuthenticationTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class BasicAuthenticationTest extends FilterTestCase
{
    public function testSuccessful()
    {
        $handle = new BasicAuthentication(function ($username, $password) {
            return $username == 'test' && $password == 'test';
        });

        $handle->onSuccess(function () {
            // success
        });

        $username = 'test';
        $password = 'test';

        $request  = new Request(Url::parse('http://localhost'), 'GET', array('Authorization' => 'Basic ' . base64_encode($username . ':' . $password)));
        $response = new Response();

        $handle->handle($request, $response, $this->getFilterChain(true, $request, $response));
    }

    public function testFailure()
    {
        $this->expectException(BadRequestException::class);

        $handle = new BasicAuthentication(function ($username, $password) {
            return $username == 'test' && $password == 'test';
        });

        $username = 'foo';
        $password = 'bar';

        $request  = new Request(Url::parse('http://localhost'), 'GET', array('Authorization' => 'Basic ' . base64_encode($username . ':' . $password)));
        $response = new Response();

        $handle->handle($request, $response, $this->getFilterChain(false));
    }

    public function testMissing()
    {
        $handle = new BasicAuthentication(function ($username, $password) {
            return $username == 'test' && $password == 'test';
        });

        $request  = new Request(Url::parse('http://localhost'), 'GET');
        $response = new Response();

        try {
            $handle->handle($request, $response, $this->getFilterChain(false));

            $this->fail('Must throw an Exception');
        } catch (UnauthorizedException $e) {
            $this->assertEquals(401, $e->getStatusCode());
            $this->assertEquals('Basic', $e->getType());
            $this->assertEquals(array('realm' => 'psx'), $e->getParameters());
        }
    }

    public function testMissingWrongType()
    {
        $handle = new BasicAuthentication(function ($username, $password) {
            return $username == 'test' && $password == 'test';
        });

        $request  = new Request(Url::parse('http://localhost'), 'GET', array('Authorization' => 'Foo'));
        $response = new Response();

        try {
            $handle->handle($request, $response, $this->getFilterChain(false));

            $this->fail('Must throw an Exception');
        } catch (UnauthorizedException $e) {
            $this->assertEquals(401, $e->getStatusCode());
            $this->assertEquals('Basic', $e->getType());
            $this->assertEquals(array('realm' => 'psx'), $e->getParameters());
        }
    }
}
