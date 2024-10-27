<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ObjectTypeResolverPickers;

use PoP\ComponentModel\Dictionaries\ObjectDictionaryInterface;

abstract class AbstractTransientObjectObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker
{
    private ?ObjectDictionaryInterface $objectDictionary = null;

    final protected function getObjectDictionary(): ObjectDictionaryInterface
    {
        if ($this->objectDictionary === null) {
            /** @var ObjectDictionaryInterface */
            $objectDictionary = $this->instanceManager->getInstance(ObjectDictionaryInterface::class);
            $this->objectDictionary = $objectDictionary;
        }
        return $this->objectDictionary;
    }

    final public function isInstanceOfType(object $object): bool
    {
        return is_a($object, $this->getTargetObjectClass(), true);
    }

    abstract protected function getTargetObjectClass(): string;

    final public function isIDOfType(string|int $objectID): bool
    {
        $transientObject = $this->getObjectDictionary()->get($this->getTargetObjectClass(), $objectID);
        if ($transientObject === null) {
            return false;
        }
        return $this->isInstanceOfType($transientObject);
    }
}
