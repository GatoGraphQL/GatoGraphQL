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
    // public function getFilter($module)
    // {
    //     return null;
    // }

    public function getImmutableHeaddatasetmoduleDataProperties($module, &$props)
    {
        $ret = parent::getImmutableHeaddatasetmoduleDataProperties($module, $props);

        // Attributes to pass to the query
        $ret[GD_DATALOAD_QUERYARGS] = $this->getImmutableDataloadQueryArgs($module, $props);

        return $ret;
    }

    public function getQueryArgsFilteringModules($module, &$props)
    {
        // Attributes overriding the dataloader args, taken from the request
        return [
            $module,
        ];
    }

    public function getMutableonmodelHeaddatasetmoduleDataProperties($module, &$props)
    {
        $ret = parent::getMutableonmodelHeaddatasetmoduleDataProperties($module, $props);

        // Attributes overriding the dataloader args, taken from the request
        if (!$ret[GD_DATALOAD_IGNOREREQUESTPARAMS]) {
            $ret[GD_DATALOAD_QUERYARGSFILTERINGMODULES] = $this->getQueryArgsFilteringModules($module, $props);
        }

        // // Set the filter if it has one
        // if ($filter = $this->getFilter($module)) {
        //     $ret[GD_DATALOAD_FILTER] = $filter;
        // }

        return $ret;
    }
    public function filterHeadmoduleDataloadQueryArgs(&$query, $module)
    {
        if ($active_filterqueryargs_modules = $this->getActiveDataloadQueryArgsFilteringModules($module)) {
            $moduleprocessor_manager = ModuleProcessor_Manager_Factory::getInstance();

            foreach ($active_filterqueryargs_modules as $submodule) {

                $submodule_processor = $moduleprocessor_manager->getProcessor($submodule);
                $value = $submodule_processor->getValue($submodule);
                $submodule_processor->filterDataloadQueryArgs($query, $submodule, $value);
            }
        }
    }

    public function getActiveDataloadQueryArgsFilteringModules($module)
    {
        // Search for cached result
        $this->activeDataloadQueryArgsFilteringModules = $this->activeDataloadQueryArgsFilteringModules ?? [];
        if (!is_null($this->activeDataloadQueryArgsFilteringModules[$module])) {
            return $this->activeDataloadQueryArgsFilteringModules[$module];
        }
        // if ($this instanceof \PoP\Engine\DataloadingModule) {

        $modules = [];
        $moduleprocessor_manager = ModuleProcessor_Manager_Factory::getInstance();
        // if ($filter_module = $this->getFilterModule($module)) {
        // Check if the module has any filtercomponent
        if ($filterqueryargs_modules = array_filter(
            // $moduleprocessor_manager->getProcessor($filter_module)->getDatasetmoduletreeSectionFlattenedModules($filter_module), 
            $this->getDatasetmoduletreeSectionFlattenedModules($module), 
            function($module) use($moduleprocessor_manager) {
                return $moduleprocessor_manager->getProcessor($module) instanceof \PoP\Engine\DataloadQueryArgsFilter;
            }
        )) {
            // Check if if we're currently filtering by any filtercomponent
            $modules = array_filter(
                $filterqueryargs_modules, 
                function($module) use($moduleprocessor_manager) {
                    return !is_null($moduleprocessor_manager->getProcessor($module)->getValue($module));
                }
            );
        }

        $this->activeDataloadQueryArgsFilteringModules[$module] = $modules;
        return $modules;
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
        if ($datasource == POP_DATALOAD_DATASOURCE_MUTABLEONREQUEST && !$data_properties[GD_DATALOAD_IGNOREREQUESTPARAMS]) {
            // Merge with $_REQUEST, so that params passed through the URL can be used for the query (eg: ?limit=5)
            // But whitelist the params that can be taken, to avoid hackers peering inside the system and getting custom data (eg: params "include", "post-status" => "draft", etc)
            $whitelisted_params = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
                'QueryDataModuleProcessorTrait:request:whitelisted_params',
                array(
                    GD_URLPARAM_REDIRECTTO,
                    GD_URLPARAM_PAGENUMBER,
                    GD_URLPARAM_LIMIT,
                    // Used for the Comments to know what post to fetch comments from when filtering
                    GD_URLPARAM_COMMENTPOSTID,
                )
            );
            $params_from_request = array_filter(
                $_REQUEST,
                function ($param) use ($whitelisted_params) {
                    return in_array($param, $whitelisted_params);
                },
                ARRAY_FILTER_USE_KEY
            );

            // Handle special cases
            // Avoid users querying all results (by passing limit=-1 or limit=0)
            $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
            if (isset($params_from_request[GD_URLPARAM_LIMIT])) {
                $limit = intval($params_from_request[GD_URLPARAM_LIMIT]);
                if ($limit === -1 || $limit === 0) {
                    $params_from_request[GD_URLPARAM_LIMIT] = $cmsapi->getOption(\PoP\CMS\NameResolver_Factory::getInstance()->getName('popcms:option:limit'));
                }
            }
            $params_from_request = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
                'QueryDataModuleProcessorTrait:request:filter_params',
                $params_from_request
            );

            // Finally merge it into the data properties
            $data_properties[GD_DATALOAD_QUERYARGS] = array_merge(
                $data_properties[GD_DATALOAD_QUERYARGS],
                $params_from_request
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
