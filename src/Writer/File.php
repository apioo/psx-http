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

namespace PSX\Http\Writer;

use PSX\Http\ResponseInterface;

/**
 * File
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class File extends Writer
{
    protected ?string $fileName;

    public function __construct(string $file, ?string $fileName = null, ?string $contentType = null)
    {
        parent::__construct($file, $contentType);

        $this->fileName = $fileName;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function writeTo(ResponseInterface $response): void
    {
        $file = $this->data;

        $fileName = $this->fileName;
        if (empty($fileName)) {
            $fileName = \pathinfo($file, PATHINFO_FILENAME);
        }

        $contentType = $this->contentType;
        if ($contentType === null && function_exists('mime_content_type')) {
            $contentType = (string) \mime_content_type($file);
        }

        $response->setHeader('Content-Type', $contentType ?? '');
        $response->setHeader('Content-Disposition', 'attachment; filename="' . addcslashes($fileName, '"') . '"');
        $response->getBody()->write((string) file_get_contents($file));
    }
}
