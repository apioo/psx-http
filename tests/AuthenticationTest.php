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

namespace PSX\Http\Tests;

use PSX\Http\Authentication;

/**
 * AuthenticationTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class AuthenticationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider decodeParametersProvider
     */
    public function testDecodeParameters($data, $expected)
    {
        $this->assertEquals($expected, Authentication::decodeParameters($data));
    }

    public function decodeParametersProvider()
    {
        return [
            ['realm="http-auth@example.org", qop="auth", algorithm=SHA-256', ['realm' => 'http-auth@example.org', 'qop' => 'auth', 'algorithm' => 'SHA-256']],
            ['Credential=AKIAIOSFODNN7EXAMPLE/20130524/us-east-1/s3/aws4_request, SignedHeaders=host;range;x-amz-date, Signature=fe5f80f77d5fa3beca038a248ff027d0445342fe2855ddc963176630326f1024', ['credential' => 'AKIAIOSFODNN7EXAMPLE/20130524/us-east-1/s3/aws4_request', 'signedheaders' => 'host;range;x-amz-date', 'signature' => 'fe5f80f77d5fa3beca038a248ff027d0445342fe2855ddc963176630326f1024']],
            ['realm="Access to the staging site", charset="UTF-8"', ['realm' => 'Access to the staging site', 'charset' => 'UTF-8']],
            ['foo=bar', ['foo' => 'bar']],
        ];
    }

    /**
     * @dataProvider encodeParametersProvider
     */
    public function testEncodeParameters($data, $expected)
    {
        $this->assertEquals($expected, Authentication::encodeParameters($data));
    }

    public function encodeParametersProvider()
    {
        return [
            [['realm' => 'http-auth@example.org', 'qop' => 'auth', 'algorithm' => 'SHA-256'], 'realm="http-auth@example.org", qop="auth", algorithm="SHA-256"'],
        ];
    }
}
