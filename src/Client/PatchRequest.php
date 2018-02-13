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

namespace PSX\Http\Client;

use PSX\Http\Request;
use PSX\Uri\Uri;
use PSX\Uri\UriInterface;

/**
 * PatchRequest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class PatchRequest extends Request
{
    /**
     * @param \PSX\Uri\UriInterface|string $uri
     * @param array $headers
     * @param \PSX\Http\StreamInterface|string|array $body
     */
    public function __construct($uri, array $headers = array(), $body = null)
    {
        if (!$uri instanceof UriInterface) {
            $uri = new Uri((string) $uri);
        }

        parent::__construct($uri, 'PATCH', $headers, $body);

        $host = $uri->getHost();
        if (!empty($host) && !$this->hasHeader('Host')) {
            $this->setHeader('Host', $uri->getHost());
        }
    }
}
