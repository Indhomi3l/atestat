<?php

namespace App\Lib\ORMType;

use App\DataObjects\ImageObject;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use JsonException;

class ImageObjectType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return Types::JSON;
    }

    /**
     * @throws JsonException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ImageObject
    {
        [$url, $width, $height] = json_decode($value, true, 512, JSON_OBJECT_AS_ARRAY|JSON_THROW_ON_ERROR);
        return new ImageObject($url, $width, $height);
    }

    /**
     * @param ImageObject $value
     * @param AbstractPlatform $platform
     * @return string
     * @throws JsonException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        assert($value instanceof ImageObject);
        return json_encode([
            'url' => $value->getUrl(),
            'width' => $value->getWidth(),
            'height' => $value->getHeight()
        ], JSON_OBJECT_AS_ARRAY|JSON_THROW_ON_ERROR);
    }

    public function getName(): string
    {
        return 'ImageObject';
    }
}
