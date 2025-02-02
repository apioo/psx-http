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
 * Options
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class Options implements OptionsInterface
{
    private ?bool $allowRedirects = null;
    private ?string $cert = null;
    private ?string $proxy = null;
    private ?string $sslKey = null;
    private ?bool $verify = null;
    private ?float $timeout = null;
    private ?float $version = null;
    private mixed $sink = null;

    public function getAllowRedirects(): ?bool
    {
        return $this->allowRedirects;
    }

    public function setAllowRedirects(?bool $allowRedirects)
    {
        $this->allowRedirects = $allowRedirects;
    }

    public function getCert(): ?string
    {
        return $this->cert;
    }

    public function setCert(?string $cert)
    {
        $this->cert = $cert;
    }

    public function getProxy(): ?string
    {
        return $this->proxy;
    }

    public function setProxy(?string $proxy)
    {
        $this->proxy = $proxy;
    }

    public function getSslKey(): ?string
    {
        return $this->sslKey;
    }

    public function setSslKey(?string $sslKey)
    {
        $this->sslKey = $sslKey;
    }

    public function getVerify(): ?bool
    {
        return $this->verify;
    }

    public function setVerify(?bool $verify)
    {
        $this->verify = $verify;
    }

    public function getTimeout(): ?float
    {
        return $this->timeout;
    }

    public function setTimeout(?float $timeout)
    {
        $this->timeout = $timeout;
    }

    public function getVersion(): ?float
    {
        return $this->version;
    }

    public function setVersion(?float $version)
    {
        $this->version = $version;
    }

    public function getSink(): mixed
    {
        return $this->sink;
    }

    public function setSink(mixed $sink): void
    {
        $this->sink = $sink;
    }
}
