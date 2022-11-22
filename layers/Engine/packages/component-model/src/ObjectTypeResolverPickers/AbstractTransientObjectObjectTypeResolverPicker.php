<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ObjectTypeResolverPickers;

use PoP\ComponentModel\Registries\TransientObjectRegistryInterface;

abstract class AbstractTransientObjectObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker
{
    private ?TransientObjectRegistryInterface $transientObjectRegistry = null;

    final public function setTransientObjectRegistry(TransientObjectRegistryInterface $transientObjectRegistry): void
    {
        $this->transientObjectRegistry = $transientObjectRegistry;
    }
    final protected function getTransientObjectRegistry(): TransientObjectRegistryInterface
    {
        /** @var TransientObjectRegistryInterface */
        return $this->transientObjectRegistry ??= $this->instanceManager->getInstance(TransientObjectRegistryInterface::class);
    }

    final public function isInstanceOfType(object $object): bool
    {
        return is_a($object, $this->getTargetObjectClass(), true);
    }

    abstract protected function getTargetObjectClass(): string;

    final public function isIDOfType(string|int $objectID): bool
    {
        $transientObject = $this->getTransientObjectRegistry()->getTransientObject($objectID);
        if ($transientObject === null) {
            return false;
        }
        return $this->isInstanceOfType($transientObject);
    }
}
