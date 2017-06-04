<?php
/*
 * PSX is a open source PHP framework to develop RESTful APIs.
 * For the current version and informations visit <http://phpsx.org>
 *
 * Copyright 2010-2017 Christoph Kappestein <christoph.kappestein@gmail.com>
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

namespace PSX\Http;

/**
 * MultipartResponse
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 * @see     https://tools.ietf.org/html/rfc2046#section-5.1.1
 */
class MultipartResponse extends Response
{
    /**
     * @var string
     */
    protected $subtype;

    /**
     * @var string
     */
    protected $boundary;

    /**
     * @var \PSX\Http\ResponseInterface[]
     */
    protected $parts;

    public function __construct($code = null, array $headers = array(), $subType = 'mixed', $boundary = null)
    {
        parent::__construct($code, $headers);

        $this->subtype  = $subType;
        $this->boundary = $boundary === null ? $this->generateBoundary() : $boundary;
        $this->parts    = [];

        $this->setHeader('Content-Type', 'multipart/' . $this->subtype . '; boundary="' . $this->boundary . '"');
    }

    public function getSubType()
    {
        return $this->subtype;
    }

    public function getBoundary()
    {
        return $this->boundary;
    }

    public function addPart(ResponseInterface $response)
    {
        $this->parts[] = $response;
    }

    public function getParts()
    {
        return $this->parts;
    }

    public function toString()
    {
        $response = ResponseParser::buildStatusLine($this) . Http::NEW_LINE;
        $headers  = ResponseParser::buildHeaderFromMessage($this);

        foreach ($headers as $header) {
            $response.= $header . Http::NEW_LINE;
        }

        $response.= Http::NEW_LINE;

        foreach ($this->parts as $part) {
            $response.= '--' . $this->boundary . Http::NEW_LINE;

            $headers = ResponseParser::buildHeaderFromMessage($part);
            foreach ($headers as $header) {
                $response.= $header . Http::NEW_LINE;
            }

            $response.= Http::NEW_LINE;
            $response.= (string) $part->getBody();
            $response.= Http::NEW_LINE;
        }

        $response.= '--' . $this->boundary . '--' . Http::NEW_LINE;

        return $response;
    }

    private function generateBoundary()
    {
        return sha1(uniqid());
    }
}
