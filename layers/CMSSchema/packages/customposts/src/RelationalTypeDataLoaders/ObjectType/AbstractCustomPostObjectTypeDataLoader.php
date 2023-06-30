<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeQueryableDataLoader;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\FilterCustomPostStatusEnumTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;

abstract class AbstractCustomPostObjectTypeDataLoader extends AbstractObjectTypeQueryableDataLoader
{
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;
    private ?FilterCustomPostStatusEnumTypeResolver $filterCustomPostStatusEnumTypeResolver = null;

    final public function setCustomPostTypeAPI(CustomPostTypeAPIInterface $customPostTypeAPI): void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        if ($this->customPostTypeAPI === null) {
            /** @var CustomPostTypeAPIInterface */
            $customPostTypeAPI = $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
            $this->customPostTypeAPI = $customPostTypeAPI;
        }
        return $this->customPostTypeAPI;
    }
    final public function setFilterCustomPostStatusEnumTypeResolver(FilterCustomPostStatusEnumTypeResolver $filterCustomPostStatusEnumTypeResolver): void
    {
        $this->filterCustomPostStatusEnumTypeResolver = $filterCustomPostStatusEnumTypeResolver;
    }
    final protected function getFilterCustomPostStatusEnumTypeResolver(): FilterCustomPostStatusEnumTypeResolver
    {
        if ($this->filterCustomPostStatusEnumTypeResolver === null) {
            /** @var FilterCustomPostStatusEnumTypeResolver */
            $filterCustomPostStatusEnumTypeResolver = $this->instanceManager->getInstance(FilterCustomPostStatusEnumTypeResolver::class);
            $this->filterCustomPostStatusEnumTypeResolver = $filterCustomPostStatusEnumTypeResolver;
        }
        return $this->filterCustomPostStatusEnumTypeResolver;
    }

    /**
     * @param array<string|int> $ids
     * @return array<string,mixed>
     */
    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        return [
            'include' => $ids,
            'status' => $this->getFilterCustomPostStatusEnumTypeResolver()->getConsolidatedEnumValues(),
        ];
    }

    /**
     * @return mixed[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function executeQuery(array $query, array $options = []): array
    {
        return $this->getCustomPostTypeAPI()->getCustomPosts($query, $options);
    }

    protected function getOrderbyDefault(): string
    {
        return $this->getNameResolver()->getName('popcms:dbcolumn:orderby:customposts:date');
    }

    protected function getOrderDefault(): string
    {
        return 'DESC';
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

    /**
     * @param array<string,mixed> $query_args
     */
    protected function getLimitParam(array $query_args): int
    {
        // @todo convert the hook from string to const, then re-enable
        // return App::applyFilters(
        //     'CustomPostObjectTypeDataLoader:query:limit',
        //     parent::getLimitParam($query_args)
        // );
        return parent::getLimitParam($query_args);
    }

    protected function getQueryHookName(): string
    {
        // Allow to add the timestamp for loadingLatest
        return 'CustomPostObjectTypeDataLoader:query';
    }
}
