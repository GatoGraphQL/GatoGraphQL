<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoriesWP\Overrides\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\Categories\Module as CategoriesModule;
use PoPCMSSchema\Categories\ModuleConfiguration as CategoriesModuleConfiguration;
use PoPCMSSchema\Categories\RelationalTypeDataLoaders\ObjectType\QueryableCategoryListObjectTypeDataLoader;
use PoPCMSSchema\Categories\RelationalTypeDataLoaders\UnionType\CategoryUnionTypeDataLoader as UpstreamCategoryUnionTypeDataLoader;
use PoP\ComponentModel\App;

/**
 * Retrieve the union of categories, for different GraphQL types
 * (i.e. for different taxonomies), with a single method execution
 */
class CategoryUnionTypeDataLoader extends UpstreamCategoryUnionTypeDataLoader
{
    public const HOOK_ALL_OBJECTS_BY_IDS_QUERY = __CLASS__ . ':all-objects-by-ids-query';

    private ?QueryableCategoryListObjectTypeDataLoader $queryableCategoryListObjectTypeDataLoader = null;

    final protected function getQueryableCategoryListObjectTypeDataLoader(): QueryableCategoryListObjectTypeDataLoader
    {
        if ($this->queryableCategoryListObjectTypeDataLoader === null) {
            /** @var QueryableCategoryListObjectTypeDataLoader */
            $queryableCategoryListObjectTypeDataLoader = $this->instanceManager->getInstance(QueryableCategoryListObjectTypeDataLoader::class);
            $this->queryableCategoryListObjectTypeDataLoader = $queryableCategoryListObjectTypeDataLoader;
        }
        return $this->queryableCategoryListObjectTypeDataLoader;
    }

    /**
     * @param array<string|int> $ids
     * @return array<string,mixed>
     */
    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        $query = $this->getQueryableCategoryListObjectTypeDataLoader()->getQueryToRetrieveObjectsForIDs($ids);

        // From all taxonomies from the member typeResolvers
        /** @var CategoriesModuleConfiguration */
        $moduleConfiguration = App::getModule(CategoriesModule::class)->getConfiguration();
        $query['taxonomy'] = $moduleConfiguration->getQueryableCategoryTaxonomies();

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
        return $this->getQueryableCategoryListObjectTypeDataLoader()->executeQuery($query);
    }
}
