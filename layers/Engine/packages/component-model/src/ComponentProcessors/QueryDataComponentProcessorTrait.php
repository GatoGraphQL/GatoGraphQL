<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Constants\DataSources;
use PoP\ComponentModel\Constants\PaginationParams;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\QueryInputOutputHandlers\ActionExecutionQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\ObjectTypeQueryableDataLoaderInterface;
use PoP\Root\App;

trait QueryDataComponentProcessorTrait
{
    use FilterDataComponentProcessorTrait;

    abstract protected function getActionExecutionQueryInputOutputHandler(): ActionExecutionQueryInputOutputHandler;

    protected function getImmutableDataloadQueryArgs(array $componentVariation, array &$props): array
    {
        return array();
    }
    protected function getMutableonrequestDataloadQueryArgs(array $componentVariation, array &$props): array
    {
        return array();
    }
    public function getQueryInputOutputHandler(array $componentVariation): ?QueryInputOutputHandlerInterface
    {
        return $this->getActionExecutionQueryInputOutputHandler();
    }
    // public function getFilter(array $componentVariation)
    // {
    //     return null;
    // }

    public function getImmutableHeaddatasetmoduleDataProperties(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableHeaddatasetmoduleDataProperties($componentVariation, $props);

        // Attributes to pass to the query
        $ret[DataloadingConstants::QUERYARGS] = $this->getImmutableDataloadQueryArgs($componentVariation, $props);

        return $ret;
    }

    public function getQueryArgsFilteringModules(array $componentVariation, array &$props): array
    {
        // Attributes overriding the query args, taken from the request
        return [
            $componentVariation,
        ];
    }

    public function getMutableonmodelHeaddatasetmoduleDataProperties(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonmodelHeaddatasetmoduleDataProperties($componentVariation, $props);

        // Attributes overriding the query args, taken from the request
        if (!isset($ret[DataloadingConstants::IGNOREREQUESTPARAMS]) || !$ret[DataloadingConstants::IGNOREREQUESTPARAMS]) {
            $ret[DataloadingConstants::QUERYARGSFILTERINGMODULES] = $this->getQueryArgsFilteringModules($componentVariation, $props);
        }

        // // Set the filter if it has one
        // if ($filter = $this->getFilter($componentVariation)) {
        //     $ret[GD_DATALOAD_FILTER] = $filter;
        // }

        return $ret;
    }

    public function getMutableonrequestHeaddatasetmoduleDataProperties(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestHeaddatasetmoduleDataProperties($componentVariation, $props);

        $ret[DataloadingConstants::QUERYARGS] = $this->getMutableonrequestDataloadQueryArgs($componentVariation, $props);

        return $ret;
    }

    public function getObjectIDOrIDs(array $componentVariation, array &$props, &$data_properties): string | int | array | null
    {
        // Prepare the Query to get data from the DB
        $datasource = $data_properties[DataloadingConstants::DATASOURCE] ?? null;
        if ($datasource == DataSources::MUTABLEONREQUEST && !($data_properties[DataloadingConstants::IGNOREREQUESTPARAMS] ?? null)) {
            // Merge with $_POST/$_GET, so that params passed through the URL can be used for the query (eg: ?limit=5)
            // But whitelist the params that can be taken, to avoid hackers peering inside the system and getting custom data (eg: params "include", "post-status" => "draft", etc)
            $whitelisted_params = (array)App::applyFilters(
                Constants::HOOK_QUERYDATA_WHITELISTEDPARAMS,
                array(
                    PaginationParams::PAGE_NUMBER,
                    PaginationParams::LIMIT,
                )
            );

            $params_from_request = array_filter(
                array_merge(
                    App::getRequest()->query->all(),
                    App::getRequest()->request->all()
                ),
                fn (string $param) => in_array($param, $whitelisted_params),
                ARRAY_FILTER_USE_KEY
            );

            $params_from_request = App::applyFilters(
                'QueryDataComponentProcessorTrait:request:filter_params',
                $params_from_request
            );

            // Finally merge it into the data properties
            $data_properties[DataloadingConstants::QUERYARGS] = array_merge(
                $data_properties[DataloadingConstants::QUERYARGS],
                $params_from_request
            );
        }

        if ($queryHandler = $this->getQueryInputOutputHandler($componentVariation)) {
            // Allow the queryHandler to override/normalize the query args
            $queryHandler->prepareQueryArgs($data_properties[DataloadingConstants::QUERYARGS]);
        }

        $relationalTypeResolver = $this->getRelationalTypeResolver($componentVariation);
        /** @var ObjectTypeQueryableDataLoaderInterface */
        $typeDataLoader = $relationalTypeResolver->getRelationalTypeDataLoader();
        return $typeDataLoader->findIDs($data_properties);
    }

    public function getDatasetmeta(array $componentVariation, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbObjectIDOrIDs): array
    {
        $ret = parent::getDatasetmeta($componentVariation, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs);

        if ($queryHandler = $this->getQueryInputOutputHandler($componentVariation)) {
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
