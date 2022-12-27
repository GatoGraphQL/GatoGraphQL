<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\TypeResolvers\ObjectType;

use PoPCMSSchema\Categories\RelationalTypeDataLoaders\ObjectType\QueryableCategoryListTypeDataLoader;
use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\Categories\TypeAPIs\QueryableCategoryTypeAPIInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

/**
 * Class to be used only when a Generic Category Type is good enough.
 * Otherwise, a specific type for the entity should be employed.
 */
class GenericCategoryObjectTypeResolver extends AbstractCategoryObjectTypeResolver
{
    private ?QueryableCategoryListTypeDataLoader $queryableCategoryListTypeDataLoader = null;
    private ?QueryableCategoryTypeAPIInterface $queryableCategoryListTypeAPI = null;

    final public function setQueryableCategoryListTypeDataLoader(QueryableCategoryListTypeDataLoader $queryableCategoryListTypeDataLoader): void
    {
        $this->queryableCategoryListTypeDataLoader = $queryableCategoryListTypeDataLoader;
    }
    final protected function getQueryableCategoryListTypeDataLoader(): QueryableCategoryListTypeDataLoader
    {
        /** @var QueryableCategoryListTypeDataLoader */
        return $this->queryableCategoryListTypeDataLoader ??= $this->instanceManager->getInstance(QueryableCategoryListTypeDataLoader::class);
    }
    final public function setQueryableCategoryTypeAPI(QueryableCategoryTypeAPIInterface $queryableCategoryListTypeAPI): void
    {
        $this->queryableCategoryListTypeAPI = $queryableCategoryListTypeAPI;
    }
    final protected function getQueryableCategoryTypeAPI(): QueryableCategoryTypeAPIInterface
    {
        /** @var QueryableCategoryTypeAPIInterface */
        return $this->queryableCategoryListTypeAPI ??= $this->instanceManager->getInstance(QueryableCategoryTypeAPIInterface::class);
    }

    public function getTypeName(): string
    {
        return 'GenericCategory';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('A category that does not have its own type in the schema', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getQueryableCategoryListTypeDataLoader();
    }

    public function getCategoryTypeAPI(): CategoryTypeAPIInterface
    {
        return $this->getQueryableCategoryTypeAPI();
    }
}
