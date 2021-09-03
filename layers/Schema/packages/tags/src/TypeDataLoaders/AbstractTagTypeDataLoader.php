<?php

declare(strict_types=1);

namespace PoPSchema\Tags\TypeDataLoaders;

use PoPSchema\Tags\ComponentContracts\TagAPIRequestedContractTrait;
use PoP\ComponentModel\TypeDataLoaders\AbstractTypeQueryableDataLoader;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions;

abstract class AbstractTagTypeDataLoader extends AbstractTypeQueryableDataLoader
{
    use TagAPIRequestedContractTrait;

    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        return [
            'include' => $ids,
        ];
    }

    protected function getOrderbyDefault()
    {
        return $this->nameResolver->getName('popcms:dbcolumn:orderby:tags:count');
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
