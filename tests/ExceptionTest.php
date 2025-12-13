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

use PHPUnit\Framework\TestCase;
use PSX\Http\Exception;
use ReflectionClass;

/**
 * ExceptionTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class ExceptionTest extends TestCase
{
    public function testBadRequestException()
    {
        $this->assertRedirection(301, Exception\MovedPermanentlyException::class, array('http://google.com'));
        $this->assertRedirection(302, Exception\FoundException::class, array('http://google.com'));
        $this->assertRedirection(303, Exception\SeeOtherException::class, array('http://google.com'));
        $this->assertRedirection(304, Exception\NotModifiedException::class);

        $this->assertClientError(400, Exception\BadRequestException::class, array('foo'));
        $this->assertClientError(401, Exception\UnauthorizedException::class, array('foo', 'Basic', array('realm' => 'psx')));
        $this->assertClientError(403, Exception\ForbiddenException::class, array('foo'));
        $this->assertClientError(404, Exception\NotFoundException::class, array('foo'));
        $this->assertClientError(405, Exception\MethodNotAllowedException::class, array('foo', array('GET', 'POST')));
        $this->assertClientError(406, Exception\NotAcceptableException::class, array('foo'));
        $this->assertClientError(409, Exception\ConflictException::class, array('foo'));
        $this->assertClientError(410, Exception\GoneException::class, array('foo'));
        $this->assertClientError(415, Exception\UnsupportedMediaTypeException::class, array('foo'));

        $this->assertServerError(500, Exception\InternalServerErrorException::class, array('foo'));
        $this->assertServerError(501, Exception\NotImplementedException::class, array('foo'));
        $this->assertServerError(503, Exception\ServiceUnavailableException::class, array('foo'));
    }

    protected function assertRedirection($statusCode, $className, array $arguments = array()): void
    {
        $e = $this->getException($className, $arguments);

        $this->assertInstanceOf(Exception\RedirectionException::class, $e);
        $this->assertGreaterThanOrEqual(300, $e->getStatusCode());
        $this->assertLessThan(400, $e->getStatusCode());
        $this->assertEquals($statusCode, $e->getStatusCode());

        if ($statusCode != 304) {
            $this->assertEquals('http://google.com', $e->getLocation());
        }
    }

    protected function assertClientError($statusCode, $className, array $arguments = array()): void
    {
        $e = $this->getException($className, $arguments);

        $this->assertInstanceOf(Exception\ClientErrorException::class, $e);
        $this->assertGreaterThanOrEqual(400, $e->getStatusCode());
        $this->assertLessThan(500, $e->getStatusCode());
        $this->assertEquals($statusCode, $e->getStatusCode());

        if ($statusCode == 401) {
            $this->assertInstanceOf(Exception\UnauthorizedException::class, $e);
            $this->assertEquals('Basic', $e->getType());
            $this->assertEquals(array('realm' => 'psx'), $e->getParameters());
        } elseif ($statusCode == 405) {
            $this->assertInstanceOf(Exception\MethodNotAllowedException::class, $e);
            $this->assertEquals(array('GET', 'POST'), $e->getAllowedMethods());
        }
    }

    protected function assertServerError($statusCode, $className, array $arguments = array()): void
    {
        $e = $this->getException($className, $arguments);

        $this->assertInstanceOf(Exception\ServerErrorException::class, $e);
        $this->assertGreaterThanOrEqual(500, $e->getStatusCode());
        $this->assertLessThan(600, $e->getStatusCode());
        $this->assertEquals($statusCode, $e->getStatusCode());
    }

    protected function getException($className, array $arguments = array())
    {
        $class     = new ReflectionClass($className);
        $exception = $class->newInstanceArgs($arguments);

        if (!$exception instanceof \Exception) {
            $this->fail('Class must be an exception');
        }

        try {
            throw $exception;
        } catch (\Exception $e) {
            return $e;
        }
    }
}
