<?php
/*
 * PSX is an open source PHP framework to develop RESTful APIs.
 * For the current version and information visit <https://phpsx.org>
 *
 * Copyright 2010-2022 Christoph Kappestein <christoph.kappestein@gmail.com>
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

use PSX\Http\RequestInterface;
use PSX\Http\Response;
use PSX\Http\ResponseInterface;

/**
 * This class is a simple wrapper around guzzle to offer a simple way to send
 * http requests
 * 
 * <code>
 * $client   = new Client();
 * $request  = new GetRequest('http://google.com');
 * $response = $client->request($request);
 *
 * if ($response->getStatusCode() == 200) {
 *     echo (string) $response->getBody();
 * }
 * </code>
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class Client implements ClientInterface
{
    private \GuzzleHttp\Client $client;

    public function __construct(array $options = [])
    {
        $this->client = new \GuzzleHttp\Client($options);
    }

    /**
     * @inheritdoc
     */
    public function request(RequestInterface $request, ?OptionsInterface $options = null): ResponseInterface
    {
        if ($options === null) {
            $options = new Options();
        }

        $opt = [
            'allow_redirects' => $options->getAllowRedirects(),
            'cert' => $options->getCert(),
            'headers' => $request->getHeaders(),
            'http_errors' => false,
            'proxy' => $options->getProxy(),
            'ssl_key' => $options->getSslKey(),
            'verify' => $options->getVerify(),
            'timeout' => $options->getTimeout(),
            'version' => $options->getVersion(),
        ];

        if (!in_array($request->getMethod(), ['HEAD', 'GET'])) {
            $opt['body'] = $request->getBody();
        }

        $sink = $options->getSink();
        if (!empty($sink)) {
            $opt['sink'] = $sink;
        }

        $response = $this->client->request($request->getMethod(), $request->getUri(), $opt);

        return new Response($response->getStatusCode(), $response->getHeaders(), $response->getBody());
    }
}
