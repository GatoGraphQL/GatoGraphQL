<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\Dictionaries\ObjectDictionaryInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractUseObjectDictionaryObjectTypeDataLoader extends AbstractObjectTypeDataLoader
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

    /**
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects(array $ids): array
    {
        $objectDictionary = $this->getObjectDictionary();
        $objectTypeResolverClass = get_class($this->getObjectTypeResolver());
        $objects = [];
        foreach ($ids as $id) {
            if (!$objectDictionary->has($objectTypeResolverClass, $id)) {
                $objectDictionary->set($objectTypeResolverClass, $id, $this->getObjectTypeNewInstance($id));
            }
            $objects[] = $objectDictionary->get($objectTypeResolverClass, $id);
        }
        return $objects;
    }

    abstract protected function getObjectTypeResolver(): ObjectTypeResolverInterface;
    abstract protected function getObjectTypeNewInstance(int|string $id): mixed;
}
