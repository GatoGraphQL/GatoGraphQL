<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeQueryableDataLoader;
use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;

abstract class AbstractCategoryTypeDataLoader extends AbstractObjectTypeQueryableDataLoader
{
    abstract public function getCategoryTypeAPI(): CategoryTypeAPIInterface;

    /**
     * @param array<string|int> $ids
     * @return array<string,mixed>
     */
    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        return [
            'include' => $ids,
        ];
    }

    protected function getOrderbyDefault(): string
    {
        return $this->getNameResolver()->getName('popcms:dbcolumn:orderby:categories:count');
    }

    protected function getOrderDefault(): string
    {
        return 'DESC';
    }

    public function executeQuery($query, array $options = []): array
    {
        $categoryTypeAPI = $this->getCategoryTypeAPI();
        return $categoryTypeAPI->getCategories($query, $options);
    }

    /**
     * @param array<string,mixed> $query
     * @return array<string|int>
     */
    public function executeQueryIDs(array $query): array
    {
        $options = [
            QueryOptions::RETURN_TYPE => ReturnTypes::IDS,
        ];
        return $this->executeQuery($query, $options);
    }
}
