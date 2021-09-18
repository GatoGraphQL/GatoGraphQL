<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeDataLoaders\UnionType;

use PoP\ComponentModel\RelationalTypeDataLoaders\AbstractRelationalTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

abstract class AbstractUnionTypeDataLoader extends AbstractRelationalTypeDataLoader
{
    abstract protected function getUnionTypeResolver(): UnionTypeResolverInterface;

    /**
     * Iterate through all unionTypes and delegate to each resolving the IDs each of them can resolve
     */
    public function getObjects(array $ids): array
    {
        $unionTypeResolver = $this->getUnionTypeResolver();
        $objectIDTargetTypeResolvers = $unionTypeResolver->getObjectIDTargetTypeResolvers($ids);
        // Organize all IDs by same resolverClass
        $typeResolverClassObjectIDs = [];
        foreach ($objectIDTargetTypeResolvers as $objectID => $targetTypeResolver) {
            $typeResolverClassObjectIDs[get_class($targetTypeResolver)][] = $objectID;
        }
        // Load all objects by each corresponding typeResolver
        $objects = [];
        foreach ($typeResolverClassObjectIDs as $targetTypeResolverClass => $objectIDs) {
            $targetTypeResolver = $this->instanceManager->getInstance($targetTypeResolverClass);
            $targetTypeDataLoader = $targetTypeResolver->getRelationalTypeDataLoader();
            $objects = array_merge(
                $objects,
                $targetTypeDataLoader->getObjects($objectIDs)
            );
        }
        return $objects;
    }
}
