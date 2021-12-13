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

namespace PSX\Http\Writer;

use PSX\Http\Http;
use PSX\Http\Parser\ResponseParser;
use PSX\Http\ResponseInterface;

/**
 * Multipart
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class Multipart extends Writer
{
    protected string $subType;
    protected ?string $boundary;

    /**
     * @var ResponseInterface[]
     */
    protected array $parts;

    public function __construct(string $subType = 'mixed', ?string $boundary = null)
    {
        parent::__construct(null, null);

        $this->subType  = $subType;
        $this->boundary = $boundary === null ? $this->generateBoundary() : $boundary;
        $this->parts    = [];
    }

    public function getSubType(): string
    {
        return $this->subType;
    }

    public function getBoundary(): ?string
    {
        return $this->boundary;
    }

    public function addPart(ResponseInterface $response)
    {
        $this->parts[] = $response;
    }

    public function getParts(): array
    {
        return $this->parts;
    }

    public function writeTo(ResponseInterface $response)
    {
        $response->setHeader('Content-Type', 'multipart/' . $this->subType . '; boundary="' . $this->boundary . '"');

        $parts = $this->getParts();
        foreach ($parts as $part) {
            $out = '--' . $this->boundary . Http::NEW_LINE;

            $headers = ResponseParser::buildHeaderFromMessage($part);
            foreach ($headers as $header) {
                $out.= $header . Http::NEW_LINE;
            }

            $out.= Http::NEW_LINE;
            $out.= $part->getBody();
            $out.= Http::NEW_LINE;

            $response->getBody()->write($out);
        }

        $response->getBody()->write('--' . $this->boundary . '--' . Http::NEW_LINE);
    }

    private function generateBoundary(): string
    {
        return sha1(uniqid());
    }
}
