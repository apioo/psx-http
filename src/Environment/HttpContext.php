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

namespace PSX\Http\Environment;

use PSX\Http\RequestInterface;

/**
 * HttpContext
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class HttpContext implements HttpContextInterface
{
    private RequestInterface $request;
    private array $uriFragments;

    public function __construct(RequestInterface $request, array $uriFragments)
    {
        $this->request = $request;
        $this->uriFragments = $uriFragments;
    }

    public function getMethod(): string
    {
        return $this->request->getMethod();
    }

    public function getHeader($name): ?string
    {
        return $this->request->getHeader($name);
    }

    public function getHeaders(): array
    {
        return $this->request->getHeaders();
    }

    public function getUriFragment(string $name): ?string
    {
        return $this->uriFragments[$name] ?? null;
    }

    public function getUriFragments(): array
    {
        return $this->uriFragments;
    }

    public function getParameter($name): string|array|null
    {
        return $this->request->getUri()->getParameter($name);
    }

    public function getParameters(): array
    {
        return $this->request->getUri()->getParameters();
    }
}
