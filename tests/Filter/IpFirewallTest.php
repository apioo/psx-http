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
use PSX\Http\Filter\IpFirewall;
use PSX\Http\Request;
use PSX\Http\Response;
use PSX\Uri\Url;

/**
 * IpFirewallTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class IpFirewallTest extends \PHPUnit_Framework_TestCase
{
    public function testValidIp()
    {
        $request  = new Request(new Url('http://localhost'), 'GET');
        $response = new Response();

        $request->setAttribute('REMOTE_ADDR', '127.0.0.1');

        $filterChain = $this->getMockBuilder(FilterChain::class)
            ->setConstructorArgs(array(array()))
            ->setMethods(array('handle'))
            ->getMock();

        $filterChain->expects($this->once())
            ->method('handle')
            ->with($this->equalTo($request), $this->equalTo($response));

        $filter = new IpFirewall(['127.0.0.1']);
        $filter->handle($request, $response, $filterChain);
    }

    /**
     * @expectedException \PSX\Http\Exception\ForbiddenException
     */
    public function testInvalidIp()
    {
        $request  = new Request(new Url('http://localhost'), 'GET');
        $response = new Response();

        $request->setAttribute('REMOTE_ADDR', '127.0.0.1');

        $filterChain = $this->getMockBuilder(FilterChain::class)
            ->setConstructorArgs(array(array()))
            ->setMethods(array('handle'))
            ->getMock();

        $filterChain->expects($this->never())
            ->method('handle')
            ->with($this->equalTo($request), $this->equalTo($response));

        $filter = new IpFirewall(['127.0.0.2']);
        $filter->handle($request, $response, $filterChain);
    }

    public function testNoIp()
    {
        $request  = new Request(new Url('http://localhost'), 'GET');
        $response = new Response();

        $filterChain = $this->getMockBuilder(FilterChain::class)
            ->setConstructorArgs(array(array()))
            ->setMethods(array('handle'))
            ->getMock();

        $filterChain->expects($this->once())
            ->method('handle')
            ->with($this->equalTo($request), $this->equalTo($response));

        $filter = new IpFirewall(['127.0.0.2']);
        $filter->handle($request, $response, $filterChain);
    }
}
