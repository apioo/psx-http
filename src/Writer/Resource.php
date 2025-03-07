<?php
/*
 * PSX is an open source PHP framework to develop RESTful APIs.
 * For the current version and information visit <https://phpsx.org>
 *
 * Copyright (c) Christoph Kappestein <christoph.kappestein@gmail.com>
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

use InvalidArgumentException;
use PSX\Http\ResponseInterface;

/**
 * Resource
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class Resource extends Writer
{
    public function __construct($data, $contentType = 'application/octet-stream')
    {
        if (!is_resource($data)) {
            throw new InvalidArgumentException('data must be a resource');
        }

        parent::__construct($data, $contentType);
    }

    /**
     * @inheritdoc
     */
    public function writeTo(ResponseInterface $response): void
    {
        $response->setHeader('Content-Type', $this->contentType ?? '');
        $response->getBody()->write((string) stream_get_contents($this->data, -1, 0));
    }
}
