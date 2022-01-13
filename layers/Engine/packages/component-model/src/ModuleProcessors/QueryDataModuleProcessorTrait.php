<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\ComponentModel\Constants\DataSources;
use PoP\ComponentModel\Constants\PaginationParams;
use PoP\ComponentModel\QueryInputOutputHandlers\ActionExecutionQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\ObjectTypeQueryableDataLoaderInterface;

trait QueryDataModuleProcessorTrait
{
    use FilterDataModuleProcessorTrait;

    abstract protected function getActionExecutionQueryInputOutputHandler(): ActionExecutionQueryInputOutputHandler;

    protected function getImmutableDataloadQueryArgs(array $module, array &$props): array
    {
        return array();
    }
    protected function getMutableonrequestDataloadQueryArgs(array $module, array &$props): array
    {
        return array();
    }
    public function getQueryInputOutputHandler(array $module): ?QueryInputOutputHandlerInterface
    {
        return $this->getActionExecutionQueryInputOutputHandler();
    }
    // public function getFilter(array $module)
    // {
    //     return null;
    // }

    public function getImmutableHeaddatasetmoduleDataProperties(array $module, array &$props): array
    {
        $ret = parent::getImmutableHeaddatasetmoduleDataProperties($module, $props);

        // Attributes to pass to the query
        $ret[DataloadingConstants::QUERYARGS] = $this->getImmutableDataloadQueryArgs($module, $props);

        return $ret;
    }

    public function getQueryArgsFilteringModules(array $module, array &$props): array
    {
        // Attributes overriding the query args, taken from the request
        return [
            $module,
        ];
    }

    public function getMutableonmodelHeaddatasetmoduleDataProperties(array $module, array &$props): array
    {
        $ret = parent::getMutableonmodelHeaddatasetmoduleDataProperties($module, $props);

        // Attributes overriding the query args, taken from the request
        if (!isset($ret[DataloadingConstants::IGNOREREQUESTPARAMS]) || !$ret[DataloadingConstants::IGNOREREQUESTPARAMS]) {
            $ret[DataloadingConstants::QUERYARGSFILTERINGMODULES] = $this->getQueryArgsFilteringModules($module, $props);
        }

        // // Set the filter if it has one
        // if ($filter = $this->getFilter($module)) {
        //     $ret[GD_DATALOAD_FILTER] = $filter;
        // }

        return $ret;
    }

    public function getMutableonrequestHeaddatasetmoduleDataProperties(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestHeaddatasetmoduleDataProperties($module, $props);

        $ret[DataloadingConstants::QUERYARGS] = $this->getMutableonrequestDataloadQueryArgs($module, $props);

        return $ret;
    }

    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array | null
    {
        // Prepare the Query to get data from the DB
        $datasource = $data_properties[DataloadingConstants::DATASOURCE] ?? null;
        if ($datasource == DataSources::MUTABLEONREQUEST && !($data_properties[DataloadingConstants::IGNOREREQUESTPARAMS] ?? null)) {
            // Merge with $_REQUEST, so that params passed through the URL can be used for the query (eg: ?limit=5)
            // But whitelist the params that can be taken, to avoid hackers peering inside the system and getting custom data (eg: params "include", "post-status" => "draft", etc)
            $whitelisted_params = (array)\PoP\Root\App::getHookManager()->applyFilters(
                Constants::HOOK_QUERYDATA_WHITELISTEDPARAMS,
                array(
                    PaginationParams::PAGE_NUMBER,
                    PaginationParams::LIMIT,
                )
            );
            $params_from_request = array_filter(
                $_REQUEST,
                function ($param) use ($whitelisted_params) {
                    return in_array($param, $whitelisted_params);
                },
                ARRAY_FILTER_USE_KEY
            );

            $params_from_request = \PoP\Root\App::getHookManager()->applyFilters(
                'QueryDataModuleProcessorTrait:request:filter_params',
                $params_from_request
            );

            // Finally merge it into the data properties
            $data_properties[DataloadingConstants::QUERYARGS] = array_merge(
                $data_properties[DataloadingConstants::QUERYARGS],
                $params_from_request
            );
        }

        if ($queryHandler = $this->getQueryInputOutputHandler($module)) {
            // Allow the queryHandler to override/normalize the query args
            $queryHandler->prepareQueryArgs($data_properties[DataloadingConstants::QUERYARGS]);
        }

        $relationalTypeResolver = $this->getRelationalTypeResolver($module);
        /** @var ObjectTypeQueryableDataLoaderInterface */
        $typeDataLoader = $relationalTypeResolver->getRelationalTypeDataLoader();
        return $typeDataLoader->findIDs($data_properties);
    }

    public function getDatasetmeta(array $module, array &$props, array $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs): array
    {
        $ret = parent::getDatasetmeta($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs);

        if ($queryHandler = $this->getQueryInputOutputHandler($module)) {
            if ($query_state = $queryHandler->getQueryState($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs)) {
                $ret['querystate'] = $query_state;
            }
            if ($query_params = $queryHandler->getQueryParams($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs)) {
                $ret['queryparams'] = $query_params;
            }
            if ($query_result = $queryHandler->getQueryResult($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs)) {
                $ret['queryresult'] = $query_result;
            }
        }

        return $ret;
    }
}
