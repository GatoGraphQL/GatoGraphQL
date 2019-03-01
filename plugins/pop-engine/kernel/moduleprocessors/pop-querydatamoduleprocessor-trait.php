<?php
namespace PoP\Engine;

trait QueryDataModuleProcessorTrait
{
    protected function getImmutableDataloadQueryArgs($module, $props)
    {
        return array();
    }
    protected function getMutableonrequestDataloadQueryArgs($module, $props)
    {
        return array();
    }
    public function getQueryhandler($module)
    {
        return GD_DATALOAD_QUERYHANDLER_ACTIONEXECUTION;
    }
    public function getFilter($module)
    {
        return null;
    }

    public function getImmutableHeaddatasetmoduleDataProperties($module, &$props)
    {
        $ret = parent::getImmutableHeaddatasetmoduleDataProperties($module, $props);

        // Attributes to pass to the query
        $ret[GD_DATALOAD_QUERYARGS] = $this->getImmutableDataloadQueryArgs($module, $props);

        // Set the filter if it has one
        if ($filter = $this->getFilter($module)) {
            $ret[GD_DATALOAD_FILTER] = $filter;
        }

        return $ret;
    }

    public function getMutableonrequestHeaddatasetmoduleDataProperties($module, &$props)
    {
        $ret = parent::getMutableonrequestHeaddatasetmoduleDataProperties($module, $props);

        $ret[GD_DATALOAD_QUERYARGS] = $this->getMutableonrequestDataloadQueryArgs($module, $props);

        return $ret;
    }

    public function getDbobjectIds($module, &$props, &$data_properties)
    {
        $dataloader_manager = Dataloader_Manager_Factory::getInstance();

        // Prepare the Query to get data from the DB
        $datasource = $data_properties[GD_DATALOAD_DATASOURCE];
        if ($datasource == POP_DATALOAD_DATASOURCE_MUTABLEONREQUEST) {
            // Merge with $_REQUEST, so that params passed through the URL can be used for the query (eg: ?limit=5)
            // But whitelist the params that can be taken, to avoid hackers peering inside the system and getting custom data (eg: params "include", "post-status" => "draft", etc)
            $whitelisted_params = apply_filters(
                'QueryDataModuleProcessorTrait:request:whitelisted_params',
                array(
                    GD_URLPARAM_REDIRECTTO,
                    GD_URLPARAM_PAGED,
                    GD_URLPARAM_LIMIT,
                    // Used for the Comments to know what post to fetch comments from when filtering
                    GD_URLPARAM_POSTID,
                )
            );
            $data_properties[GD_DATALOAD_QUERYARGS] = array_merge(
                array_filter(
                    $_REQUEST,
                    function ($param) use ($whitelisted_params) {
                        return in_array($param, $whitelisted_params);
                    },
                    ARRAY_FILTER_USE_KEY
                ),
                $data_properties[GD_DATALOAD_QUERYARGS]
            );
        }

        if ($queryhandler_name = $this->getQueryhandler($module)) {
            // Allow the queryhandler to override/normalize the query args
            $queryhandler_manager = QueryHandler_Manager_Factory::getInstance();
            $queryhandler = $queryhandler_manager->get($queryhandler_name);
            $queryhandler->prepareQueryArgs($data_properties[GD_DATALOAD_QUERYARGS]);
        }

        $dataloader = $dataloader_manager->get($this->getDataloader($module));
        return $dataloader->getDbobjectIds($data_properties);
    }

    public function getDatasetmeta($module, &$props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)
    {
        $ret = parent::getDatasetmeta($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

        if ($queryhandler_name = $this->getQueryhandler($module)) {
            $queryhandler_manager = QueryHandler_Manager_Factory::getInstance();
            $queryhandler = $queryhandler_manager->get($queryhandler_name);
            
            if ($query_state = $queryhandler->getQueryState($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)) {
                $ret['querystate'] = $query_state;
            }
            if ($query_params = $queryhandler->getQueryParams($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)) {
                $ret['queryparams'] = $query_params;
            }
            if ($query_result = $queryhandler->getQueryResult($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)) {
                $ret['queryresult'] = $query_result;
            }
        }

        return $ret;
    }
}
