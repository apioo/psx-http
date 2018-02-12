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

namespace PSX\Http\Tests\Client;

use PSX\Http\Client\PostRequest;
use PSX\Uri\Url;

/**
 * PostRequestTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class PostRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $request = new PostRequest(new Url('http://localhost.com/foo'), array('X-Foo' => 'bar'), 'foo');

        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('localhost.com', $request->getHeader('Host'));
        $this->assertEquals('bar', $request->getHeader('X-Foo'));
        $this->assertEquals('foo', (string) $request->getBody());
    }

    public function testConstructUrlHeader()
    {
        $request = new PostRequest(new Url('http://localhost.com/foo'), array('X-Foo' => 'bar'));

        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('localhost.com', $request->getHeader('Host'));
        $this->assertEquals('bar', $request->getHeader('X-Foo'));
    }

    public function testConstructUrl()
    {
        $request = new PostRequest(new Url('http://localhost.com/foo'));

        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('localhost.com', $request->getHeader('Host'));
    }

    public function testConstructUrlString()
    {
        $request = new PostRequest('http://localhost.com/foo');

        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('localhost.com', $request->getHeader('Host'));
    }

    public function testArrayBody()
    {
        $request = new PostRequest(new Url('http://localhost.com/foo'), array(), array('foo' => 'bar'));

        $this->assertEquals('foo=bar', (string) $request->getBody());
    }
}
