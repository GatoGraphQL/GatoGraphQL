<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\TypeResolvers\ObjectType;

use PoPCMSSchema\Tags\RelationalTypeDataLoaders\ObjectType\QueryableTagListObjectTypeDataLoader;
use PoPCMSSchema\Tags\TypeAPIs\QueryableTagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

/**
 * Class to be used only when a Generic Tag Type is good enough.
 * Otherwise, a specific type for the entity should be employed.
 */
class GenericTagObjectTypeResolver extends AbstractTagObjectTypeResolver
{
    private ?QueryableTagListObjectTypeDataLoader $queryableTagListObjectTypeDataLoader = null;
    private ?QueryableTagTypeAPIInterface $queryableTagListTypeAPI = null;

    final protected function getQueryableTagListObjectTypeDataLoader(): QueryableTagListObjectTypeDataLoader
    {
        if ($this->queryableTagListObjectTypeDataLoader === null) {
            /** @var QueryableTagListObjectTypeDataLoader */
            $queryableTagListObjectTypeDataLoader = $this->instanceManager->getInstance(QueryableTagListObjectTypeDataLoader::class);
            $this->queryableTagListObjectTypeDataLoader = $queryableTagListObjectTypeDataLoader;
        }
        return $this->queryableTagListObjectTypeDataLoader;
    }
    final protected function getQueryableTagTypeAPI(): QueryableTagTypeAPIInterface
    {
        if ($this->queryableTagListTypeAPI === null) {
            /** @var QueryableTagTypeAPIInterface */
            $queryableTagListTypeAPI = $this->instanceManager->getInstance(QueryableTagTypeAPIInterface::class);
            $this->queryableTagListTypeAPI = $queryableTagListTypeAPI;
        }
        return $this->queryableTagListTypeAPI;
    }

    public function getTypeName(): string
    {
        return 'GenericTag';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('A tag that does not have its own type in the schema', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getQueryableTagListObjectTypeDataLoader();
    }

    public function getTagTypeAPI(): TagTypeAPIInterface
    {
        return $this->getQueryableTagTypeAPI();
    }
}
