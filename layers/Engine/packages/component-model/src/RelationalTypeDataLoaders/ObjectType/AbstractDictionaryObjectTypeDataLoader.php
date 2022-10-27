<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\Container\ObjectDictionaryInterface;

abstract class AbstractDictionaryObjectTypeDataLoader extends AbstractObjectTypeDataLoader
{
    private ?ObjectDictionaryInterface $objectDictionary = null;

    final public function setObjectDictionary(ObjectDictionaryInterface $objectDictionary): void
    {
        $this->objectDictionary = $objectDictionary;
    }
    final protected function getObjectDictionary(): ObjectDictionaryInterface
    {
        /** @var ObjectDictionaryInterface */
        return $this->objectDictionary ??= $this->instanceManager->getInstance(ObjectDictionaryInterface::class);
    }

    /**
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects(array $ids): array
    {
        $objectClass = $this->getObjectClass();
        $objectDictionary = $this->getObjectDictionary();
        return array_map(
            fn (string|int $id) => $objectDictionary->get($objectClass, $id),
            $ids
        );
    }

    abstract protected function getObjectClass(): string;
}
