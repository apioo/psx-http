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

namespace PSX\Http\Exception;

/**
 * The HyperText Transfer Protocol (HTTP) 412 Precondition Failed client error response code indicates that access to
 * the target resource has been denied. This happens with conditional requests on methods other than GET or HEAD when
 * the condition defined by the If-Unmodified-Since or If-None-Match headers is not fulfilled. In that case, the
 * request, usually an upload or a modification of a resource, cannot be made and this error response is sent back.
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class PreconditionFailedException extends ClientErrorException
{
    public function __construct(string $message, \Throwable $previous = null)
    {
        parent::__construct($message, 412, $previous);
    }
}
