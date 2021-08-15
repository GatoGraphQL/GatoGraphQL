<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostsWP\Overrides\TypeResolvers;

use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver as UpstreamCustomPostUnionTypeResolver;

class CustomPostUnionTypeResolver extends UpstreamCustomPostUnionTypeResolver
{
    /**
     * Overriding function to provide optimization:
     * instead of calling ->isIDOfType on each object (as in parent function),
     * in which case we must make a DB call for each result,
     * we obtain all the types from executing a single query against the DB
     */
    public function getResultItemIDTargetTypeResolvers(array $ids): array
    {
        $resultItemIDTargetTypeResolvers = [];
        $customPostUnionTypeDataLoader = $this->instanceManager->getInstance($this->getTypeDataLoaderClass());
        // If any ID cannot be resolved, the resultItem will be null
        if ($customPosts = array_filter($customPostUnionTypeDataLoader->getObjects($ids))) {
            foreach ($customPosts as $customPost) {
                $targetTypeResolver = $this->getTargetTypeResolver($customPost);
                if ($targetTypeResolver !== null) {
                    $resultItemIDTargetTypeResolvers[$targetTypeResolver->getID($customPost)] = $targetTypeResolver;
                }
            }
        }
        return $resultItemIDTargetTypeResolvers;
    }

    public function getIdFieldTypeResolverClass(): string
    {
        return UpstreamCustomPostUnionTypeResolver::class;
    }
}
