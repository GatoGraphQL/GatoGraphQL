<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\ObjectSerializers;

use DateTime;
// @todo Replace with \DateTimeInterface. See: https://github.com/leoloso/PoP/issues/1282
use PoPSchema\SchemaCommons\Polyfill\PHP72\DateTimeInterface;
use PoP\ComponentModel\ObjectSerialization\AbstractObjectSerializer;

class DateTimeObjectSerializer extends AbstractObjectSerializer
{
    public function getObjectClassToSerialize(): string
    {
        return DateTime::class;
    }
    public function serialize(object $object): string
    {
        /** @var $object DateTime */
        return $object->format(DateTimeInterface::ATOM);
    }
}
