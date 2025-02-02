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

namespace PSX\Http\Tests\Client;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use PSX\Http\Client\Client;
use PSX\Http\Client\GetRequest;
use PSX\Http\Client\Options;
use PSX\Http\Client\PostRequest;
use PSX\Uri\Url;

/**
 * ClientTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class ClientTest extends TestCase
{
    public function testRequest()
    {
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], 'foobar'),
        ]);

        $container = [];
        $history = Middleware::history($container);

        $stack = HandlerStack::create($mock);
        $stack->push($history);

        $client   = new Client(['handler' => $stack]);
        $request  = new GetRequest(Url::parse('http://localhost.com'));
        $response = $client->request($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Bar', (string) $response->getHeader('X-Foo'));
        $this->assertEquals('foobar', (string) $response->getBody());

        $this->assertEquals(1, count($container));
        $transaction = array_shift($container);

        $this->assertEquals('GET', $transaction['request']->getMethod());
        $this->assertEquals(['localhost.com'], $transaction['request']->getHeader('Host'));
    }

    public function testRequestPost()
    {
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], 'foobar'),
        ]);

        $container = [];
        $history = Middleware::history($container);

        $stack = HandlerStack::create($mock);
        $stack->push($history);

        $client   = new Client(['handler' => $stack]);
        $request  = new PostRequest(Url::parse('http://localhost.com'), [], 'foobar');
        $response = $client->request($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Bar', (string) $response->getHeader('X-Foo'));
        $this->assertEquals('foobar', (string) $response->getBody());

        $this->assertEquals(1, count($container));
        $transaction = array_shift($container);

        $this->assertEquals('POST', $transaction['request']->getMethod());
        $this->assertEquals(['localhost.com'], $transaction['request']->getHeader('Host'));
        $this->assertEquals('foobar', (string) $transaction['request']->getBody());
    }

    public function testRequestSink()
    {
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], 'foobar'),
        ]);

        $container = [];
        $history = Middleware::history($container);

        $stack = HandlerStack::create($mock);
        $stack->push($history);

        $file = __DIR__ . '/file.json';
        $handle = fopen($file, 'w');
        $options = new Options();
        $options->setSink($handle);

        $client   = new Client(['handler' => $stack]);
        $request  = new PostRequest(Url::parse('http://localhost.com'), [], 'foobar');
        $response = $client->request($request, $options);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Bar', (string) $response->getHeader('X-Foo'));
        $this->assertEquals('foobar', (string) $response->getBody());

        $this->assertEquals('foobar', file_get_contents($file));

        $this->assertEquals(1, count($container));
        $transaction = array_shift($container);

        $this->assertEquals('POST', $transaction['request']->getMethod());
        $this->assertEquals(['localhost.com'], $transaction['request']->getHeader('Host'));
        $this->assertEquals('foobar', (string) $transaction['request']->getBody());
    }
}
