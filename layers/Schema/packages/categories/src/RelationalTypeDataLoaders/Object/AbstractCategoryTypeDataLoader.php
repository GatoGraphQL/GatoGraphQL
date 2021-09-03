<?php

declare(strict_types=1);

namespace PoPSchema\Categories\RelationalTypeDataLoaders\Object;

use PoPSchema\Categories\ComponentContracts\CategoryAPIRequestedContractTrait;
use PoP\ComponentModel\RelationalTypeDataLoaders\Object\AbstractObjectTypeQueryableDataLoader;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions;

abstract class AbstractCategoryTypeDataLoader extends AbstractObjectTypeQueryableDataLoader
{
    use CategoryAPIRequestedContractTrait;

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
