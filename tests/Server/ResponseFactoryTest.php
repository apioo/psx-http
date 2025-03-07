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

namespace PSX\Http\Tests\Server;

use PHPUnit\Framework\TestCase;
use PSX\Http\Server\ResponseFactory;

/**
 * ResponseFactoryTest
 *
 * @see     http://www.ietf.org/rfc/rfc3875
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class ResponseFactoryTest extends TestCase
{
    /**
     * @var array
     */
    protected $server;

    protected function setUp(): void
    {
        parent::setUp();

        // the test modifies the global server variable so store and reset the
        // values after the test
        $this->server = $_SERVER;
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $_SERVER = $this->server;
    }

    public function testCreateResponse()
    {
        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.0';

        $factory  = new ResponseFactory();
        $response = $factory->createResponse();

        $this->assertEquals('HTTP/1.0', $response->getProtocolVersion());
    }

    public function testCreateResponseProtocolFallback()
    {
        $factory  = new ResponseFactory();
        $response = $factory->createResponse();

        $this->assertEquals('HTTP/1.1', $response->getProtocolVersion());
    }
}
