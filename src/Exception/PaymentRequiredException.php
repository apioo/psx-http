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
 * The HTTP 402 Payment Required is a nonstandard response status code that is reserved for future use. This status code
 * was created to enable digital cash or (micro) payment systems and would indicate that the requested content is not
 * available until the client makes a payment.
 *
 * Sometimes, this status code indicates that the request cannot be processed until the client makes a payment. However,
 * no standard use convention exists and different entities use it in different contexts.
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class PaymentRequiredException extends ClientErrorException
{
    public function __construct(string $message, \Throwable $previous = null)
    {
        parent::__construct($message, 402, $previous);
    }
}
