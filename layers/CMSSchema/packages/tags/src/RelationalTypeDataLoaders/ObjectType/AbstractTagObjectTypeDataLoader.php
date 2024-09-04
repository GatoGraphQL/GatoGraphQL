<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoP\ComponentModel\App;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeQueryableDataLoader;

abstract class AbstractTagObjectTypeDataLoader extends AbstractObjectTypeQueryableDataLoader
{
    public const HOOK_ALL_OBJECTS_BY_IDS_QUERY = __CLASS__ . ':all-objects-by-ids-query';

    abstract public function getTagTypeAPI(): TagTypeAPIInterface;

    /**
     * @param array<string|int> $ids
     * @return array<string,mixed>
     */
    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        return App::applyFilters(
            self::HOOK_ALL_OBJECTS_BY_IDS_QUERY,
            [
                'include' => $ids,
            ],
            $ids
        );
    }

    protected function getOrderbyDefault(): string
    {
        return $this->getNameResolver()->getName('popcms:dbcolumn:orderby:tags:count');
    }

    protected function getOrderDefault(): string
    {
        return 'DESC';
    }

    /**
     * @return mixed[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function executeQuery(array $query, array $options = []): array
    {
        $tagTypeAPI = $this->getTagTypeAPI();
        return $tagTypeAPI->getTags($query, $options);
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
