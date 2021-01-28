<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeDataLoaders;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class AbstractTypeQueryableDataLoader extends AbstractTypeDataLoader implements TypeQueryableDataLoaderInterface
{
    /**
     * Function to override
     */
    public function executeQuery($query, array $options = [])
    {
        return array();
    }

    public function executeQueryIds($query): array
    {
        return (array)$this->executeQuery($query);
    }

    protected function getPagenumberParam($query_args)
    {
        return HooksAPIFacade::getInstance()->applyFilters(
            'GD_Dataloader_List:query:pagenumber',
            $query_args[\PoP\ComponentModel\Constants\Params::PAGE_NUMBER]
        );
    }
    protected function getLimitParam($query_args)
    {
        return HooksAPIFacade::getInstance()->applyFilters(
            'GD_Dataloader_List:query:limit',
            $query_args[\PoP\ComponentModel\Constants\Params::LIMIT]
        );
    }

    public function findIDs(array $data_properties): array
    {
        $query_args = $data_properties[DataloadingConstants::QUERYARGS];

        // If already indicating the ids to get back, then already return them
        if ($include = $query_args['include']) {
            return $include;
        }

        // Customize query
        $query = $this->getQuery($query_args);

        // Allow URE to modify the role, limiting selected users and excluding others, like 'subscriber'
        $query = HooksAPIFacade::getInstance()->applyFilters(self::class . ':gd_dataload_query', $query, $data_properties);

        // Apply filtering of the data
        if ($filtering_modules = $data_properties[DataloadingConstants::QUERYARGSFILTERINGMODULES] ?? null) {
            $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
            foreach ($filtering_modules as $module) {
                $moduleprocessor_manager->getProcessor($module)->filterHeadmoduleDataloadQueryArgs($module, $query);
            }
        }

        // Execute the query, get ids
        $ids = $this->executeQueryIds($query);

        return $ids;
    }

    /**
     * Function to override
     */
    public function getDataFromIdsQuery(array $ids): array
    {
        return array();
    }

    public function getObjects(array $ids): array
    {
        $query = $this->getDataFromIdsQuery($ids);
        return (array)$this->executeQuery($query);
    }

    protected function getOrderbyDefault()
    {
        return '';
    }

    protected function getOrderDefault()
    {
        return '';
    }

    protected function getQueryHookName()
    {
        return 'Dataloader_ListTrait:query';
    }

    public function getQuery($query_args): array
    {
        // Use all the query params already provided in the query args
        $query = $query_args;

        // Allow to check for "loading-latest"
        $limit = $this->getLimitParam($query_args);
        $query['limit'] = $limit;
        $pagenumber = $this->getPagenumberParam($query_args);
        if ($pagenumber >= 2) {
            $query['offset'] = ($pagenumber - 1) * $limit;
        }
        // Params and values by default
        if (!$query['orderby']) {
            $query['orderby'] = $this->getOrderbyDefault();
        }
        if (!$query['order']) {
            $query['order'] = $this->getOrderDefault();
        }

        // Allow CoAuthors Plus to modify the query to add the coauthors
        return HooksAPIFacade::getInstance()->applyFilters(
            $this->getQueryHookName(),
            $query,
            $query_args
        );
    }

    public function getFilterDataloadingModule(): ?array
    {
        return null;
    }
}
