<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\Facades\Container\ObjectDictionaryFacade;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

trait UseObjectDictionaryTypeDataLoaderTrait
{
    public function getObjects(array $ids): array
    {
        $objectDictionary = ObjectDictionaryFacade::getInstance();
        $objectTypeResolverClass = get_class($this->getObjectTypeResolver());
        $ret = [];
        foreach ($ids as $id) {
            if (!$objectDictionary->has($objectTypeResolverClass, $id)) {
                $objectDictionary->set($objectTypeResolverClass, $id, $this->getObjectTypeNewInstance($id));
            }
            $ret[] = $objectDictionary->get($objectTypeResolverClass, $id);
        }
        return $ret;
    }

    abstract protected function getObjectTypeResolver(): ObjectTypeResolverInterface;
    abstract protected function getObjectTypeNewInstance(int | string $id): mixed;
}
