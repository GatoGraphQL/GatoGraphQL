<?php

declare(strict_types=1);

namespace PoPSchema\Categories\TypeDataLoaders;

use PoPSchema\Categories\ComponentContracts\CategoryAPIRequestedContractTrait;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\ComponentModel\TypeDataLoaders\AbstractTypeQueryableDataLoader;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Categories\ModuleProcessors\FieldDataloadModuleProcessor;

abstract class AbstractCategoryTypeDataLoader extends AbstractTypeQueryableDataLoader
{
    use CategoryAPIRequestedContractTrait;

    public function getFilterDataloadingModule(): ?array
    {
        return [FieldDataloadModuleProcessor::class, FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYLIST];
    }

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
        return NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:categories:count');
    }

    protected function getOrderDefault()
    {
        return 'DESC';
    }

    public function executeQuery($query, array $options = [])
    {
        $categoryTypeAPI = $this->getTypeAPI();
        return $categoryTypeAPI->getCategories($query, $options);
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
