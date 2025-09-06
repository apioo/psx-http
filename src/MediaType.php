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

namespace PSX\Http;

use InvalidArgumentException;

/**
 * MediaType
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class MediaType implements \JsonSerializable, \Stringable
{
    protected const TOP_LEVEL_MEDIA_TYPES = [
        'application',
        'audio',
        'example',
        'image',
        'message',
        'model',
        'multipart',
        'text',
        'video',
    ];

    private string $type;
    private string $subType;
    private array $parameters;
    private float $quality = 1;

    private function __construct(string $type, string $subType, array $parameters = [])
    {
        $this->type = $type;
        $this->subType = $subType;
        $this->parameters = $parameters;

        if (isset($parameters['q'])) {
            $this->quality = $this->parseQuality($parameters['q']);
        }
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSubType(): string
    {
        return $this->subType;
    }

    public function getName(): string
    {
        return $this->type . '/' . $this->subType;
    }

    public function getQuality(): ?float
    {
        return $this->quality;
    }

    public function getParameter(string $name): ?string
    {
        return $this->parameters[$name] ?? null;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function toString(): string
    {
        $mediaType = $this->getName();

        if (!empty($this->parameters)) {
            $arguments = array();
            foreach ($this->parameters as $key => $value) {
                $arguments[] = $key . '=' . $value;
            }

            $mediaType.= '; ' . implode('; ', $arguments);
        }

        return $mediaType;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function jsonSerialize(): string
    {
        return $this->toString();
    }

    /**
     * Checks whether the given media type would match
     */
    public function match(MediaType $mediaType): bool
    {
        return ($this->type == '*' && $this->subType == '*') ||
            ($this->type == $mediaType->getType() && $this->subType == $mediaType->getSubType()) ||
            ($this->type == $mediaType->getType() && $this->subType == '*');
    }

    private function parseQuality(mixed $quality): float
    {
        if (!empty($quality)) {
            $q = (float) $quality;

            if ($q >= 0 && $q <= 1) {
                return $q;
            }
        }

        return 1;
    }

    public static function of(string $type, string $subType, array $parameters = []): self
    {
        return new self($type, $subType, $parameters);
    }

    public static function parse(string $mime): self
    {
        $result = preg_match('/^' . self::getPattern() . '$/i', $mime, $matches);
        if (!$result) {
            throw new InvalidArgumentException('Invalid media type given');
        }

        $type = isset($matches[1]) ? strtolower($matches[1]) : null;
        $subType = isset($matches[2]) ? strtolower($matches[2]) : null;

        if (empty($type) || empty($subType)) {
            throw new InvalidArgumentException('Invalid media type given');
        }

        if ($type != '*' && !in_array($type, self::TOP_LEVEL_MEDIA_TYPES)) {
            throw new InvalidArgumentException('Invalid media type given');
        }

        $rest = $matches[3] ?? null;
        $parameters = [];

        if (!empty($rest)) {
            $parts = explode(';', $rest);
            foreach ($parts as $part) {
                $kv    = explode('=', $part, 2);
                $key   = trim($kv[0]);
                $value = isset($kv[1]) ? trim($kv[1]) : '';

                if (!empty($key)) {
                    $parameters[$key] = trim($value, '"');
                }
            }
        }

        return new self($type, $subType, $parameters);
    }

    public static function parseList(string $mimeList): array
    {
        $types  = explode(',', $mimeList);
        $result = array();

        $sortQuality = array();
        $sortIndex   = array();

        foreach ($types as $key => $mime) {
            try {
                $mediaType = self::parse(trim($mime));

                $sortQuality[] = $mediaType->getQuality();
                $sortIndex[]   = $key;

                $result[] = $mediaType;
            } catch (InvalidArgumentException $e) {
            }
        }

        array_multisort($sortQuality, SORT_DESC, $sortIndex, SORT_ASC, $result);

        return $result;
    }

    public static function getPattern(): string
    {
        return '([A-z]+|x-[A-z\-\_]+|\*)\/([A-z0-9\-\_\.\+]+|\*);?\s?(.*)';
    }
}
