<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeQueryableDataLoader;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\CustomPostsWP\TypeAPIs\CustomPostTypeAPIUtils;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions;

abstract class AbstractCustomPostTypeDataLoader extends AbstractObjectTypeQueryableDataLoader
{
    public function __construct(
        \PoP\Hooks\HooksAPIInterface $hooksAPI,
        \PoP\ComponentModel\Instances\InstanceManagerInterface $instanceManager,
        \PoP\LooseContracts\NameResolverInterface $nameResolver,
        \PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface $moduleProcessorManager,
        protected CustomPostTypeAPIInterface $customPostTypeAPI,
    ) {
        parent::__construct(
            $hooksAPI,
            $instanceManager,
            $nameResolver,
            $moduleProcessorManager,
        );
    }

    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        return [
            'include' => $ids,
            'status' => CustomPostTypeAPIUtils::getPostStatuses(),
        ];
    }

    public function executeQuery($query, array $options = []): array
    {
        return $this->customPostTypeAPI->getCustomPosts($query, $options);
    }

    protected function getOrderbyDefault()
    {
        return $this->nameResolver->getName('popcms:dbcolumn:orderby:customposts:date');
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
        return $this->hooksAPI->applyFilters(
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
