<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\Categories\RelationalTypeDataLoaders\ObjectType\AbstractCategoryTypeDataLoader;
use PoPCMSSchema\Categories\TypeAPIs\CategoryListTypeAPIInterface;
use PoPCMSSchema\Categories\TypeAPIs\QueryableCategoryTypeAPIInterface;

class QueryableCategoryListTypeDataLoader extends AbstractCategoryTypeDataLoader
{
    private ?QueryableCategoryTypeAPIInterface $queryableCategoryListTypeAPI = null;

    final public function setQueryableCategoryTypeAPI(QueryableCategoryTypeAPIInterface $queryableCategoryListTypeAPI): void
    {
        $this->queryableCategoryListTypeAPI = $queryableCategoryListTypeAPI;
    }
    final protected function getQueryableCategoryTypeAPI(): QueryableCategoryTypeAPIInterface
    {
        /** @var QueryableCategoryTypeAPIInterface */
        return $this->queryableCategoryListTypeAPI ??= $this->instanceManager->getInstance(QueryableCategoryTypeAPIInterface::class);
    }

    public function getCategoryListTypeAPI(): CategoryListTypeAPIInterface
    {
        return $this->getQueryableCategoryTypeAPI();
    }
}
