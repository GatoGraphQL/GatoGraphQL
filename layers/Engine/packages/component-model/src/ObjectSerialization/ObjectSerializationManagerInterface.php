<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ObjectSerialization;

use stdClass;

interface ObjectSerializationManagerInterface
{
    public function addObjectSerializer(ObjectSerializerInterface $objectSerializer): void;
    public function serialize(object $object): string|int|float|bool|array|stdClass;
}
