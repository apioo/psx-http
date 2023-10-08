<?php
/*
 * PSX is an open source PHP framework to develop RESTful APIs.
 * For the current version and information visit <https://phpsx.org>
 *
 * Copyright 2010-2023 Christoph Kappestein <christoph.kappestein@gmail.com>
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
 * The HyperText Transfer Protocol (HTTP) 302 Found redirect status response code indicates that the resource requested
 * has been temporarily moved to the URL given by the Location header. A browser redirects to this page but search
 * engines don't update their links to the resource (in 'SEO-speak', it is said that the 'link-juice' is not sent to the
 * new URL).
 *
 * Even if the specification requires the method (and the body) not to be altered when the redirection is performed, not
 * all user-agents conform here - you can still find this type of bugged software out there. It is therefore recommended
 * to set the 302 code only as a response for GET or HEAD methods and to use 307 Temporary Redirect instead, as the
 * method change is explicitly prohibited in that case.
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class FoundException extends RedirectionException
{
    public function __construct(string $location, \Throwable $previous = null)
    {
        parent::__construct(302, $location, $previous);
    }
}
