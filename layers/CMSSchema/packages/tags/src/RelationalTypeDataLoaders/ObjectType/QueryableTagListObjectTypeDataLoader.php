<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\Tags\RelationalTypeDataLoaders\ObjectType\AbstractTagObjectTypeDataLoader;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeAPIs\QueryableTagTypeAPIInterface;

class QueryableTagListObjectTypeDataLoader extends AbstractTagObjectTypeDataLoader
{
    private ?QueryableTagTypeAPIInterface $queryableTagListTypeAPI = null;

    final protected function getQueryableTagTypeAPI(): QueryableTagTypeAPIInterface
    {
        if ($this->queryableTagListTypeAPI === null) {
            /** @var QueryableTagTypeAPIInterface */
            $queryableTagListTypeAPI = $this->instanceManager->getInstance(QueryableTagTypeAPIInterface::class);
            $this->queryableTagListTypeAPI = $queryableTagListTypeAPI;
        }
        return $this->queryableTagListTypeAPI;
    }

    public function getTagTypeAPI(): TagTypeAPIInterface
    {
        return $this->getQueryableTagTypeAPI();
    }
}
