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

/**
 * Represents a middleware which can read data from a HTTP request and writes
 * data to a HTTP response. Can call the handle method of the filter chain to
 * trigger the next middleware in the chain
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
interface FilterInterface
{
    /**
     * Executes a middleware which can read data from the request and modify the 
     * response. A middleware can call the handle method of the filter chain in
     * order to execute the next middleware 
     */
    public function handle(RequestInterface $request, ResponseInterface $response, FilterChainInterface $filterChain): void;
}
