<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ObjectSerialization;

interface ObjectSerializationManagerInterface
{
    public function addObjectSerializer(ObjectSerializerInterface $objectSerializer): void;
    public function serialize(object $object): string;
}
