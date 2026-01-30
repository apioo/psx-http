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

namespace PSX\Http\Tests;

use Nyholm\Psr7\Response as PsrResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use PSX\Http\PsrFactory;
use PSX\Http\Request;
use PSX\Http\ResponseInterface;
use PSX\Http\Stream\StringStream;
use PSX\Uri\Uri;

/**
 * PsrFactoryTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class PsrFactoryTest extends TestCase
{
    public function testToPsrRequest()
    {
        $request = new Request(Uri::parse('https://phpsx.org/foo?foo=bar'), 'POST', ['X-Custom' => 'test'], new StringStream('foobar'));
        $psrRequest = (new PsrFactory())->toPsrRequest($request);

        $this->assertInstanceOf(RequestInterface::class, $psrRequest);
        $this->assertEquals('POST', $psrRequest->getMethod());
        $this->assertEquals('https://phpsx.org/foo?foo=bar', (string) $psrRequest->getUri());
        $this->assertEquals('test', $psrRequest->getHeaderLine('X-Custom'));
        $this->assertEquals('foobar', (string) $psrRequest->getBody());
    }

    public function testToPsrServerRequest()
    {
        $request = new Request(Uri::parse('https://phpsx.org/foo?foo=bar'), 'POST', ['X-Custom' => 'test'], new StringStream('foobar'));
        $psrRequest = (new PsrFactory())->toPsrServerRequest($request);

        $this->assertInstanceOf(ServerRequestInterface::class, $psrRequest);
        $this->assertEquals('POST', $psrRequest->getMethod());
        $this->assertEquals('https://phpsx.org/foo?foo=bar', (string) $psrRequest->getUri());
        $this->assertEquals('test', $psrRequest->getHeaderLine('X-Custom'));
        $this->assertEquals('foobar', (string) $psrRequest->getBody());
    }

    public function testFromPsrResponse()
    {
        $psrResponse = new PsrResponse(200, ['X-Custom' => 'test'], new StringStream('foobar'));
        $response = (new PsrFactory())->fromPsrResponse($psrResponse);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('test', $response->getHeader('X-Custom'));
        $this->assertEquals('foobar', (string) $response->getBody());
    }
}
