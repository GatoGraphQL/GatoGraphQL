<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoriesWP\Overrides\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\Categories\Module as CategoriesModule;
use PoPCMSSchema\Categories\ModuleConfiguration as CategoriesModuleConfiguration;
use PoPCMSSchema\Categories\RelationalTypeDataLoaders\ObjectType\QueryableCategoryListTypeDataLoader;
use PoPCMSSchema\Categories\RelationalTypeDataLoaders\UnionType\CategoryUnionTypeDataLoader as UpstreamCategoryUnionTypeDataLoader;
use PoP\ComponentModel\App;

/**
 * Retrieve the union of categories, for different GraphQL types
 * (i.e. for different taxonomies), with a single method execution
 */
class CategoryUnionTypeDataLoader extends UpstreamCategoryUnionTypeDataLoader
{
    private ?QueryableCategoryListTypeDataLoader $queryableCategoryListTypeDataLoader = null;

    final public function setQueryableCategoryListTypeDataLoader(QueryableCategoryListTypeDataLoader $queryableCategoryListTypeDataLoader): void
    {
        $this->queryableCategoryListTypeDataLoader = $queryableCategoryListTypeDataLoader;
    }
    final protected function getQueryableCategoryListTypeDataLoader(): QueryableCategoryListTypeDataLoader
    {
        /** @var QueryableCategoryListTypeDataLoader */
        return $this->queryableCategoryListTypeDataLoader ??= $this->instanceManager->getInstance(QueryableCategoryListTypeDataLoader::class);
    }

    /**
     * @param array<string|int> $ids
     * @return array<string,mixed>
     */
    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        $query = $this->getQueryableCategoryListTypeDataLoader()->getQueryToRetrieveObjectsForIDs($ids);

        // From all taxonomies from the member typeResolvers
        /** @var CategoriesModuleConfiguration */
        $moduleConfiguration = App::getModule(CategoriesModule::class)->getConfiguration();
        $query['taxonomy'] = $moduleConfiguration->getQueryableCategoryTaxonomies();

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
        return $this->getQueryableCategoryListTypeDataLoader()->executeQuery($query);
    }
}
