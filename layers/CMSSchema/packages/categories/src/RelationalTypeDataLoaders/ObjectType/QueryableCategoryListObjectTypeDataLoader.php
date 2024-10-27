<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\Categories\RelationalTypeDataLoaders\ObjectType\AbstractCategoryObjectTypeDataLoader;
use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\Categories\TypeAPIs\QueryableCategoryTypeAPIInterface;

class QueryableCategoryListObjectTypeDataLoader extends AbstractCategoryObjectTypeDataLoader
{
    private ?QueryableCategoryTypeAPIInterface $queryableCategoryListTypeAPI = null;

    final protected function getQueryableCategoryTypeAPI(): QueryableCategoryTypeAPIInterface
    {
        if ($this->queryableCategoryListTypeAPI === null) {
            /** @var QueryableCategoryTypeAPIInterface */
            $queryableCategoryListTypeAPI = $this->instanceManager->getInstance(QueryableCategoryTypeAPIInterface::class);
            $this->queryableCategoryListTypeAPI = $queryableCategoryListTypeAPI;
        }
        return $this->queryableCategoryListTypeAPI;
    }

    public function getCategoryTypeAPI(): CategoryTypeAPIInterface
    {
        return $this->getQueryableCategoryTypeAPI();
    }
}
