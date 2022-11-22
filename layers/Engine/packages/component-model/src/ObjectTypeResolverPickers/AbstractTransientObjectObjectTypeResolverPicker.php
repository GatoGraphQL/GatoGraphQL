<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ObjectTypeResolverPickers;

abstract class AbstractTransientObjectObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker
{
    final public function isInstanceOfType(object $object): bool
    {
        return is_a($object, $this->getTargetObjectClass(), true);
    }

    abstract protected function getTargetObjectClass(): string;

    final public function isIDOfType(string|int $objectID): bool
    {
        // @todo Retrieve type from registry!!!!
        return false;
    }
}
