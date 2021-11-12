<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ObjectSerialization;

interface ObjectSerializerInterface
{
    public function getObjectClassToSerialize(): string;
    public function serialize(object $object): string;
}
