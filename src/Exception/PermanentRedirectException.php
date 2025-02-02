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
 * The HyperText Transfer Protocol (HTTP) 308 Permanent Redirect redirect status response code indicates that the
 * resource requested has been definitively moved to the URL given by the Location headers. A browser redirects to this
 * page and search engines update their links to the resource (in 'SEO-speak', it is said that the 'link-juice' is sent
 * to the new URL).
 *
 * The request method and the body will not be altered, whereas 301 may incorrectly sometimes be changed to a GET
 * method.
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class PermanentRedirectException extends RedirectionException
{
    public function __construct(string $location, \Throwable $previous = null)
    {
        parent::__construct(308, $location, $previous);
    }
}
