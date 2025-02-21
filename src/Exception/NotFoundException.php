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
 * The HTTP 404 Not Found response status code indicates that the server cannot find the requested resource. Links that
 * lead to a 404 page are often called broken or dead links and can be subject to link rot.
 *
 * A 404 status code only indicates that the resource is missing: not whether the absence is temporary or permanent. If
 * a resource is permanently removed, use the 410 (Gone) status instead.
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class NotFoundException extends ClientErrorException
{
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }
}
