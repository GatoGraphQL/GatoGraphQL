<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagsWP\Overrides\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\Tags\Module as TagsModule;
use PoPCMSSchema\Tags\ModuleConfiguration as TagsModuleConfiguration;
use PoPCMSSchema\Tags\RelationalTypeDataLoaders\ObjectType\QueryableTagListTypeDataLoader;
use PoPCMSSchema\Tags\RelationalTypeDataLoaders\UnionType\TagUnionTypeDataLoader as UpstreamTagUnionTypeDataLoader;
use PoP\ComponentModel\App;

/**
 * Retrieve the union of tags, for different GraphQL types
 * (i.e. for different taxonomies), with a single method execution
 */
class TagUnionTypeDataLoader extends UpstreamTagUnionTypeDataLoader
{
    private ?QueryableTagListTypeDataLoader $queryableTagListTypeDataLoader = null;

    final public function setQueryableTagListTypeDataLoader(QueryableTagListTypeDataLoader $queryableTagListTypeDataLoader): void
    {
        $this->queryableTagListTypeDataLoader = $queryableTagListTypeDataLoader;
    }
    final protected function getQueryableTagListTypeDataLoader(): QueryableTagListTypeDataLoader
    {
        /** @var QueryableTagListTypeDataLoader */
        return $this->queryableTagListTypeDataLoader ??= $this->instanceManager->getInstance(QueryableTagListTypeDataLoader::class);
    }

    /**
     * @param array<string|int> $ids
     * @return array<string,mixed>
     */
    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        $query = $this->getQueryableTagListTypeDataLoader()->getQueryToRetrieveObjectsForIDs($ids);

        // From all taxonomies from the member typeResolvers
        /** @var TagsModuleConfiguration */
        $moduleConfiguration = App::getModule(TagsModule::class)->getConfiguration();
        $query['taxonomy'] = $moduleConfiguration->getQueryableTagTaxonomies();

        return $query;
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
        return $this->getQueryableTagListTypeDataLoader()->executeQuery($query);
    }
}
