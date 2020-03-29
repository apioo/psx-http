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

use PSX\Http\Exception\BadRequestException;
use PSX\Http\Filter\FilterChain;
use PSX\Http\Filter\UserAgentEnforcer;
use PSX\Http\Request;
use PSX\Http\Response;
use PSX\Uri\Url;

/**
 * UserAgentEnforcerTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class UserAgentEnforcerTest extends FilterTestCase
{
    public function testUserAgent()
    {
        $request  = new Request(new Url('http://localhost'), 'GET', ['User-Agent' => 'foobar']);
        $response = new Response();

        $filter = new UserAgentEnforcer();
        $filter->handle($request, $response, $this->getFilterChain(true, $request, $response));
    }

    public function testNoUserAgent()
    {
        $this->expectException(BadRequestException::class);

        $request  = new Request(new Url('http://localhost'), 'GET');
        $response = new Response();

        $filter = new UserAgentEnforcer();
        $filter->handle($request, $response, $this->getFilterChain(false));
    }
}
