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

namespace PSX\Http\Stream;

/**
 * Stream which contains multiple streams from an multipart upload
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class MultipartStream extends StringStream implements \Countable, \IteratorAggregate
{
    /**
     * @var array
     */
    protected $parts;

    /**
     * @param array $files
     * @param array $post
     */
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

    /**
     * @param string $name
     * @return \PSX\Http\Stream\FileStream|string|null
     */
    public function getPart($name)
    {
        return $this->parts[$name] ?? null;
    }

    /**
     * @return integer
     */
    public function count()
    {
        return count($this->parts);
    }

    /**
     * @return \Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->parts);
    }
}
