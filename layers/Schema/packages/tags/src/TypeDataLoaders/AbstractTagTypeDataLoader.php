<?php

declare(strict_types=1);

namespace PoPSchema\Tags\TypeDataLoaders;

use PoPSchema\Tags\ComponentContracts\TagAPIRequestedContractTrait;
use PoP\ComponentModel\TypeDataLoaders\AbstractTypeQueryableDataLoader;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Tags\ModuleProcessors\FieldDataloadModuleProcessor;

abstract class AbstractTagTypeDataLoader extends AbstractTypeQueryableDataLoader
{
    use TagAPIRequestedContractTrait;

    public function getFilterDataloadingModule(): ?array
    {
        return [FieldDataloadModuleProcessor::class, FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST];
    }

    public function getObjects(array $ids): array
    {
        $query = array(
            'include' => $ids
        );
        $tagTypeAPI = $this->getTypeAPI();
        return $tagTypeAPI->getTags($query);
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
        return $this->nameResolver->getName('popcms:dbcolumn:orderby:tags:count');
    }

    protected function getOrderDefault()
    {
        return 'DESC';
    }

    public function executeQuery($query, array $options = [])
    {
        $tagTypeAPI = $this->getTypeAPI();
        return $tagTypeAPI->getTags($query, $options);
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
