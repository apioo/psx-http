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

namespace PSX\Http\Tests\Filter\Condition;

use PSX\Http\Filter\Condition\PathMatch;
use PSX\Http\Filter\FilterChain;
use PSX\Http\FilterInterface;
use PSX\Http\Request;
use PSX\Http\Response;
use PSX\Http\Stream\StringStream;
use PSX\Uri\Url;

/**
 * PathMatchTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class PathMatchTest extends \PHPUnit_Framework_TestCase
{
    public function testPath()
    {
        $request  = new Request(new Url('http://localhost/foobar'), 'GET');
        $response = new Response();
        $response->setBody(new StringStream());

        $filter = $this->getMockBuilder(FilterInterface::class)
            ->setMethods(array('handle'))
            ->getMock();

        $filter->expects($this->once())
            ->method('handle')
            ->with($this->equalTo($request), $this->equalTo($response));

        $filterChain = $this->getMockBuilder(FilterChain::class)
            ->setConstructorArgs(array(array()))
            ->setMethods(array('handle'))
            ->getMock();

        $filterChain->expects($this->never())
            ->method('handle');

        $handle = new PathMatch('/foo+', $filter);
        $handle->handle($request, $response, $filterChain);
    }

    public function testWrongPath()
    {
        $request  = new Request(new Url('http://localhost/baz'), 'GET');
        $response = new Response();
        $response->setBody(new StringStream());

        $filter = $this->getMockBuilder(FilterInterface::class)
            ->setMethods(array('handle'))
            ->getMock();

        $filter->expects($this->never())
            ->method('handle')
            ->with($this->equalTo($request), $this->equalTo($response));

        $filterChain = $this->getMockBuilder(FilterChain::class)
            ->setConstructorArgs(array(array()))
            ->setMethods(array('handle'))
            ->getMock();

        $filterChain->expects($this->once())
            ->method('handle');

        $handle = new PathMatch('/foo+', $filter);
        $handle->handle($request, $response, $filterChain);
    }
}
