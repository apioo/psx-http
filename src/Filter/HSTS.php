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
 * HSTS
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class HSTS implements FilterInterface
{
    const INCLUDE_SUB_DOMAINS = 1;
    const PRELOAD = 2;

    /**
     * @var integer
     */
    protected $maxAge;

    /**
     * @var integer
     */
    protected $mode;

    /**
     * @param integer $maxAge
     * @param integer|null $mode
     */
    public function __construct($maxAge, $mode = null)
    {
        $this->maxAge = $maxAge;
        $this->mode   = $mode;
    }

    /**
     * @inheritdoc
     */
    public function handle(RequestInterface $request, ResponseInterface $response, FilterChainInterface $filterChain)
    {
        if ($this->maxAge > 0) {
            $mode = '';
            if ($this->mode === self::INCLUDE_SUB_DOMAINS) {
                $mode = '; includeSubDomains';
            } elseif ($this->mode === self::PRELOAD) {
                $mode = '; preload';
            }

            $response->setHeader('Strict-Transport-Security', 'max-age=' . $this->maxAge . $mode);
        }

        $filterChain->handle($request, $response);
    }
}
