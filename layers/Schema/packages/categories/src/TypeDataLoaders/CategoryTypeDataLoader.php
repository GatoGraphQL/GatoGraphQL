<?php

declare(strict_types=1);

namespace PoPSchema\Categories\TypeDataLoaders;

use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\ComponentModel\TypeDataLoaders\AbstractTypeQueryableDataLoader;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Categories\ModuleProcessors\FieldDataloads;

class CategoryTypeDataLoader extends AbstractTypeQueryableDataLoader
{
    public function getFilterDataloadingModule(): ?array
    {
        return [FieldDataloads::class, FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYLIST];
    }

    public function getObjects(array $ids): array
    {
        $query = array(
            'include' => $ids
        );
        $categoryapi = \PoPSchema\Categories\FunctionAPIFactory::getInstance();
        return $categoryapi->getCategories($query);
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
        return NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:categories:count');
    }

    protected function getOrderDefault()
    {
        return 'DESC';
    }

    public function executeQuery($query, array $options = [])
    {
        $categoryapi = \PoPSchema\Categories\FunctionAPIFactory::getInstance();
        return $categoryapi->getCategories($query, $options);
    }

    public function executeQueryIds($query): array
    {
        // $query['fields'] = 'ids';
        $options = [
            'return-type' => ReturnTypes::IDS,
        ];
        return (array)$this->executeQuery($query, $options);
    }
}
