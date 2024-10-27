<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagsWP\Overrides\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\Tags\Module as TagsModule;
use PoPCMSSchema\Tags\ModuleConfiguration as TagsModuleConfiguration;
use PoPCMSSchema\Tags\RelationalTypeDataLoaders\ObjectType\QueryableTagListObjectTypeDataLoader;
use PoPCMSSchema\Tags\RelationalTypeDataLoaders\UnionType\TagUnionTypeDataLoader as UpstreamTagUnionTypeDataLoader;
use PoP\ComponentModel\App;

/**
 * Retrieve the union of tags, for different GraphQL types
 * (i.e. for different taxonomies), with a single method execution
 */
class TagUnionTypeDataLoader extends UpstreamTagUnionTypeDataLoader
{
    public const HOOK_ALL_OBJECTS_BY_IDS_QUERY = __CLASS__ . ':all-objects-by-ids-query';

    private ?QueryableTagListObjectTypeDataLoader $queryableTagListObjectTypeDataLoader = null;

    final protected function getQueryableTagListObjectTypeDataLoader(): QueryableTagListObjectTypeDataLoader
    {
        if ($this->queryableTagListObjectTypeDataLoader === null) {
            /** @var QueryableTagListObjectTypeDataLoader */
            $queryableTagListObjectTypeDataLoader = $this->instanceManager->getInstance(QueryableTagListObjectTypeDataLoader::class);
            $this->queryableTagListObjectTypeDataLoader = $queryableTagListObjectTypeDataLoader;
        }
        return $this->queryableTagListObjectTypeDataLoader;
    }

    /**
     * @param array<string|int> $ids
     * @return array<string,mixed>
     */
    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        $query = $this->getQueryableTagListObjectTypeDataLoader()->getQueryToRetrieveObjectsForIDs($ids);

        // From all taxonomies from the member typeResolvers
        /** @var TagsModuleConfiguration */
        $moduleConfiguration = App::getModule(TagsModule::class)->getConfiguration();
        $query['taxonomy'] = $moduleConfiguration->getQueryableTagTaxonomies();

        return App::applyFilters(
            self::HOOK_ALL_OBJECTS_BY_IDS_QUERY,
            $query,
            $ids
        );
    }

    /**
     * Override function to execute a single call to the DB,
     * instead of one per type.
     *
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects(array $ids): array
    {
        $query = $this->getQueryToRetrieveObjectsForIDs($ids);
        return $this->getQueryableTagListObjectTypeDataLoader()->executeQuery($query);
    }
}
