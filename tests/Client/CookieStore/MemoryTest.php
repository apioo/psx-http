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

namespace PSX\Http\Tests\Client\CookieStore;

use PSX\Http\Client\Cookie;
use PSX\Http\Client\CookieStore\Memory;

/**
 * MemoryTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class MemoryTest extends \PHPUnit_Framework_TestCase
{
    public function testStore()
    {
        $store = new Memory();

        $cookies = $store->load('foo.com');

        $this->assertEmpty($cookies);

        $cookie = new Cookie('foo=bar');

        $store->store('foo.com', $cookie);

        $cookies = $store->load('foo.com');

        $this->assertEquals(array('foo' => $cookie), $cookies);
        $this->assertEquals('bar', $cookie->getValue());

        $store->remove('foo.com', $cookie);
        $store->remove('bar.com', $cookie); // unknown

        $cookies = $store->load('foo.com');

        $this->assertEmpty($cookies);
    }
}