<?php

declare(strict_types=1);

namespace PoPSchema\Categories\RelationalTypeDataLoaders\ObjectType;

use PoPSchema\Categories\ComponentContracts\CategoryAPIRequestedContractInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeQueryableDataLoader;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions;

abstract class AbstractCategoryTypeDataLoader extends AbstractObjectTypeQueryableDataLoader implements CategoryAPIRequestedContractInterface
{
    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        return [
            'include' => $ids,
        ];
    }

    protected function getOrderbyDefault()
    {
        return $this->nameResolver->getName('popcms:dbcolumn:orderby:categories:count');
    }

    protected function getOrderDefault()
    {
        return 'DESC';
    }

    public function executeQuery($query, array $options = []): array
    {
        $categoryTypeAPI = $this->getCategoryTypeAPI();
        return $categoryTypeAPI->getCategories($query, $options);
    }

    public function executeQueryIDs($query): array
    {
        $options = [
            QueryOptions::RETURN_TYPE => ReturnTypes::IDS,
        ];
        return $this->executeQuery($query, $options);
    }
}
