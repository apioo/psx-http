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

namespace PSX\Http\Stream;

use PSX\Http\StreamInterface;

/**
 * Stream which opens the stream only on actual usage
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class LazyStream implements StreamInterface
{
    use StreamWrapperTrait;

    private string $uri;
    private string $mode;
    private bool $opened = false;

    public function __construct(string $uri, string $mode = 'rb')
    {
        $this->uri  = $uri;
        $this->mode = $mode;
    }

    protected function call(): void
    {
        if ($this->opened) {
            return;
        }

        $this->stream = new Stream(fopen($this->uri, $this->mode));
        $this->opened = true;
    }
}
