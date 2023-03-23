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

namespace PSX\Http\Server;

use PSX\Http\Request;
use PSX\Http\RequestInterface;
use PSX\Http\Stream\BufferedStream;
use PSX\Http\Stream\LazyStream;
use PSX\Http\Stream\MultipartStream;
use PSX\Uri\Uri;

/**
 * RequestFactory
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class RequestFactory implements RequestFactoryInterface
{
    private ?string $baseUri;
    private array $server;

    public function __construct(?string $baseUri = null, ?array $server = null)
    {
        $this->baseUri = $baseUri;
        $this->server  = $server === null ? $_SERVER : $server;
    }

    public function createRequest(): RequestInterface
    {
        $https  = isset($this->server['HTTPS']) ? strtolower($this->server['HTTPS']) : null;
        $scheme = !empty($https) && $https != 'off' ? 'https' : 'http';
        $host   = $this->server['SERVER_NAME'] ?? null;
        $query  = null;

        if (isset($this->server['REQUEST_URI'])) {
            $path = $this->server['REQUEST_URI'];
            $path = str_replace(['index.php/', 'index.php'], '', $path);

            // remove fragment
            if (($pos = strpos($path, '#')) !== false) {
                $path = substr($path, 0, $pos);
            }

            // remove query
            if (($pos = strpos($path, '?')) !== false) {
                $query = substr($path, $pos + 1);
                $path  = substr($path, 0, $pos);
            }

            // skip base path
            if (!empty($this->baseUri)) {
                $basePath = parse_url($this->baseUri, PHP_URL_PATH);
                if (!empty($basePath)) {
                    $path = $this->skip($path, $basePath);
                    $path = '/' . ltrim($path, '/');
                }
            }

            if (empty($path)) {
                $path = '/';
            }
        } else {
            $path = '/';
        }

        // create request
        $uri     = Uri::of($scheme, $host, $path, $query, '');
        $method  = $this->getRequestMethod();
        $headers = $this->getRequestHeaders();
        $body    = null;

        // create body
        if (in_array($method, ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            if ($method == 'POST' && !empty($_FILES) && isset($headers['CONTENT-TYPE']) && str_starts_with($headers['CONTENT-TYPE'], 'multipart/form-data')) {
                // in case of file uploads use multipart stream
                $body = new MultipartStream($_FILES, $_POST);
            } else {
                // use lazy stream to open the stream only and usage and buffer the
                // response to read multiple times from the same stream
                $body = new BufferedStream(new LazyStream('php://input'));
            }
        }

        $request = new Request($uri, $method, $headers, $body);

        // set specific request attributes
        $keys = ['SERVER_ADDR', 'SERVER_NAME', 'REQUEST_TIME', 'REQUEST_TIME_FLOAT', 'DOCUMENT_ROOT', 'HTTPS', 'REMOTE_ADDR', 'REMOTE_PORT'];
        foreach ($keys as $key) {
            if (isset($this->server[$key])) {
                $request->setAttribute($key, $this->server[$key]);
            }
        }

        return $request;
    }

    /**
     * Tries to detect the current request method. It considers the
     * X-HTTP-METHOD-OVERRIDE header.
     */
    protected function getRequestMethod(): string
    {
        if (isset($this->server['REQUEST_METHOD'])) {
            // check for X-HTTP-Method-Override
            if (isset($this->server['HTTP_X_HTTP_METHOD_OVERRIDE']) && in_array($this->server['HTTP_X_HTTP_METHOD_OVERRIDE'], ['OPTIONS', 'GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'PATCH'])) {
                return $this->server['HTTP_X_HTTP_METHOD_OVERRIDE'];
            } else {
                return $this->server['REQUEST_METHOD'];
            }
        } else {
            return 'GET';
        }
    }

    /**
     * Returns all request headers
     */
    protected function getRequestHeaders(): array
    {
        $contentKeys = array('CONTENT_LENGTH' => true, 'CONTENT_MD5' => true, 'CONTENT_TYPE' => true);
        $headers     = array();

        foreach ($this->server as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $headers[str_replace('_', '-', substr($key, 5))] = $value;
            } elseif (isset($contentKeys[$key])) {
                $headers[str_replace('_', '-', $key)] = $value;
            }
        }

        if (!isset($headers['AUTHORIZATION'])) {
            if (isset($this->server['REDIRECT_HTTP_AUTHORIZATION'])) {
                $headers['AUTHORIZATION'] = $this->server['REDIRECT_HTTP_AUTHORIZATION'];
            } elseif (isset($this->server['PHP_AUTH_USER'])) {
                $headers['AUTHORIZATION'] = 'Basic ' . base64_encode($this->server['PHP_AUTH_USER'] . ':' . (isset($this->server['PHP_AUTH_PW']) ? $this->server['PHP_AUTH_PW'] : ''));
            } elseif (isset($this->server['PHP_AUTH_DIGEST'])) {
                $headers['AUTHORIZATION'] = $this->server['PHP_AUTH_DIGEST'];
            }
        }

        return $headers;
    }

    /**
     * Removes the given $skipPath from the $srcPath as long as they have the
     * same value
     */
    protected function skip(string $srcPath, string $skipPath): string
    {
        $len = strlen($srcPath);

        for ($i = 0; $i < $len; $i++) {
            if (!isset($skipPath[$i]) || $skipPath[$i] != $srcPath[$i]) {
                break;
            }
        }

        return substr($srcPath, $i);
    }
}
