<?php

declare(strict_types=1);

namespace PoPSchema\Categories\TypeDataLoaders;

use PoPSchema\Categories\ComponentContracts\CategoryAPIRequestedContractTrait;
use PoP\ComponentModel\TypeDataLoaders\AbstractTypeQueryableDataLoader;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions;

abstract class AbstractCategoryTypeDataLoader extends AbstractTypeQueryableDataLoader
{
    use CategoryAPIRequestedContractTrait;

    public function getObjects(array $ids): array
    {
        $query = array(
            'include' => $ids
        );
        $categoryTypeAPI = $this->getTypeAPI();
        return $categoryTypeAPI->getCategories($query);
    }

    public function getDataFromIdsQuery(array $ids): array
    {
        $query = array(
            'include' => $ids
        );
        return $query;
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
        $categoryTypeAPI = $this->getTypeAPI();
        return $categoryTypeAPI->getCategories($query, $options);
    }

    public function executeQueryIds($query): array
    {
        // $query['fields'] = 'ids';
        $options = [
            QueryOptions::RETURN_TYPE => ReturnTypes::IDS,
        ];
        return $this->executeQuery($query, $options);
    }
}
