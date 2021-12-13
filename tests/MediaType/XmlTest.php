<?php
/*
 * PSX is an open source PHP framework to develop RESTful APIs.
 * For the current version and information visit <https://phpsx.org>
 *
 * Copyright 2010-2022 Christoph Kappestein <christoph.kappestein@gmail.com>
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

namespace PSX\Http\Tests\MediaType;

use PHPUnit\Framework\TestCase;
use PSX\Http\MediaType;

/**
 * XmlTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class XmlTest extends TestCase
{
    /**
     * @dataProvider mediaTypeProvider
     */
    public function testIsMediaType($mime, $expected)
    {
        $this->assertSame($expected, MediaType\Xml::isMediaType(new MediaType($mime)));
    }

    public function mediaTypeProvider()
    {
        return [
            ['text/xml', true],
            ['application/xml', true],
            ['text/xml-external-parsed-entity', true],
            ['application/xml-external-parsed-entity', true],
            ['application/xml-dtd', true],
            ['application/atom+xml', true],
            ['text/plain', false],
        ];
    }
    
    public function testMediaTypes()
    {
        $mediaTypes = [
            'text/xml',
            'application/xml',
            'text/xml-external-parsed-entity',
            'application/xml-external-parsed-entity',
            'application/xml-dtd'
        ];

        $this->assertEquals($mediaTypes, MediaType\Xml::getMediaTypes());
    }
}
