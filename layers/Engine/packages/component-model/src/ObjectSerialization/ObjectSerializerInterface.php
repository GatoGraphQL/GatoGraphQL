<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ObjectSerialization;

use stdClass;

interface ObjectSerializerInterface
{
    public function getObjectClassToSerialize(): string;
    public function serialize(object $object): string|int|float|bool|array|stdClass;
}
