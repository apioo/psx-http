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

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use PSX\Http\Exception\InvalidFilterException;
use PSX\Http\FilterChainInterface;
use PSX\Http\FilterCollectionInterface;
use PSX\Http\FilterInterface;
use PSX\Http\RequestInterface;
use PSX\Http\ResponseInterface;

/**
 * FilterChain
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class FilterChain implements FilterChainInterface, LoggerAwareInterface
{
    private array $filters;
    private ?FilterChainInterface $filterChain;
    private ?LoggerInterface $logger;

    public function __construct(iterable $filters = [], ?FilterChainInterface $filterChain = null)
    {
        $this->filters     = [];
        $this->filterChain = $filterChain;
        $this->logger      = null;

        $this->addAll($filters);
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function on(mixed $filter): void
    {
        $this->filters[] = $filter;
    }

    public function addAll(iterable $filters)
    {
        foreach ($filters as $filter) {
            $this->on($filter);
        }
    }

    public function handle(RequestInterface $request, ResponseInterface $response): void
    {
        $filter = array_shift($this->filters);

        if ($filter === null) {
            // if we have no filters check whether we have a parent filter chain
            // which should be called next
            if ($this->filterChain !== null) {
                $this->filterChain->handle($request, $response);
            }
        } elseif ($filter instanceof FilterInterface) {
            if ($this->logger !== null) {
                $this->logger->debug('Filter execute ' . get_class($filter));
            }

            $filter->handle($request, $response, $this);
        } elseif (is_callable($filter)) {
            call_user_func_array($filter, [$request, $response, $this]);
        } else {
            throw new InvalidFilterException('Invalid filter value');
        }
    }
}
