<?php

declare(strict_types=1);

namespace PoPSchema\Tags\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeQueryableDataLoader;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Tags\TypeAPIs\TagTypeAPIInterface;

abstract class AbstractTagTypeDataLoader extends AbstractObjectTypeQueryableDataLoader
{
    abstract public function getTagTypeAPI(): TagTypeAPIInterface;

    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        return [
            'include' => $ids,
        ];
    }

    protected function getOrderbyDefault()
    {
        return $this->getNameResolver()->getName('popcms:dbcolumn:orderby:tags:count');
    }

    protected function getOrderDefault()
    {
        return 'DESC';
    }

    public function executeQuery($query, array $options = []): array
    {
        $tagTypeAPI = $this->getTagTypeAPI();
        return $tagTypeAPI->getTags($query, $options);
    }

    public function executeQueryIDs($query): array
    {
        $options = [
            QueryOptions::RETURN_TYPE => ReturnTypes::IDS,
        ];
        return $this->executeQuery($query, $options);
    }
}
