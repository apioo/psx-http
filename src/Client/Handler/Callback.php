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

namespace PSX\Http\Client\Handler;

use Closure;
use PSX\Http\Client\HandlerInterface;
use PSX\Http\Client\Options;
use PSX\Http\Parser\ResponseParser;
use PSX\Http\RequestInterface;
use PSX\Http\ResponseInterface;

/**
 * Callback
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class Callback implements HandlerInterface
{
    protected $callback;

    public function __construct(Closure $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @inheritdoc
     */
    public function request(RequestInterface $request, Options $options)
    {
        try {
            $response = call_user_func_array($this->callback, array($request, $options));

            if ($response instanceof ResponseInterface) {
                return $response;
            } else {
                return ResponseParser::convert((string) $response);
            }
        } catch (\PHPUnit_Framework_Exception $e) {
            throw $e;
        } catch (\ErrorException $e) {
            throw $e;
        }
    }
}
