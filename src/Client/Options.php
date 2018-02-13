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

/**
 * Options
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class Options implements OptionsInterface
{
    /**
     * @var boolean
     */
    protected $allowRedirects;

    /**
     * @var string
     */
    protected $cert;

    /**
     * @var string
     */
    protected $proxy;

    /**
     * @var string
     */
    protected $sslKey;

    /**
     * @var boolean
     */
    protected $verify;

    /**
     * @var float
     */
    protected $timeout;

    /**
     * @var float
     */
    protected $version;

    /**
     * @inheritdoc
     */
    public function getAllowRedirects()
    {
        return $this->allowRedirects;
    }

    /**
     * @param boolean $allowRedirects
     */
    public function setAllowRedirects($allowRedirects)
    {
        $this->allowRedirects = $allowRedirects;
    }

    /**
     * @inheritdoc
     */
    public function getCert()
    {
        return $this->cert;
    }

    /**
     * @param string $cert
     */
    public function setCert($cert)
    {
        $this->cert = $cert;
    }

    /**
     * @inheritdoc
     */
    public function getProxy()
    {
        return $this->proxy;
    }

    /**
     * @param string $proxy
     */
    public function setProxy($proxy)
    {
        $this->proxy = $proxy;
    }

    /**
     * @inheritdoc
     */
    public function getSslKey()
    {
        return $this->sslKey;
    }

    /**
     * @param string $sslKey
     */
    public function setSslKey($sslKey)
    {
        $this->sslKey = $sslKey;
    }

    /**
     * @inheritdoc
     */
    public function getVerify()
    {
        return $this->verify;
    }

    /**
     * @param boolean $verify
     */
    public function setVerify($verify)
    {
        $this->verify = $verify;
    }

    /**
     * @inheritdoc
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param float $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * @inheritdoc
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param float $version
     */
    public function setVersion(float $version)
    {
        $this->version = $version;
    }
}
