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

namespace PSX\Http\Tests\Exception;

use PSX\Http\Exception\FoundException;
use PSX\Http\Exception\MethodNotAllowedException;
use PSX\Http\Exception\StatusCodeException;
use PSX\Http\Exception\UnauthorizedException;
use PSX\Http\Http;
use PSX\Http\Response;

/**
 * StatusCodeExceptionTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class StatusCodeExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidStatusCode()
    {
        new StatusCodeException('foo', 108);
    }

    public function testGetStatusCode()
    {
        $e = new StatusCodeException('foo', 101);

        $this->assertEquals(101, $e->getStatusCode());
    }

    public function testIsInformational()
    {
        $e = new StatusCodeException('foo', 100);

        $this->assertTrue($e->isInformational());
        $this->assertFalse($e->isSuccessful());
        $this->assertFalse($e->isRedirection());
        $this->assertFalse($e->isClientError());
        $this->assertFalse($e->isServerError());
    }

    public function testIsSuccessful()
    {
        $e = new StatusCodeException('foo', 200);

        $this->assertFalse($e->isInformational());
        $this->assertTrue($e->isSuccessful());
        $this->assertFalse($e->isRedirection());
        $this->assertFalse($e->isClientError());
        $this->assertFalse($e->isServerError());
    }

    public function testIsRedirection()
    {
        $e = new StatusCodeException('foo', 300);

        $this->assertFalse($e->isInformational());
        $this->assertFalse($e->isSuccessful());
        $this->assertTrue($e->isRedirection());
        $this->assertFalse($e->isClientError());
        $this->assertFalse($e->isServerError());
    }

    public function testIsClientError()
    {
        $e = new StatusCodeException('foo', 400);

        $this->assertFalse($e->isInformational());
        $this->assertFalse($e->isSuccessful());
        $this->assertFalse($e->isRedirection());
        $this->assertTrue($e->isClientError());
        $this->assertFalse($e->isServerError());
    }

    public function testIsServerError()
    {
        $e = new StatusCodeException('foo', 500);

        $this->assertFalse($e->isInformational());
        $this->assertFalse($e->isSuccessful());
        $this->assertFalse($e->isRedirection());
        $this->assertFalse($e->isClientError());
        $this->assertTrue($e->isServerError());
    }

    /**
     * @dataProvider redirectionCodeProvider
     * @expectedException \PSX\Http\Exception\RedirectionException
     */
    public function testThrowOnRedirection($statusCode)
    {
        StatusCodeException::throwOnRedirection(new Response($statusCode));
    }

    public function redirectionCodeProvider()
    {
        return $this->getCodes(function($code){
            return $code >= 300 && $code < 400;
        });
    }

    /**
     * @dataProvider noRedirectionCodeProvider
     */
    public function testThrowOnRedirectionNoRedirection($statusCode)
    {
        StatusCodeException::throwOnRedirection(new Response($statusCode));

        $this->assertTrue($statusCode >= 100 && $statusCode < 600);
    }

    public function noRedirectionCodeProvider()
    {
        return $this->getCodes(function($code){
            return !($code >= 300 && $code < 400);
        });
    }

    public function testThrowOnRedirectionFound()
    {
        try {
            StatusCodeException::throwOnRedirection(new Response(302, ['Location' => 'http://foo.bar']));

            $this->fail('Must throw an exception');
        } catch (FoundException $e) {
            $this->assertEquals('http://foo.bar', $e->getLocation());
        }
    }

    /**
     * @dataProvider clientErrorCodeProvider
     * @expectedException \PSX\Http\Exception\ClientErrorException
     */
    public function testThrowOnClientError($statusCode)
    {
        StatusCodeException::throwOnClientError(new Response($statusCode));
    }

    public function clientErrorCodeProvider()
    {
        return $this->getCodes(function($code){
            return $code >= 400 && $code < 500;
        });
    }

    /**
     * @dataProvider noClientErrorCodeProvider
     */
    public function testThrowOnClientErrorNoClientError($statusCode)
    {
        StatusCodeException::throwOnClientError(new Response($statusCode));

        $this->assertTrue($statusCode >= 100 && $statusCode < 600);
    }

    public function noClientErrorCodeProvider()
    {
        return $this->getCodes(function($code){
            return !($code >= 400 && $code < 500);
        });
    }

    public function testThrowOnClientErrorUnauthorized()
    {
        try {
            StatusCodeException::throwOnClientError(new Response(401, ['WWW-Authenticate' => 'Basic realm="foobar"']));

            $this->fail('Must throw an exception');
        } catch (UnauthorizedException $e) {
            $this->assertEquals('Basic', $e->getType());
            $this->assertEquals(['realm' => 'foobar'], $e->getParameters());
        }
    }

    public function testThrowOnClientErrorMethodNotAllowed()
    {
        try {
            StatusCodeException::throwOnClientError(new Response(405, ['Allow' => 'GET, POST']));

            $this->fail('Must throw an exception');
        } catch (MethodNotAllowedException $e) {
            $this->assertEquals(['GET', 'POST'], $e->getAllowedMethods());
        }
    }

    /**
     * @dataProvider serverErrorCodeProvider
     * @expectedException \PSX\Http\Exception\ServerErrorException
     */
    public function testThrowOnServerError($statusCode)
    {
        StatusCodeException::throwOnServerError(new Response($statusCode));
    }

    public function serverErrorCodeProvider()
    {
        return $this->getCodes(function($code){
            return $code >= 500 && $code < 600;
        });
    }

    /**
     * @dataProvider noServerErrorCodeProvider
     */
    public function testThrowOnServerErrorNoServerError($statusCode)
    {
        StatusCodeException::throwOnServerError(new Response($statusCode));

        $this->assertTrue($statusCode >= 100 && $statusCode < 600);
    }

    public function noServerErrorCodeProvider()
    {
        return $this->getCodes(function($code){
            return !($code >= 500 && $code < 600);
        });
    }

    /**
     * @dataProvider errorCodeProvider
     * @expectedException \PSX\Http\Exception\StatusCodeException
     */
    public function testThrowOnError($statusCode)
    {
        StatusCodeException::throwOnError(new Response($statusCode));
    }

    public function errorCodeProvider()
    {
        return $this->getCodes(function($code){
            return $code >= 400 && $code < 600;
        });
    }

    /**
     * @dataProvider noErrorCodeProvider
     */
    public function testThrowOnErrorNoError($statusCode)
    {
        StatusCodeException::throwOnError(new Response($statusCode));

        $this->assertTrue($statusCode >= 100 && $statusCode < 600);
    }

    public function noErrorCodeProvider()
    {
        return $this->getCodes(function($code){
            return !(($code >= 400 && $code < 500) || ($code >= 500 && $code < 600));
        });
    }

    private function getCodes(\Closure $filter)
    {
        $codes = array_keys(Http::$codes);
        $codes = array_filter($codes, $filter);
        $codes = array_map(function($code){ return [$code]; }, $codes);

        return $codes;
    }
}
