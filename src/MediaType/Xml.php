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

namespace PSX\Http\MediaType;

use PSX\Http\MediaType;

/**
 * Xml
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class Xml
{
    protected static $mediaTypes = array(
        'text/xml',
        'application/xml',
        'text/xml-external-parsed-entity',
        'application/xml-external-parsed-entity',
        'application/xml-dtd'
    );

    public static function isMediaType(MediaType $mediaType)
    {
        return in_array($mediaType->getName(), self::$mediaTypes) ||
            substr($mediaType->getSubType(), -4) == '+xml' ||
            substr($mediaType->getSubType(), -4) == '/xml';
    }

    public static function getMediaTypes()
    {
        return self::$mediaTypes;
    }
}
