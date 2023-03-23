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

use PSX\Http\Filter\HSTS;
use PSX\Http\Request;
use PSX\Http\Response;
use PSX\Uri\Url;

/**
 * HSTSTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class HSTSTest extends FilterTestCase
{
    /**
     * @dataProvider hstsProvider
     */
    public function testHandle($maxAge, $mode, array $expectHeaders)
    {
        $request  = new Request(Url::parse('http://localhost'), 'GET', []);
        $response = new Response();

        $handle = new HSTS($maxAge, $mode);
        $handle->handle($request, $response, $this->getFilterChain(true, $request, $response));

        $this->assertEquals($expectHeaders, $response->getHeaders());
    }

    public function hstsProvider()
    {
        return [
            [0, null, []],
            [8600, null, ['strict-transport-security' => ['max-age=8600']]],
            [8600, HSTS::INCLUDE_SUB_DOMAINS, ['strict-transport-security' => ['max-age=8600; includeSubDomains']]],
            [8600, HSTS::PRELOAD, ['strict-transport-security' => ['max-age=8600; preload']]],
        ];
    }
}
