<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\Tags\RelationalTypeDataLoaders\ObjectType\AbstractTagObjectTypeDataLoader;
use PoPCMSSchema\Tags\TypeAPIs\TagListTypeAPIInterface;
use PoPCMSSchema\Tags\TypeAPIs\QueryableTagTypeAPIInterface;

class QueryableTagListTypeDataLoader extends AbstractTagObjectTypeDataLoader
{
    private ?QueryableTagTypeAPIInterface $queryableTagListTypeAPI = null;

    final public function setQueryableTagTypeAPI(QueryableTagTypeAPIInterface $queryableTagListTypeAPI): void
    {
        $this->queryableTagListTypeAPI = $queryableTagListTypeAPI;
    }
    final protected function getQueryableTagTypeAPI(): QueryableTagTypeAPIInterface
    {
        /** @var QueryableTagTypeAPIInterface */
        return $this->queryableTagListTypeAPI ??= $this->instanceManager->getInstance(QueryableTagTypeAPIInterface::class);
    }

    public function getTagListTypeAPI(): TagListTypeAPIInterface
    {
        return $this->getQueryableTagTypeAPI();
    }
}
