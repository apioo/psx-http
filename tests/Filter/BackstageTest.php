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

use PSX\Http\Filter\Backstage;
use PSX\Http\Filter\FilterChain;
use PSX\Http\Request;
use PSX\Http\Response;
use PSX\Http\Stream\StringStream;
use PSX\Uri\Url;

/**
 * BackstageTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class BackstageTest extends FilterTestCase
{
    public function testFileExists()
    {
        $request  = new Request(Url::parse('http://localhost'), 'GET', array(
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8'
        ));
        $response = new Response();
        $response->setBody(new StringStream());

        $handle = new Backstage(__DIR__ . '/backstage.htm');
        $handle->handle($request, $response, $this->getFilterChain(false));

        $this->assertEquals('foobar', (string) $response->getBody());
    }

    public function testNoFittingAcceptHeader()
    {
        $request  = new Request(Url::parse('http://localhost'), 'GET', array(
            'Accept' => 'application/json'
        ));
        $response = new Response();

        $handle = new Backstage(__DIR__ . '/backstage.htm');
        $handle->handle($request, $response, $this->getFilterChain(true, $request, $response));
    }

    public function testFileNotExists()
    {
        $request  = new Request(Url::parse('http://localhost'), 'GET', array(
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8'
        ));
        $response = new Response();

        $handle = new Backstage(__DIR__ . '/foo.htm');
        $handle->handle($request, $response, $this->getFilterChain(true, $request, $response));
    }

    public function testNoFittingAcceptHeaderAndFileNotExists()
    {
        $request  = new Request(Url::parse('http://localhost'), 'GET', array(
            'Accept' => 'application/json'
        ));
        $response = new Response();

        $handle = new Backstage(__DIR__ . '/foo.htm');
        $handle->handle($request, $response, $this->getFilterChain(true, $request, $response));
    }
}
