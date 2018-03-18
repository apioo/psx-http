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

use PSX\Http\Filter\CORS;
use PSX\Http\Filter\FilterChain;
use PSX\Http\Request;
use PSX\Http\RequestInterface;
use PSX\Http\Response;
use PSX\Http\ResponseInterface;
use PSX\Uri\Url;

/**
 * CORSTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
abstract class FilterTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param boolean $expectNextCall
     * @param \PSX\Http\RequestInterface|null $expectRequest
     * @param \PSX\Http\ResponseInterface|null $expectResponse
     * @return \PSX\Http\FilterChainInterface
     */
    protected function getFilterChain($expectNextCall, RequestInterface $expectRequest = null, ResponseInterface $expectResponse = null)
    {
        $filterChain = $this->getMockBuilder(FilterChain::class)
            ->setConstructorArgs([[]])
            ->setMethods(['handle'])
            ->getMock();

        if ($expectNextCall) {
            $filterChain->expects($this->once())
                ->method('handle')
                ->with($this->equalTo($expectRequest), $this->equalTo($expectResponse));
        } else {
            $filterChain->expects($this->never())
                ->method('handle');
        }

        return $filterChain;
    }
}
