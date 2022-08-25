<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostsWP\Overrides\TypeResolvers\UnionType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver as UpstreamCustomPostUnionTypeResolver;
use PoPCMSSchema\SchemaCommonsWP\Overrides\TypeResolvers\OverridingTypeResolverTrait;

class CustomPostUnionTypeResolver extends UpstreamCustomPostUnionTypeResolver
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
        $customPostUnionTypeDataLoader = $this->getRelationalTypeDataLoader();
        $resolvedObjectIDs = [];
        $customPosts = array_filter($customPostUnionTypeDataLoader->getObjects($ids));
        // If any ID cannot be resolved, the object will be null
        foreach ($customPosts as $customPost) {
            $targetObjectTypeResolver = $this->getTargetObjectTypeResolver($customPost);
            if ($targetObjectTypeResolver === null) {
                continue;
            }
            $objectID = $targetObjectTypeResolver->getID($customPost);
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
}
