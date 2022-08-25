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
     *
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects(array $ids): array
    {
        $unionTypeResolver = $this->getUnionTypeResolver();
        $objectIDTargetTypeResolvers = $unionTypeResolver->getObjectIDTargetTypeResolvers($ids);
        // Organize all IDs by same typeResolver
        $objectTypeResolverNameDataItems = [];
        foreach ($objectIDTargetTypeResolvers as $objectID => $targetObjectTypeResolver) {
            // If there's no resolver, it's an error: the ID can't be processed by anyone
            if ($targetObjectTypeResolver === null) {
                continue;
            }
            $targetObjectTypeName = $targetObjectTypeResolver->getNamespacedTypeName();
            $objectTypeResolverNameDataItems[$targetObjectTypeName] ??= [
                'targetObjectTypeResolver' => $targetObjectTypeResolver,
                'objectIDs' => [],
            ];
            $objectTypeResolverNameDataItems[$targetObjectTypeName]['objectIDs'][] = $objectID;
        }
        // Load all objects by each corresponding typeResolver
        $objects = [];
        foreach ($objectTypeResolverNameDataItems as $targetObjectTypeName => $objectTypeResolverDataItems) {
            $targetObjectTypeResolver = $objectTypeResolverDataItems['targetObjectTypeResolver'];
            $objectIDs = $objectTypeResolverDataItems['objectIDs'];
            $targetTypeDataLoader = $targetObjectTypeResolver->getRelationalTypeDataLoader();
            $objects = array_merge(
                $objects,
                $targetTypeDataLoader->getObjects($objectIDs)
            );
        }
        return $objects;
    }
}
