<?php

namespace App\Lib\ORMType;

use App\DataObjects\ImageObject;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use JsonException;

class ImageCollectionType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return Types::JSON;
    }


    /**
     * @param $value
     * @param AbstractPlatform $platform
     * @return ArrayCollection<int, ImageObject>
     * @throws JsonException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ArrayCollection
    {
        $phpValue = new ArrayCollection();
        $values = json_decode($value, true, 512, JSON_OBJECT_AS_ARRAY | JSON_THROW_ON_ERROR);
        foreach ($values as $imageObjectValue) {
            $phpValue->add(
                new ImageObject(
                    $imageObjectValue['url'],
                    $imageObjectValue['width'],
                    $imageObjectValue['height']
                )
            );
        }
        return $phpValue;
    }

    /**
     * @param ImageObject $value
     * @param AbstractPlatform $platform
     * @return string
     * @throws JsonException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        assert($value instanceof ArrayCollection);
        return json_encode($value->toArray(), JSON_OBJECT_AS_ARRAY | JSON_THROW_ON_ERROR);
    }

    public function getName(): string
    {
        return 'ImageObjectCollection';
    }
}
