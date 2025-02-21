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
 * The HTTP 415 Unsupported Media Type client error response code indicates that the server refuses to accept the
 * request because the payload format is in an unsupported format.
 *
 * The format problem might be due to the request's indicated Content-Type or Content-Encoding, or as a result of
 * inspecting the data directly.
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class UnsupportedMediaTypeException extends ClientErrorException
{
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, 415, $previous);
    }
}
