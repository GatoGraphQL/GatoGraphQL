<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\ObjectSerializers;

use DateTime;
use DateTimeInterface;
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
