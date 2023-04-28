<?php

namespace App\DataObjects;

use JsonSerializable;
use stdClass;

class ImageObject implements JsonSerializable
{
    public function __construct(
        private readonly string $url,
        private readonly int $width = 300,
        private readonly int $height = 300
    ) {
    }

    public function jsonSerialize(): stdClass
    {
        $serializableObject = new stdClass();
        $serializableObject->url = $this->getUrl();
        $serializableObject->width = $this->getWidth();
        $serializableObject->height = $this->getHeight();
        return $serializableObject;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }
}
