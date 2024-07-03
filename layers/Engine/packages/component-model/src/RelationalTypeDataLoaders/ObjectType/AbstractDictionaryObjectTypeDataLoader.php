<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\Dictionaries\ObjectDictionaryInterface;

abstract class AbstractDictionaryObjectTypeDataLoader extends AbstractObjectTypeDataLoader implements DictionaryObjectTypeDataLoaderInterface
{
    private ?ObjectDictionaryInterface $objectDictionary = null;

    final public function setObjectDictionary(ObjectDictionaryInterface $objectDictionary): void
    {
        $this->objectDictionary = $objectDictionary;
    }
    final protected function getObjectDictionary(): ObjectDictionaryInterface
    {
        if ($this->objectDictionary === null) {
            /** @var ObjectDictionaryInterface */
            $objectDictionary = $this->instanceManager->getInstance(ObjectDictionaryInterface::class);
            $this->objectDictionary = $objectDictionary;
        }
        return $this->objectDictionary;
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
}
