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
 * HTTP 307 Temporary Redirect redirect status response code indicates that the resource requested has been temporarily
 * moved to the URL given by the Location headers.
 *
 * The method and the body of the original request are reused to perform the redirected request. In the cases where you
 * want the method used to be changed to GET, use 303 See Other instead. This is useful when you want to give an answer
 * to a PUT method that is not the uploaded resources, but a confirmation message (like "You successfully uploaded
 * XYZ").
 *
 * The only difference between 307 and 302 is that 307 guarantees that the method and the body will not be changed when
 * the redirected request is made. With 302, some old clients were incorrectly changing the method to GET: the behavior
 * with non-GET methods and 302 is then unpredictable on the Web, whereas the behavior with 307 is predictable. For GET
 * requests, their behavior is identical.
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class TemporaryRedirectException extends RedirectionException
{
    public function __construct(string $location, ?\Throwable $previous = null)
    {
        parent::__construct(307, $location, $previous);
    }
}
