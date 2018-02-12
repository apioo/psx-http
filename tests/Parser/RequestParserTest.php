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

namespace PSX\Http\Tests\Parser;

use PSX\Http\Http;
use PSX\Http\Parser\RequestParser;
use PSX\Http\Request;
use PSX\Http\RequestInterface;
use PSX\Uri\Url;

/**
 * RequestParserTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class RequestParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParseStrictMode()
    {
        $request = 'GET /foobar?foo=bar#fragment HTTP/1.1' . Http::NEW_LINE;
        $request.= 'Content-Type: text/plain' . Http::NEW_LINE;
        $request.= 'User-Agent: psx' . Http::NEW_LINE;
        $request.= Http::NEW_LINE;
        $request.= 'Google is built by a large team of engineers, designers, researchers, robots, and others in many different sites across the globe. It is updated continuously, and built with more tools and technologies than we can shake a stick at. If you\'d like to help us out, see google.com/jobs.';

        $parser  = new RequestParser(new Url('http://localhost.com'), RequestParser::MODE_STRICT);
        $request = $parser->parse($request);

        $this->assertInstanceOf(RequestInterface::class, $request);
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('http://localhost.com/foobar?foo=bar#fragment', $request->getUri()->toString());
        $this->assertEquals('HTTP/1.1', $request->getProtocolVersion());
        $this->assertEquals(array(
            'content-type' => ['text/plain'],
            'user-agent'   => ['psx'],
        ), $request->getHeaders());
        $this->assertEquals('Google is built by a large team of engineers, designers, researchers, robots, and others in many different sites across the globe. It is updated continuously, and built with more tools and technologies than we can shake a stick at. If you\'d like to help us out, see google.com/jobs.', $request->getBody());
    }

    public function testParseLooseMode()
    {
        $parser     = new RequestParser(new Url('http://localhost.com'), RequestParser::MODE_LOOSE);
        $seperators = array("\r\n", "\n", "\r");

        foreach ($seperators as $newline) {
            $request = 'GET /foobar?foo=bar#fragment HTTP/1.1' . $newline;
            $request.= 'Content-Type: text/plain' . $newline;
            $request.= 'User-Agent: psx' . $newline;
            $request.= $newline;
            $request.= 'Google is built by a large team of engineers, designers, researchers, robots, and others in many different sites across the globe. It is updated continuously, and built with more tools and technologies than we can shake a stick at. If you\'d like to help us out, see google.com/jobs.';

            $request = $parser->parse($request);

            $this->assertInstanceOf(RequestInterface::class, $request);
            $this->assertEquals('GET', $request->getMethod());
            $this->assertEquals('http://localhost.com/foobar?foo=bar#fragment', $request->getUri()->toString());
            $this->assertEquals('HTTP/1.1', $request->getProtocolVersion());
            $this->assertEquals(array(
                'content-type' => ['text/plain'],
                'user-agent'   => ['psx'],
            ), $request->getHeaders());
            $this->assertEquals('Google is built by a large team of engineers, designers, researchers, robots, and others in many different sites across the globe. It is updated continuously, and built with more tools and technologies than we can shake a stick at. If you\'d like to help us out, see google.com/jobs.', $request->getBody());
        }
    }

    public function testParseNoBaseUrl()
    {
        $request = 'GET /foobar?foo=bar#fragment HTTP/1.1' . Http::NEW_LINE;
        $request.= 'Content-Type: text/plain' . Http::NEW_LINE;
        $request.= 'User-Agent: psx' . Http::NEW_LINE;
        $request.= Http::NEW_LINE;
        $request.= 'Google is built by a large team of engineers, designers, researchers, robots, and others in many different sites across the globe. It is updated continuously, and built with more tools and technologies than we can shake a stick at. If you\'d like to help us out, see google.com/jobs.';

        $parser  = new RequestParser();
        $request = $parser->parse($request);

        $this->assertInstanceOf(RequestInterface::class, $request);
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/foobar?foo=bar#fragment', $request->getUri()->toString());
        $this->assertEquals('HTTP/1.1', $request->getProtocolVersion());
        $this->assertEquals(array(
            'content-type' => ['text/plain'],
            'user-agent'   => ['psx'],
        ), $request->getHeaders());
        $this->assertEquals('Google is built by a large team of engineers, designers, researchers, robots, and others in many different sites across the globe. It is updated continuously, and built with more tools and technologies than we can shake a stick at. If you\'d like to help us out, see google.com/jobs.', $request->getBody());
    }

    /**
     * @expectedException \PSX\Http\Parser\ParseException
     */
    public function testParseInvalidStatusLine()
    {
        $request = 'foobar' . Http::NEW_LINE;
        $request.= 'Vary: Accept-Encoding' . Http::NEW_LINE;

        $parser = new RequestParser(new Url('http://localhost.com'), RequestParser::MODE_STRICT);
        $parser->parse($request);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testParseEmpty()
    {
        $request = '';

        $parser = new RequestParser(new Url('http://localhost.com'), RequestParser::MODE_STRICT);
        $parser->parse($request);
    }

    /**
     * @expectedException \PSX\Http\Parser\ParseException
     */
    public function testParseNoLineEnding()
    {
        $request = 'GET /foobar?foo=bar#fragment HTTP/1.1';
        $request.= 'Vary: Accept-Encoding';

        $parser = new RequestParser(new Url('http://localhost.com'), RequestParser::MODE_STRICT);
        $parser->parse($request);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testParseInvalidMode()
    {
        $request = 'GET /foobar?foo=bar#fragment HTTP/1.1' . Http::NEW_LINE;
        $request.= 'Content-Type: text/plain' . Http::NEW_LINE;

        $parser = new RequestParser(new Url('http://localhost.com'), 'foo');
        $parser->parse($request);
    }

    public function testBuildStatusLine()
    {
        $request = new Request(new Url('http://127.0.0.1'), 'GET');

        $this->assertEquals('GET / HTTP/1.1', RequestParser::buildStatusLine($request));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testBuildStatusLineNoTarget()
    {
        $request = new Request(new Url('http://127.0.0.1'), 'GET');
        $request->setRequestTarget('');

        RequestParser::buildStatusLine($request);
    }

    public function testConvert()
    {
        $httpRequest = 'GET /foo/bar?foo=bar#test HTTP/1.1' . Http::NEW_LINE;
        $httpRequest.= 'Content-type: text/html; charset=UTF-8' . Http::NEW_LINE;
        $httpRequest.= Http::NEW_LINE;
        $httpRequest.= 'foobar';

        $request = RequestParser::convert($httpRequest, new Url('http://psx.dev'));

        $this->assertEquals('http://psx.dev/foo/bar?foo=bar#test', $request->getUri()->toString());
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('HTTP/1.1', $request->getProtocolVersion());
        $this->assertEquals('text/html; charset=UTF-8', (string) $request->getHeader('Content-Type'));
        $this->assertEquals('foobar', $request->getBody());
    }
}
