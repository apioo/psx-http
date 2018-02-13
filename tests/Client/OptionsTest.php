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

namespace PSX\Http\Tests\Client;

use PSX\Http\Client\Options;

/**
 * OptionsTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class OptionsTest extends \PHPUnit_Framework_TestCase
{
    public function testOptions()
    {
        $options = new Options();
        $options->setAllowRedirects(true);
        $options->setCert('foo');
        $options->setProxy('foo');
        $options->setSslKey('foo');
        $options->setVerify(true);
        $options->setTimeout(1.23);
        $options->setVersion(1.1);

        $this->assertSame(true, $options->getAllowRedirects());
        $this->assertSame('foo', $options->getCert());
        $this->assertSame('foo', $options->getProxy());
        $this->assertSame('foo', $options->getSslKey());
        $this->assertSame(true, $options->getVerify());
        $this->assertSame(1.23, $options->getTimeout());
        $this->assertSame(1.1, $options->getVersion());
    }

    public function testOptionsDefault()
    {
        $options = new Options();

        $this->assertNull($options->getAllowRedirects());
        $this->assertNull($options->getCert());
        $this->assertNull($options->getProxy());
        $this->assertNull($options->getSslKey());
        $this->assertNull($options->getVerify());
        $this->assertNull($options->getTimeout());
        $this->assertNull($options->getVersion());
    }
}
