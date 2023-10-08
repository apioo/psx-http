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

namespace PSX\Http\Tests\Exception;

use PHPUnit\Framework\TestCase;
use PSX\Http\Exception\ClientErrorException;
use PSX\Http\Exception\FoundException;
use PSX\Http\Exception\MethodNotAllowedException;
use PSX\Http\Exception\RedirectionException;
use PSX\Http\Exception\ServerErrorException;
use PSX\Http\Exception\StatusCodeException;
use PSX\Http\Exception\UnauthorizedException;
use PSX\Http\ExceptionThrower;
use PSX\Http\Http;
use PSX\Http\Response;

/**
 * ExceptionThrowerTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class ExceptionThrowerTest extends TestCase
{
    /**
     * @dataProvider redirectionCodeProvider
     */
    public function testThrowOnRedirection($statusCode)
    {
        $this->expectException(RedirectionException::class);

        ExceptionThrower::throwOnRedirection(new Response($statusCode));
    }

    public function redirectionCodeProvider(): array
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
        ExceptionThrower::throwOnRedirection(new Response($statusCode));

        $this->assertTrue($statusCode >= 100 && $statusCode < 600);
    }

    public function noRedirectionCodeProvider(): array
    {
        return $this->getCodes(function($code){
            return !($code >= 300 && $code < 400);
        });
    }

    public function testThrowOnRedirectionFound()
    {
        try {
            ExceptionThrower::throwOnRedirection(new Response(302, ['Location' => 'http://foo.bar']));

            $this->fail('Must throw an exception');
        } catch (FoundException $e) {
            $this->assertEquals('http://foo.bar', $e->getLocation());
        }
    }

    /**
     * @dataProvider clientErrorCodeProvider
     */
    public function testThrowOnClientError($statusCode)
    {
        $this->expectException(ClientErrorException::class);

        ExceptionThrower::throwOnClientError(new Response($statusCode));
    }

    public function clientErrorCodeProvider(): array
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
        ExceptionThrower::throwOnClientError(new Response($statusCode));

        $this->assertTrue($statusCode >= 100 && $statusCode < 600);
    }

    public function noClientErrorCodeProvider(): array
    {
        return $this->getCodes(function($code){
            return !($code >= 400 && $code < 500);
        });
    }

    public function testThrowOnClientErrorUnauthorized()
    {
        try {
            ExceptionThrower::throwOnClientError(new Response(401, ['WWW-Authenticate' => 'Basic realm="foobar"']));

            $this->fail('Must throw an exception');
        } catch (UnauthorizedException $e) {
            $this->assertEquals('Basic', $e->getType());
            $this->assertEquals(['realm' => 'foobar'], $e->getParameters());
        }
    }

    public function testThrowOnClientErrorMethodNotAllowed()
    {
        try {
            ExceptionThrower::throwOnClientError(new Response(405, ['Allow' => 'GET, POST']));

            $this->fail('Must throw an exception');
        } catch (MethodNotAllowedException $e) {
            $this->assertEquals(['GET', 'POST'], $e->getAllowedMethods());
        }
    }

    /**
     * @dataProvider serverErrorCodeProvider
     */
    public function testThrowOnServerError($statusCode)
    {
        $this->expectException(ServerErrorException::class);

        ExceptionThrower::throwOnServerError(new Response($statusCode));
    }

    public function serverErrorCodeProvider(): array
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
        ExceptionThrower::throwOnServerError(new Response($statusCode));

        $this->assertTrue($statusCode >= 100 && $statusCode < 600);
    }

    public function noServerErrorCodeProvider(): array
    {
        return $this->getCodes(function($code){
            return !($code >= 500 && $code < 600);
        });
    }

    /**
     * @dataProvider errorCodeProvider
     */
    public function testThrowOnError($statusCode)
    {
        $this->expectException(StatusCodeException::class);

        ExceptionThrower::throwOnError(new Response($statusCode));
    }

    public function errorCodeProvider(): array
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
        ExceptionThrower::throwOnError(new Response($statusCode));

        $this->assertTrue($statusCode >= 100 && $statusCode < 600);
    }

    public function noErrorCodeProvider(): array
    {
        return $this->getCodes(function($code){
            return !(($code >= 400 && $code < 500) || ($code >= 500 && $code < 600));
        });
    }

    private function getCodes(\Closure $filter): array
    {
        $codes = array_keys(Http::CODES);
        $codes = array_filter($codes, $filter);
        $codes = array_map(function($code){ return [$code]; }, $codes);

        return $codes;
    }
}
