<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostsWP\Overrides\TypeResolvers;

use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoPSchema\CustomPostsWP\Overrides\TypeDataLoaders\CustomPostUnionTypeDataLoader;

class CustomPostUnionTypeResolver extends \PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver
{
    public function getTypeDataLoaderClass(): string
    {
        return CustomPostUnionTypeDataLoader::class;
    }

    /**
     * Overriding function to provide optimization:
     * instead of calling ->isIDOfType on each object (as in parent function),
     * in which case we must make a DB call for each result,
     * we obtain all the types from executing a single query against the DB
     *
     * @param array $ids
     * @return array
     */
    public function getResultItemIDTargetTypeResolvers(array $ids): array
    {
        $resultItemIDTargetTypeResolvers = [];
        $instanceManager = InstanceManagerFacade::getInstance();
        $customPostUnionTypeDataLoader = $instanceManager->getInstance($this->getTypeDataLoaderClass());
        if ($customPosts = $customPostUnionTypeDataLoader->getObjects($ids)) {
            foreach ($customPosts as $customPost) {
                $targetTypeResolver = $this->getTargetTypeResolver($customPost);
                if (!is_null($targetTypeResolver)) {
                    $resultItemIDTargetTypeResolvers[$targetTypeResolver->getID($customPost)] = $targetTypeResolver;
                }
            }
        }
        return $resultItemIDTargetTypeResolvers;
    }
}
