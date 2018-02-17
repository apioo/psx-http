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

namespace PSX\Framework\Tests\Filter;

use PSX\Http\Authentication;
use PSX\Http\Exception\UnauthorizedException;
use PSX\Http\Filter\DigestAuthentication;
use PSX\Http\Filter\DigestAuthentication\Digest;
use PSX\Http\Filter\DigestAuthentication\MemoryStore;
use PSX\Http\Filter\DigestAuthentication\StoreInterface;
use PSX\Http\Filter\FilterChain;
use PSX\Http\FilterChainInterface;
use PSX\Http\Request;
use PSX\Http\Response;
use PSX\Uri\Url;

/**
 * DigestAuthenticationTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class DigestAuthenticationTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessful()
    {
        $store  = new MemoryStore();
        $handle = $this->makeHandshake($store);

        $handle->onSuccess(function () {
            // success
        });

        $username = 'test';
        $password = 'test';

        $container = $store->toArray();
        /** @var Digest $digest */
        $digest    = array_shift($container);

        $nonce    = $digest->getNonce();
        $opaque   = $digest->getOpaque();
        $cnonce   = md5(uniqid());
        $nc       = '00000001';
        $ha1      = md5($username . ':psx:' . $password);
        $ha2      = md5('GET:/index.php');
        $response = md5($ha1 . ':' . $nonce . ':' . $nc . ':' . $cnonce . ':auth:' . $ha2);

        $params = array(
            'username' => $username,
            'realm'    => 'psx',
            'nonce'    => $nonce,
            'qop'      => 'auth',
            'nc'       => $nc,
            'cnonce'   => $cnonce,
            'response' => $response,
            'opaque'   => $opaque,
        );

        $request  = new Request(new Url('http://localhost/index.php'), 'GET', array('Authorization' => 'Digest ' . Authentication::encodeParameters($params)));
        $response = new Response();

        $filterChain = $this->getMockFilterChain();
        $filterChain->expects($this->once())
            ->method('handle')
            ->with($this->equalTo($request), $this->equalTo($response));

        $handle->handle($request, $response, $filterChain);
    }

    /**
     * @expectedException \PSX\Http\Exception\BadRequestException
     */
    public function testFailure()
    {
        $store  = new MemoryStore();
        $handle = $this->makeHandshake($store);

        $username = 'test';
        $password = 'bar';

        $container = $store->toArray();
        /** @var Digest $digest */
        $digest    = array_shift($container);

        $nonce    = $digest->getNonce();
        $opaque   = $digest->getOpaque();
        $cnonce   = md5(uniqid());
        $nc       = '00000001';
        $ha1      = md5($username . ':psx:' . $password);
        $ha2      = md5('GET:/index.php');
        $response = md5($ha1 . ':' . $nonce . ':' . $nc . ':' . $cnonce . ':auth:' . $ha2);

        $params = array(
            'username' => $username,
            'realm'    => 'psx',
            'nonce'    => $nonce,
            'qop'      => 'auth',
            'nc'       => $nc,
            'cnonce'   => $cnonce,
            'response' => $response,
            'opaque'   => $opaque,
        );

        $request  = new Request(new Url('http://localhost/index.php'), 'GET', array('Authorization' => 'Digest ' . Authentication::encodeParameters($params)));
        $response = new Response();

        $filterChain = $this->getMockFilterChain();
        $filterChain->expects($this->never())
            ->method('handle');

        $handle->handle($request, $response, $filterChain);
    }

    public function testMissing()
    {
        $store  = new MemoryStore();
        $handle = new DigestAuthentication(function ($username) {
            return md5($username . ':psx:test');
        }, $store);

        $request  = new Request(new Url('http://localhost/index.php'), 'GET');
        $response = new Response();

        $filterChain = $this->getMockFilterChain();
        $filterChain->expects($this->never())
            ->method('handle');

        try {
            $handle->handle($request, $response, $filterChain);

            $this->fail('Must throw an Exception');
        } catch (UnauthorizedException $e) {
            $this->assertEquals(401, $e->getStatusCode());
            $this->assertEquals('Digest', $e->getType());

            $params = $e->getParameters();

            $this->assertEquals('auth,auth-int', $params['qop']);
            $this->assertTrue(strlen($params['nonce']) > 8);
            $this->assertTrue(strlen($params['opaque']) > 8);
        }
    }

    public function testMissingWrongType()
    {
        $store  = new MemoryStore();
        $handle = new DigestAuthentication(function ($username) {
            return md5($username . ':psx:test');
        }, $store);

        $request  = new Request(new Url('http://localhost'), 'GET', array('Authorization' => 'Foo'));
        $response = new Response();

        $filterChain = $this->getMockFilterChain();
        $filterChain->expects($this->never())
            ->method('handle');

        try {
            $handle->handle($request, $response, $filterChain);

            $this->fail('Must throw an Exception');
        } catch (UnauthorizedException $e) {
            $this->assertEquals(401, $e->getStatusCode());
            $this->assertEquals('Digest', $e->getType());

            $params = $e->getParameters();

            $this->assertEquals('auth,auth-int', $params['qop']);
            $this->assertTrue(strlen($params['nonce']) > 8);
            $this->assertTrue(strlen($params['opaque']) > 8);
        }
    }

    protected function makeHandshake(StoreInterface $store)
    {
        // first we make an normal request without authentication then we should
        // get an 401 response with the nonce and opaque then we can make an
        // authentication request
        $handle = new DigestAuthentication(function ($username) {
            return md5($username . ':psx:test');
        }, $store);

        $request  = new Request(new Url('http://localhost/index.php'), 'GET');
        $response = new Response();

        $filterChain = $this->getMockFilterChain();
        $filterChain->expects($this->never())
            ->method('handle');

        try {
            $handle->handle($request, $response, $filterChain);

            $this->fail('Must throw an Exception');
        } catch (UnauthorizedException $e) {
            $this->assertEquals(401, $e->getStatusCode());
            $this->assertEquals('Digest', $e->getType());

            $params = $e->getParameters();

            $this->assertEquals('auth,auth-int', $params['qop']);
            $this->assertTrue(strlen($params['nonce']) > 8);
            $this->assertTrue(strlen($params['opaque']) > 8);
        }

        return $handle;
    }

    /**
     * @return \PSX\Http\FilterChainInterface
     */
    protected function getMockFilterChain()
    {
        return $this->getMockBuilder(FilterChain::class)
            ->setConstructorArgs(array(array()))
            ->setMethods(array('handle'))
            ->getMock();
    }
}
