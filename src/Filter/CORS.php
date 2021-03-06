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

namespace PSX\Http\Filter;

use PSX\Http\FilterChainInterface;
use PSX\Http\FilterInterface;
use PSX\Http\RequestInterface;
use PSX\Http\ResponseInterface;

/**
 * CORS
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class CORS implements FilterInterface
{
    /**
     * @var string|\Closure
     */
    protected $allowOrigin;

    /**
     * @var array
     */
    protected $allowMethods;

    /**
     * @var array
     */
    protected $allowHeaders;

    /**
     * @var boolean
     */
    protected $allowCredentials;

    /**
     * @param string|\Closure $allowOrigin
     * @param array|null $allowMethods
     * @param array|null $allowHeaders
     * @param boolean|null $allowCredentials
     */
    public function __construct($allowOrigin, array $allowMethods = null, array $allowHeaders = null, $allowCredentials = null)
    {
        $this->allowOrigin      = $allowOrigin;
        $this->allowMethods     = $allowMethods;
        $this->allowHeaders     = $allowHeaders;
        $this->allowCredentials = $allowCredentials;
    }

    public function handle(RequestInterface $request, ResponseInterface $response, FilterChainInterface $filterChain)
    {
        $allow  = false;
        $origin = $request->getHeader('Origin');
        if (!empty($origin)) {
            if (is_string($this->allowOrigin)) {
                $response->setHeader('Access-Control-Allow-Origin', $this->allowOrigin);
                $allow = true;
            } elseif ($this->allowOrigin instanceof \Closure) {
                $func = $this->allowOrigin;
                if ($func($origin)) {
                    $response->setHeader('Access-Control-Allow-Origin', $origin);
                    $response->addHeader('Vary', 'Origin');
                    $allow = true;
                }
            }

            if ($allow && $this->allowCredentials) {
                $response->setHeader('Access-Control-Allow-Credentials', 'true');
            }
        }

        if ($allow && $request->getMethod() == 'OPTIONS') {
            $method = $request->getHeader('Access-Control-Request-Method');
            if (!empty($method)) {
                $response->setHeader('Access-Control-Allow-Methods', implode(', ', $this->allowMethods));
            }

            $headers = $request->getHeader('Access-Control-Request-Headers');
            if (!empty($headers)) {
                $response->setHeader('Access-Control-Allow-Headers', implode(', ', $this->allowHeaders));
            }

            $response->setHeader('Access-Control-Expose-Headers', '*');
        }

        $filterChain->handle($request, $response);
    }

    public static function allowOrigin($allowOrigin)
    {
        return new self($allowOrigin);
    }
}
