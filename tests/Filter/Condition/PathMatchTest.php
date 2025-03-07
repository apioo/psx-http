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

namespace PSX\Http\Tests\Filter\Condition;

use PSX\Http\Filter\Condition\PathMatch;
use PSX\Http\Filter\FilterChain;
use PSX\Http\FilterInterface;
use PSX\Http\Request;
use PSX\Http\Response;
use PSX\Http\Stream\StringStream;
use PSX\Http\Tests\Filter\FilterTestCase;
use PSX\Uri\Url;

/**
 * PathMatchTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class PathMatchTest extends FilterTestCase
{
    public function testPath()
    {
        $request  = new Request(Url::parse('http://localhost/foobar'), 'GET');
        $response = new Response();
        $response->setBody(new StringStream());

        $filter = $this->getMockBuilder(FilterInterface::class)
            ->getMock();

        $filter->expects($this->once())
            ->method('handle')
            ->with($this->equalTo($request), $this->equalTo($response));

        $handle = new PathMatch('/foo+', $filter);
        $handle->handle($request, $response, $this->getFilterChain(false));
    }

    public function testWrongPath()
    {
        $request  = new Request(Url::parse('http://localhost/baz'), 'GET');
        $response = new Response();
        $response->setBody(new StringStream());

        $filter = $this->getMockBuilder(FilterInterface::class)
            ->getMock();

        $filter->expects($this->never())
            ->method('handle')
            ->with($this->equalTo($request), $this->equalTo($response));

        $handle = new PathMatch('/foo+', $filter);
        $handle->handle($request, $response, $this->getFilterChain(true, $request, $response));
    }
}
