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

use Generator;
use PSX\Http\ResponseInterface;
use PSX\Http\Stream\ServerSentEventStream;

/**
 * ServerSentEvent
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class ServerSentEvent extends Writer
{
    public function __construct(Generator $producer, $contentType = 'text/event-stream')
    {
        parent::__construct($producer, $contentType);
    }

    public function writeTo(ResponseInterface $response): void
    {
        $response->setHeader('X-Accel-Buffering', 'no');
        $response->setHeader('Content-Type', $this->contentType ?? '');
        $response->setHeader('Cache-Control', 'no-cache');
        $response->setBody(new ServerSentEventStream($this->data));
    }
}
