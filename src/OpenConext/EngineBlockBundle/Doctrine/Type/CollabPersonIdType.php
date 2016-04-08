<?php

namespace OpenConext\EngineBlockBundle\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use OpenConext\EngineBlock\Authentication\Value\CollabPersonId;
use OpenConext\EngineBlock\Exception\InvalidArgumentException;

class CollabPersonIdType extends Type
{
    const NAME = 'engineblock_collab_person_id';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return $value;
        }

        if (!$value instanceof CollabPersonId) {
            throw new ConversionException(sprintf(
                'Value %s must be null or an instance of CollabPersonId to be able to convert it to a database value',
                is_object($value) ? get_class($value) : (string) $value
            ));
        }

        return $value->getCollabPersonId();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return $value;
        }

        try {
            $entityId = new CollabPersonId($value);
        } catch (InvalidArgumentException $e) {
            // get nice standard message, so we can throw it keeping the exception chain
            $doctrineExceptionMessage = ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                'a valid CollabPersonId'
            )->getMessage();

            throw new ConversionException($doctrineExceptionMessage, 0, $e);
        }

        return $entityId;
    }

    public function getName()
    {
        return self::NAME;
    }
}
