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

namespace PSX\Http\Tests\Environment;

use PHPUnit\Framework\TestCase;
use PSX\Http\Environment\HttpContext;
use PSX\Http\Environment\HttpContextInterface;
use PSX\Http\Request;
use PSX\Uri\Uri;

/**
 * HttpContextTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class HttpContextTest extends TestCase
{
    public function testContext()
    {
        $request = new Request(Uri::parse('/foo?baz=foo'), 'GET', ['X-Foo' => 'bar']);
        $context = new HttpContext($request, ['bar' => 'foo']);

        $this->assertInstanceOf(HttpContextInterface::class, $context);
        $this->assertEquals('GET', $context->getMethod());
        $this->assertEquals('bar', $context->getHeader('X-Foo'));
        $this->assertEquals(['x-foo' => ['bar']], $context->getHeaders());
        $this->assertEquals('foo', $context->getParameter('baz'));
        $this->assertEquals(['baz' => 'foo'], $context->getParameters());
        $this->assertEquals('foo', $context->getUriFragment('bar'));
        $this->assertEquals(['bar' => 'foo'], $context->getUriFragments());
    }
}
