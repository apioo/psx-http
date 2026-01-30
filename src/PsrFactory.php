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

namespace PSX\Http;

use Nyholm\Psr7;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * Factory to convert incoming PSX requests to PSR and convert outgoing PSR responses to PSX.
 * This can be useful if your controller wants to invoke PSR compatible systems
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class PsrFactory
{
    public function toPsrRequest(RequestInterface $request): PsrRequestInterface
    {
        return new Psr7\Request($request->getMethod(), $request->getUri(), $request->getHeaders(), $request->getBody(), $request->getProtocolVersion() ?? '1.1');
    }

    public function toPsrServerRequest(RequestInterface $request): PsrRequestInterface
    {
        return new Psr7\ServerRequest($request->getMethod(), $request->getUri(), $request->getHeaders(), $request->getBody(), $request->getProtocolVersion() ?? '1.1', $request->getAttributes());
    }

    public function fromPsrResponse(PsrResponseInterface $response): ResponseInterface
    {
        return new Response($response->getStatusCode(), $response->getHeaders(), $response->getBody());
    }
}
