<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeQueryableDataLoader;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\CustomPosts\TypeResolvers\EnumType\FilterCustomPostStatusEnumTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

abstract class AbstractCustomPostTypeDataLoader extends AbstractObjectTypeQueryableDataLoader
{
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;
    private ?FilterCustomPostStatusEnumTypeResolver $filterCustomPostStatusEnumTypeResolver = null;

    final public function setCustomPostTypeAPI(CustomPostTypeAPIInterface $customPostTypeAPI): void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        return $this->customPostTypeAPI ??= $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
    }
    final public function setFilterCustomPostStatusEnumTypeResolver(FilterCustomPostStatusEnumTypeResolver $filterCustomPostStatusEnumTypeResolver): void
    {
        $this->filterCustomPostStatusEnumTypeResolver = $filterCustomPostStatusEnumTypeResolver;
    }
    final protected function getFilterCustomPostStatusEnumTypeResolver(): FilterCustomPostStatusEnumTypeResolver
    {
        return $this->filterCustomPostStatusEnumTypeResolver ??= $this->instanceManager->getInstance(FilterCustomPostStatusEnumTypeResolver::class);
    }

    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        return [
            'include' => $ids,
            'status' => $this->getFilterCustomPostStatusEnumTypeResolver()->getConsolidatedEnumValues(),
        ];
    }

    public function executeQuery($query, array $options = []): array
    {
        return $this->getCustomPostTypeAPI()->getCustomPosts($query, $options);
    }

    protected function getOrderbyDefault()
    {
        return $this->getNameResolver()->getName('popcms:dbcolumn:orderby:customposts:date');
    }

    protected function getOrderDefault()
    {
        return 'DESC';
    }

    public function executeQueryIDs($query): array
    {
        $options = [
            QueryOptions::RETURN_TYPE => ReturnTypes::IDS,
        ];
        return $this->executeQuery($query, $options);
    }

    protected function getLimitParam($query_args)
    {
        return \PoP\Root\App::getHookManager()->applyFilters(
            'CustomPostTypeDataLoader:query:limit',
            parent::getLimitParam($query_args)
        );
    }

    protected function getQueryHookName()
    {
        // Allow to add the timestamp for loadingLatest
        return 'CustomPostTypeDataLoader:query';
    }
}
