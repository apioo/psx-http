<?php
/*
 * PSX is an open source PHP framework to develop RESTful APIs.
 * For the current version and information visit <https://phpsx.org>
 *
 * Copyright 2010-2023 Christoph Kappestein <christoph.kappestein@gmail.com>
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

namespace PSX\Http\Filter;

use PSX\Http\FilterChainInterface;
use PSX\Http\FilterInterface;
use PSX\Http\RequestInterface;
use PSX\Http\ResponseInterface;

/**
 * Inspired by rubys rack backstage. If the specified file exists it gets
 * written as response i.e. to show an maintenance message else the next filter
 * gets called. Note the message gets only displayed for text/html visitors all
 * other requests get passed to the application
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class Backstage implements FilterInterface
{
    private string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function handle(RequestInterface $request, ResponseInterface $response, FilterChainInterface $filterChain): void
    {
        $accept = $request->getHeader('Accept');

        if (stripos($accept, 'text/html') !== false && is_file($this->file)) {
            $response->setHeader('Content-Type', 'text/html');
            $response->getBody()->write((string) file_get_contents($this->file));
        } else {
            $filterChain->handle($request, $response);
        }
    }
}
