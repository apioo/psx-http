<?php
/*
 * PSX is an open source PHP framework to develop RESTful APIs.
 * For the current version and information visit <https://phpsx.org>
 *
 * Copyright 2010-2023 Christoph Kappestein <christoph.kappestein@gmail.com>
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

use PHPUnit\Framework\TestCase;
use PSX\Http\Client\HeadRequest;
use PSX\Uri\Url;

/**
 * HeadRequestTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class HeadRequestTest extends TestCase
{
    public function testConstruct()
    {
        $request = new HeadRequest(Url::parse('http://localhost.com/foo'), array('X-Foo' => 'bar'));

        $this->assertEquals('HEAD', $request->getMethod());
        $this->assertEquals('localhost.com', $request->getHeader('Host'));
        $this->assertEquals('bar', $request->getHeader('X-Foo'));
    }

    public function testConstructUrl()
    {
        $request = new HeadRequest(Url::parse('http://localhost.com/foo'));

        $this->assertEquals('HEAD', $request->getMethod());
        $this->assertEquals('localhost.com', $request->getHeader('Host'));
    }

    public function testConstructUrlString()
    {
        $request = new HeadRequest('http://localhost.com/foo');

        $this->assertEquals('HEAD', $request->getMethod());
        $this->assertEquals('localhost.com', $request->getHeader('Host'));
    }
}
