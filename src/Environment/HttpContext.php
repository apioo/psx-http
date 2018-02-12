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

namespace PSX\Http\Environment;

use PSX\Http\RequestInterface;

/**
 * HttpContext
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class HttpContext implements HttpContextInterface
{
    /**
     * @var \PSX\Http\RequestInterface
     */
    protected $request;

    /**
     * @var array
     */
    protected $uriFragments;

    /**
     * @param \PSX\Http\RequestInterface $request
     * @param array $uriFragments
     */
    public function __construct(RequestInterface $request, array $uriFragments)
    {
        $this->request      = $request;
        $this->uriFragments = $uriFragments;
    }

    /**
     * @inheritdoc
     */
    public function getMethod()
    {
        return $this->request->getMethod();
    }

    /**
     * @inheritdoc
     */
    public function getHeader($name)
    {
        return $this->request->getHeader($name);
    }

    /**
     * @inheritdoc
     */
    public function getHeaders()
    {
        return $this->request->getHeaders();
    }

    /**
     * @inheritdoc
     */
    public function getUriFragment($name)
    {
        return $this->uriFragments[$name] ?? null;
    }

    /**
     * @inheritdoc
     */
    public function getUriFragments()
    {
        return $this->uriFragments;
    }

    /**
     * @inheritdoc
     */
    public function getParameter($name)
    {
        return $this->request->getUri()->getParameter($name);
    }

    /**
     * @inheritdoc
     */
    public function getParameters()
    {
        return $this->request->getUri()->getParameters();
    }
}
