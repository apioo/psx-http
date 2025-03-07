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

namespace PSX\Http\Stream;

/**
 * Stream which contains multiple streams from a multipart upload
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 *
 * @implements \IteratorAggregate<FileStream|string>
 */
class MultipartStream extends StringStream implements \Countable, \IteratorAggregate
{
    private array $parts;

    public function __construct(array $files, array $post)
    {
        parent::__construct('');

        $this->parts = [];

        foreach ($files as $name => $file) {
            if (isset($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {
                $this->parts[$name] = new FileStream(
                    new LazyStream($file['tmp_name'], 'rb'),
                    $file['tmp_name'],
                    $file['name'],
                    $file['type'],
                    $file['size'],
                    $file['error']
                );
            }
        }

        foreach ($post as $name => $value) {
            if (!isset($this->parts[$name])) {
                $this->parts[$name] = $value;
            }
        }
    }

    public function getPart(string $name): FileStream|string|null
    {
        return $this->parts[$name] ?? null;
    }

    public function count(): int
    {
        return count($this->parts);
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->parts);
    }
}
