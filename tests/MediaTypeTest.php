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

use PSX\Http\MediaType;

/**
 * MediaTypeTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class MediaTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider mediaTypeProvider
     */
    public function testParse($mime, $type, $subType, $quality, array $parameters)
    {
        $mediaType = new MediaType($mime);

        $this->assertEquals($type, $mediaType->getType());
        $this->assertEquals($subType, $mediaType->getSubType());
        $this->assertEquals($type . '/' . $subType, $mediaType->getName());
        $this->assertEquals($quality, $mediaType->getQuality());
        $this->assertEquals($parameters, $mediaType->getParameters());
    }

    public function mediaTypeProvider()
    {
        return array(
            ['application/xml', 'application', 'xml', 1, []],
            ['text/html; encoding=UTF-8', 'text', 'html', 1, ['encoding' => 'UTF-8']],
            ['text/html;encoding=UTF-8', 'text', 'html', 1, ['encoding' => 'UTF-8']],
            ['text/html;	encoding=UTF-8', 'text', 'html', 1, ['encoding' => 'UTF-8']],
            ['text/html; encoding = UTF-8', 'text', 'html', 1, ['encoding' => 'UTF-8']],
            ['text/html; encoding=UTF-8; boundary=frontier', 'text', 'html', 1, ['encoding' => 'UTF-8', 'boundary' => 'frontier']],
            ['text/html; foo="bar test"', 'text', 'html', 1, ['foo' => 'bar test']],
            ['text/html; foo=', 'text', 'html', 1, ['foo' => null]],
            ['text/html; foo=""', 'text', 'html', 1, ['foo' => null]],
            ['Message/Partial; number=2; total=3; id="oc=jpbe0M2Yt4s@thumper.bellcore.com"; ', 'message', 'partial', 1, ['number' => '2', 'total' => '3', 'id' => 'oc=jpbe0M2Yt4s@thumper.bellcore.com']],
            ['message/external-body; access-type=local-file; name="/u/nsb/Me.gif" ', 'message', 'external-body', 1, ['access-type' => 'local-file', 'name' => '/u/nsb/Me.gif']],
            ['text/html;level=1', 'text', 'html', 1, ['level' => '1']],
            ['text/html', 'text', 'html', 1, []],
            ['text/*', 'text', '*', 1, []],
            ['*/*', '*', '*', 1, []],
            ['audio/*; q=0.2', 'audio', '*', 0.2, ['q' => '0.2']],
            ['audio/basic', 'audio', 'basic', 1, []],
            ['text/plain; q=0.5', 'text', 'plain', 0.5, ['q' => '0.5']],
            ['application/vnd.psx.v2+json', 'application', 'vnd.psx.v2+json', 1, []],
            ['application/atom+xml', 'application', 'atom+xml', 1, []],
            ['text/plain; q=2', 'text', 'plain', 1, ['q' => '2']],
            ['application/xml;foo="bar/baz"', 'application', 'xml', 1, ['foo' => 'bar/baz']],
        );
    }

    public function testGetParameter()
    {
        $mediaType = new MediaType('text/html; encoding=UTF-8');

        $this->assertEquals('UTF-8', $mediaType->getParameter('encoding'));
        $this->assertEquals(null, $mediaType->getParameter('foo'));
    }

    public function testParseListQuality()
    {
        $mediaTypes = MediaType::parseList('text/plain; q=0.5, text/html, text/x-dvi; q=0.8, text/x-c;q=0.9');
        $actual     = array();

        foreach ($mediaTypes as $mediaType) {
            $actual[] = $mediaType->getName();
        }

        $expect = array(
            'text/html',
            'text/x-c',
            'text/x-dvi',
            'text/plain',
        );

        $this->assertEquals($expect, $actual);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testParseEmpty()
    {
        new MediaType('');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testParseInvalid()
    {
        new MediaType('foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testParseInvalidMediaType()
    {
        new MediaType('foo/bar');
    }

    /**
     * @dataProvider acceptHeaderProvider
     */
    public function testParseList($accept, $mediaTypeCount)
    {
        $mediaTypes = MediaType::parseList($accept);

        $this->assertEquals($mediaTypeCount, count($mediaTypes));
    }

    public function acceptHeaderProvider()
    {
        return array(
            ['', 0],
            ['foo', 0],
            ['text/plain; q=0.5, ', 1],
            ['text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8', 5],
            ['text/html, application/xhtml+xml, */*', 3],
            ['text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8', 4],
            ['application/xml;q=0.9,*/*;q=0.8', 2],
            ['image/jpeg, application/x-ms-application, image/gif, application/xaml+xml, image/pjpeg, application/x-ms-xbap, application/x-shockwave-flash, application/msword, */*', 9],
        );
    }

    public function testFullConstructor()
    {
        $mediaType = new MediaType('text', 'plain', array('q' => 0.5, 'encoding' => 'UTF-8'));

        $this->assertEquals('text/plain; q=0.5; encoding=UTF-8', $mediaType->toString());
    }

    public function testToString()
    {
        $mediaType = new MediaType('text/plain');

        $this->assertEquals('text/plain', (string) $mediaType);
    }

    /**
     * We check whether we can open all registered IANA content types
     */
    public function testRegisteredIANAMediaTypes()
    {
        $dom = new \DOMDocument();
        $dom->load(__DIR__ . '/media-types.xml');

        $elements = $dom->getElementsByTagName('file');

        foreach ($elements as $element) {
            if ($element->getAttribute('type') == 'template') {
                $mediaType = new MediaType($element->textContent);

                $this->assertNotEmpty($mediaType->getType());
                $this->assertNotEmpty($mediaType->getSubType());
            }
        }
    }
}
