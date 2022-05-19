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

    protected function getImmutableDataloadQueryArgs(array $component, array &$props): array
    {
        return array();
    }
    protected function getMutableonrequestDataloadQueryArgs(array $component, array &$props): array
    {
        return array();
    }
    public function getQueryInputOutputHandler(array $component): ?QueryInputOutputHandlerInterface
    {
        return $this->getActionExecutionQueryInputOutputHandler();
    }
    // public function getFilter(array $component)
    // {
    //     return null;
    // }

    public function getImmutableHeaddatasetcomponentDataProperties(array $component, array &$props): array
    {
        $ret = parent::getImmutableHeaddatasetcomponentDataProperties($component, $props);

        // Attributes to pass to the query
        $ret[DataloadingConstants::QUERYARGS] = $this->getImmutableDataloadQueryArgs($component, $props);

        return $ret;
    }

    public function getQueryArgsFilteringComponents(array $component, array &$props): array
    {
        // Attributes overriding the query args, taken from the request
        return [
            $component,
        ];
    }

    public function getMutableonmodelHeaddatasetcomponentDataProperties(array $component, array &$props): array
    {
        $ret = parent::getMutableonmodelHeaddatasetcomponentDataProperties($component, $props);

        // Attributes overriding the query args, taken from the request
        if (!isset($ret[DataloadingConstants::IGNOREREQUESTPARAMS]) || !$ret[DataloadingConstants::IGNOREREQUESTPARAMS]) {
            $ret[DataloadingConstants::QUERYARGSFILTERINGCOMPONENTS] = $this->getQueryArgsFilteringComponents($component, $props);
        }

        // // Set the filter if it has one
        // if ($filter = $this->getFilter($component)) {
        //     $ret[GD_DATALOAD_FILTER] = $filter;
        // }

        return $ret;
    }

    public function getMutableonrequestHeaddatasetcomponentDataProperties(array $component, array &$props): array
    {
        $ret = parent::getMutableonrequestHeaddatasetcomponentDataProperties($component, $props);

        $ret[DataloadingConstants::QUERYARGS] = $this->getMutableonrequestDataloadQueryArgs($component, $props);

        return $ret;
    }

    public function getObjectIDOrIDs(array $component, array &$props, &$data_properties): string | int | array | null
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

        if ($queryHandler = $this->getQueryInputOutputHandler($component)) {
            // Allow the queryHandler to override/normalize the query args
            $queryHandler->prepareQueryArgs($data_properties[DataloadingConstants::QUERYARGS]);
        }

        $relationalTypeResolver = $this->getRelationalTypeResolver($component);
        /** @var ObjectTypeQueryableDataLoaderInterface */
        $typeDataLoader = $relationalTypeResolver->getRelationalTypeDataLoader();
        return $typeDataLoader->findIDs($data_properties);
    }

    public function getDatasetmeta(array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbObjectIDOrIDs): array
    {
        $ret = parent::getDatasetmeta($component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs);

        if ($queryHandler = $this->getQueryInputOutputHandler($component)) {
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
