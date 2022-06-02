<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\Container\ObjectDictionaryInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

trait UseObjectDictionaryTypeDataLoaderTrait
{
    abstract protected function getObjectDictionary(): ObjectDictionaryInterface;

    /**
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects(array $ids): array
    {
        $objectTypeResolverClass = get_class($this->getObjectTypeResolver());
        $objects = [];
        foreach ($ids as $id) {
            if (!$this->getObjectDictionary()->has($objectTypeResolverClass, $id)) {
                $this->getObjectDictionary()->set($objectTypeResolverClass, $id, $this->getObjectTypeNewInstance($id));
            }
            $objects[] = $this->getObjectDictionary()->get($objectTypeResolverClass, $id);
        }
        return $objects;
    }

    abstract protected function getObjectTypeResolver(): ObjectTypeResolverInterface;
    abstract protected function getObjectTypeNewInstance(int | string $id): mixed;
}
