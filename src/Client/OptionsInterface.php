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

namespace PSX\Http\Client;

/**
 * OptionsInterface
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
interface OptionsInterface
{
    /**
     * Describes the redirect behavior of a request
     */
    public function getAllowRedirects(): ?bool;

    /**
     * Set to a string to specify the path to a file containing a PEM formatted 
     * client side certificate
     */
    public function getCert(): ?string;

    /**
     * Pass a string to specify an HTTP proxy, or an array to specify different 
     * proxies for different protocols
     */
    public function getProxy(): ?string;

    /**
     * Specify the path to a file containing a private SSL key in PEM format
     */
    public function getSslKey(): ?string;

    /**
     * Describes the SSL certificate verification behavior of a request
     */
    public function getVerify(): ?bool;

    /**
     * Float describing the timeout of the request in seconds. Use 0 to wait 
     * indefinitely (the default behavior)
     */
    public function getTimeout(): ?float;

    /**
     * Protocol version to use with the request
     */
    public function getVersion(): ?float;

    /**
     * Specify where the body of a response will be saved. Must be either a
     * path to a file or a resource
     */
    public function getSink(): mixed;
}
