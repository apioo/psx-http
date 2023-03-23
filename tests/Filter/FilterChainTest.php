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

namespace PSX\Http\Tests\Filter;

use PHPUnit\Framework\TestCase;
use PSX\Http\Filter\FilterChain;
use PSX\Http\FilterChainInterface;
use PSX\Http\Request;
use PSX\Http\RequestInterface;
use PSX\Http\Response;
use PSX\Http\ResponseInterface;
use PSX\Uri\Uri;

/**
 * FilterChainTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class FilterChainTest extends TestCase
{
    public function testFilterChain()
    {
        $request  = new Request(Uri::parse('/'), 'GET');
        $response = new Response();

        $chain = new FilterChain();

        $chain->on(function(RequestInterface $request, ResponseInterface $response, FilterChainInterface $filterChain){
            $request->setAttribute('closure', true);
            
            $filterChain->handle($request, $response);
        });

        $chain->on(new TestFilter());

        $chain->handle($request, $response);

        $this->assertSame(true, $request->getAttribute('closure'));
        $this->assertSame(true, $request->getAttribute('class'));
    }
}
