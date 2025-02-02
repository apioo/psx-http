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

namespace PSX\Http\Filter;

use PSX\Http\FilterChainInterface;
use PSX\Http\FilterInterface;
use PSX\Http\RequestInterface;
use PSX\Http\ResponseInterface;

/**
 * Uses http headers to control the browser cache of the client
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class BrowserCache implements FilterInterface
{
    public const TYPE_PUBLIC      = 0x1;
    public const TYPE_PRIVATE     = 0x2;
    public const NO_CACHE         = 0x4;
    public const NO_STORE         = 0x8;
    public const NO_TRANSFORM     = 0x10;
    public const MUST_REVALIDATE  = 0x20;
    public const PROXY_REVALIDATE = 0x40;

    private int $flags;
    private ?int $maxAge;
    private ?int $sMaxAge;
    private ?\DateTime $expires;

    public function __construct(int $flags = 0, ?int $maxAge = null, ?int $sMaxAge = null, ?\DateTime $expires = null)
    {
        $this->flags   = $flags;
        $this->maxAge  = $maxAge;
        $this->sMaxAge = $sMaxAge;
        $this->expires = $expires;
    }

    public function setMaxAge(int $maxAge)
    {
        $this->maxAge = $maxAge;
    }

    public function setSMaxAge(int $sMaxAge)
    {
        $this->sMaxAge = $sMaxAge;
    }

    public function setExpires(\DateTime $expires)
    {
        $this->expires = $expires;
    }

    public function handle(RequestInterface $request, ResponseInterface $response, FilterChainInterface $filterChain): void
    {
        $cacheControl = array();

        if ($this->flags & self::TYPE_PUBLIC) {
            $cacheControl[] = 'public';
        }

        if ($this->flags & self::TYPE_PRIVATE) {
            $cacheControl[] = 'private';
        }

        if ($this->flags & self::NO_CACHE) {
            $cacheControl[] = 'no-cache';
        }

        if ($this->flags & self::NO_STORE) {
            $cacheControl[] = 'no-store';
        }

        if ($this->flags & self::NO_TRANSFORM) {
            $cacheControl[] = 'no-transform';
        }

        if ($this->flags & self::MUST_REVALIDATE) {
            $cacheControl[] = 'must-revalidate';
        }

        if ($this->flags & self::PROXY_REVALIDATE) {
            $cacheControl[] = 'proxy-revalidate';
        }

        if ($this->maxAge !== null) {
            $cacheControl[] = 'max-age=' . $this->maxAge;
        }

        if ($this->sMaxAge !== null) {
            $cacheControl[] = 's-maxage=' . $this->sMaxAge;
        }

        if (!empty($cacheControl)) {
            $response->setHeader('Cache-Control', implode(', ', $cacheControl));
        }

        if ($this->expires !== null) {
            $response->setHeader('Expires', $this->expires->format('D, d M Y H:i:s \G\M\T'));
        }

        $filterChain->handle($request, $response);
    }

    public static function expires(\DateTime $expires): self
    {
        return new self(0, null, null, $expires);
    }

    public static function cacheControl($flags = 0, $maxAge = null, $sMaxAge = null): self
    {
        return new self($flags, $maxAge, $sMaxAge);
    }

    public static function preventCache(): self
    {
        return new self(
            self::NO_STORE | self::NO_CACHE | self::MUST_REVALIDATE,
            null,
            null,
            new \DateTime('1986-10-09')
        );
    }
}
