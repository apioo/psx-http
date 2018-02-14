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

namespace PSX\Http\Tests\Filter;

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
 * @link    http://phpsx.org
 */
class FilterChainTest extends \PHPUnit_Framework_TestCase
{
    public function testFilterChain()
    {
        $request  = new Request(new Uri('/'), 'GET');
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

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFilterChainInvalid()
    {
        $chain = new FilterChain();
        $chain->on(new \stdClass());
    }
}
