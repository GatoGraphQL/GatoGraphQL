<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\Overrides\TypeResolvers;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

trait OverridingUnionTypeResolverTrait
{
    use OverridingTypeResolverTrait;

    /**
     * Overriding function to provide optimization:
     * instead of calling ->isIDOfType on each object (as in parent function),
     * in which case we must make a DB call for each result,
     * we obtain all the types from executing a single query against the DB.
     *
     * @param array<string|int> $ids
     * @return array<string|int,ObjectTypeResolverInterface|null>
     */
    public function getObjectIDTargetTypeResolvers(array $ids): array
    {
        $objectIDTargetTypeResolvers = [];
        /**
         * We retrieve the original service, which must also be
         * overridden with a new service for WP
         */
        $unionTypeDataLoader = $this->getRelationalTypeDataLoader();
        $resolvedObjectIDs = [];
        $objects = array_filter($unionTypeDataLoader->getObjects($ids));
        // If any ID cannot be resolved, the object will be null
        foreach ($objects as $object) {
            $targetObjectTypeResolver = $this->getTargetObjectTypeResolver($object);
            if ($targetObjectTypeResolver === null) {
                continue;
            }
            $objectID = $targetObjectTypeResolver->getID($object);
            $resolvedObjectIDs[] = $objectID;
            $objectIDTargetTypeResolvers[$objectID] = $targetObjectTypeResolver;
        }
        /**
         * Set all the unresolved IDs to null
         */
        foreach (array_diff($ids, $resolvedObjectIDs) as $unresolvedObjectID) {
            $objectIDTargetTypeResolvers[$unresolvedObjectID] = null;
        }
        return $objectIDTargetTypeResolvers;
    }

    abstract public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface;

    abstract public function getTargetObjectTypeResolver(object $object): ?ObjectTypeResolverInterface;
}
