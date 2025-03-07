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
 * The HyperText Transfer Protocol (HTTP) 303 See Other redirect status response code indicates that the redirects don't
 * link to the requested resource itself, but to another page (such as a confirmation page, a representation of a
 * real-world object — see HTTP range-14 — or an upload-progress page). This response code is often sent back as a
 * result of PUT or POST. The method used to display this redirected page is always GET.
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class SeeOtherException extends RedirectionException
{
    public function __construct(string $location, ?\Throwable $previous = null)
    {
        parent::__construct(303, $location, $previous);
    }
}
