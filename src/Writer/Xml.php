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

namespace PSX\Http\Writer;

use DOMDocument;
use InvalidArgumentException;
use PSX\Http\ResponseInterface;
use SimpleXMLElement;

/**
 * Xml
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class Xml extends Writer
{
    /**
     * @var \DOMDocument|\SimpleXMLElement|string
     */
    protected $data;

    /**
     * @param \DOMDocument|\SimpleXMLElement|string $data
     * @param string $contentType
     */
    public function __construct($data, $contentType = 'application/xml')
    {
        if ($data instanceof DOMDocument) {
        } elseif ($data instanceof SimpleXMLElement) {
        } elseif (is_string($data)) {
        } else {
            throw new InvalidArgumentException('Document must be either a string, DOMDocument or SimpleXMLElement');
        }

        parent::__construct($data, $contentType);
    }

    /**
     * @inheritdoc
     */
    public function writeTo(ResponseInterface $response)
    {
        $response->setHeader('Content-Type', $this->contentType);
        $response->getBody()->write($this->toString());
    }

    /**
     * @return string
     */
    private function toString()
    {
        if ($this->data instanceof DOMDocument) {
            return (string) $this->data->saveXML();
        } elseif ($this->data instanceof SimpleXMLElement) {
            return (string) $this->data->asXML();
        } else {
            return $this->data;
        }
    }
}
