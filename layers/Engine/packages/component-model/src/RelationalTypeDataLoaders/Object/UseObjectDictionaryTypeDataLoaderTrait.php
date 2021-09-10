<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\Facades\Container\ObjectDictionaryFacade;

trait UseObjectDictionaryTypeDataLoaderTrait
{
    public function getObjects(array $ids): array
    {
        $objectDictionary = ObjectDictionaryFacade::getInstance();
        $objectTypeResolverClass = $this->getObjectTypeResolverClass();
        $ret = [];
        foreach ($ids as $id) {
            if (!$objectDictionary->has($objectTypeResolverClass, $id)) {
                $objectDictionary->set($objectTypeResolverClass, $id, $this->getObjectTypeNewInstance($id));
            }
            $ret[] = $objectDictionary->get($objectTypeResolverClass, $id);
        }
        return $ret;
    }

    abstract protected function getObjectTypeResolverClass(): string;
    abstract protected function getObjectTypeNewInstance(int | string $id): mixed;
}
