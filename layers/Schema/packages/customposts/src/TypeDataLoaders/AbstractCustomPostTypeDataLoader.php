<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeDataLoaders;

use PoPSchema\CustomPosts\Types\Status;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoP\ComponentModel\TypeDataLoaders\AbstractTypeQueryableDataLoader;
use PoPSchema\CustomPosts\ModuleProcessors\CustomPostRelationalFieldDataloadModuleProcessor;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

abstract class AbstractCustomPostTypeDataLoader extends AbstractTypeQueryableDataLoader
{
    public function getFilterDataloadingModule(): ?array
    {
        return [
            CustomPostRelationalFieldDataloadModuleProcessor::class,
            CustomPostRelationalFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST
        ];
    }

    public function getObjectQuery(array $ids): array
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        return array(
            'include' => $ids,
            // If not adding the post types, WordPress only uses "post", so querying by CPT would fail loading data
            // This should be considered for the CMS-agnostic case if it makes sense
            'custompost-types' => $customPostTypeAPI->getCustomPostTypes([
                'publicly-queryable' => true,
            ])
        );
    }

    public function getObjects(array $ids): array
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $query = $this->getObjectQuery($ids);
        return $customPostTypeAPI->getCustomPosts($query);
    }

    public function getDataFromIdsQuery(array $ids): array
    {
        $query = array();
        $query['include'] = $ids;
        $query['status'] = [
            Status::PUBLISHED,
            Status::DRAFT,
            Status::PENDING,
        ]; // Status can also be 'pending', so don't limit it here, just select by ID

        return $query;
    }

    public function executeQuery($query, array $options = [])
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        return $customPostTypeAPI->getCustomPosts($query, $options);
    }

    protected function getOrderbyDefault()
    {
        return NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:customposts:date');
    }

    protected function getOrderDefault()
    {
        return 'DESC';
    }

    public function executeQueryIds($query): array
    {
        $options = [
            'return-type' => ReturnTypes::IDS,
        ];
        return (array)$this->executeQuery($query, $options);
    }

    protected function getLimitParam($query_args)
    {
        return HooksAPIFacade::getInstance()->applyFilters(
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
