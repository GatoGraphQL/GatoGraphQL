<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Constants\DataSources;
use PoP\ComponentModel\Constants\HookNames;
use PoP\ComponentModel\Constants\PaginationParams;
use PoP\ComponentModel\QueryInputOutputHandlers\ActionExecutionQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\ObjectTypeQueryableDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;

trait QueryDataComponentProcessorTrait
{
    use FilterDataComponentProcessorTrait;

    abstract protected function getActionExecutionQueryInputOutputHandler(): ActionExecutionQueryInputOutputHandler;

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     */
    protected function getImmutableDataloadQueryArgs(Component $component, array &$props): array
    {
        return array();
    }
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     */
    protected function getMutableonrequestDataloadQueryArgs(Component $component, array &$props): array
    {
        return array();
    }
    public function getQueryInputOutputHandler(Component $component): ?QueryInputOutputHandlerInterface
    {
        return $this->getActionExecutionQueryInputOutputHandler();
    }
    // public function getFilter(\PoP\ComponentModel\Component\Component $component)
    // {
    //     return null;
    // }
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     */
    public function getImmutableHeaddatasetcomponentDataProperties(Component $component, array &$props): array
    {
        $ret = parent::getImmutableHeaddatasetcomponentDataProperties($component, $props);

        // Attributes to pass to the query
        $ret[DataloadingConstants::QUERYARGS] = $this->getImmutableDataloadQueryArgs($component, $props);

        return $ret;
    }

    /**
     * @return Component[]
     * @param array<string,mixed> $props
     */
    public function getQueryArgsFilteringComponents(Component $component, array &$props): array
    {
        // Attributes overriding the query args, taken from the request
        return [
            $component,
        ];
    }

    /**
     * @return mixed[]
     * @param array<string,mixed> $props
     */
    public function getMutableonmodelHeaddatasetcomponentDataProperties(Component $component, array &$props): array
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

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     */
    public function getMutableonrequestHeaddatasetcomponentDataProperties(Component $component, array &$props): array
    {
        $ret = parent::getMutableonrequestHeaddatasetcomponentDataProperties($component, $props);

        $ret[DataloadingConstants::QUERYARGS] = $this->getMutableonrequestDataloadQueryArgs($component, $props);

        return $ret;
    }

    /**
     * @return string|int|array<string|int>|null
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     */
    public function getObjectIDOrIDs(Component $component, array &$props, array &$data_properties): string|int|array|null
    {
        // Prepare the Query to get data from the DB
        $datasource = $data_properties[DataloadingConstants::DATASOURCE] ?? null;
        if ($datasource == DataSources::MUTABLEONREQUEST && !($data_properties[DataloadingConstants::IGNOREREQUESTPARAMS] ?? null)) {
            // Merge with $_POST/$_GET, so that params passed through the URL can be used for the query (eg: ?limit=5)
            // But whitelist the params that can be taken, to avoid hackers peering inside the system and getting custom data (eg: params "include", "post-status" => "draft", etc)
            $whitelisted_params = (array)App::applyFilters(
                HookNames::QUERYDATA_WHITELISTEDPARAMS,
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
        if ($relationalTypeResolver === null) {
            return null;
        }

        /** @var ObjectTypeQueryableDataLoaderInterface */
        $typeDataLoader = $relationalTypeResolver->getRelationalTypeDataLoader();
        return $typeDataLoader->findIDs($data_properties);
    }

    abstract public function getRelationalTypeResolver(Component $component): ?RelationalTypeResolverInterface;

    /**
     * @return mixed[]
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     * @param string|int|array<string|int> $objectIDOrIDs
     * @param array<string,mixed>|null $executed
     */
    public function getDatasetmeta(Component $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, string|int|array $objectIDOrIDs): array
    {
        $ret = parent::getDatasetmeta($component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDOrIDs);

        if ($queryHandler = $this->getQueryInputOutputHandler($component)) {
            if ($query_state = $queryHandler->getQueryState($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDOrIDs)) {
                $ret['querystate'] = $query_state;
            }
            if ($query_params = $queryHandler->getQueryParams($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDOrIDs)) {
                $ret['queryparams'] = $query_params;
            }
            if ($query_result = $queryHandler->getQueryResult($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDOrIDs)) {
                $ret['queryresult'] = $query_result;
            }
        }

        return $ret;
    }
}
