<?php
namespace PoP\Engine;

trait QueryDataModuleProcessorTrait {

	protected function get_immutable_dataload_query_args($module, $props) {

		return array();
	}
	protected function get_mutableonrequest_dataload_query_args($module, $props) {

		return array();
	}
	function get_queryhandler($module) {

		return GD_DATALOAD_QUERYHANDLER_ACTIONEXECUTION;
	}
	function get_filter($module) {

		return null;
	}

	function get_immutable_headdatasetmodule_data_properties($module, &$props) {

		$ret = parent::get_immutable_headdatasetmodule_data_properties($module, $props);

		// Attributes to pass to the query
		$ret[GD_DATALOAD_QUERYARGS] = $this->get_immutable_dataload_query_args($module, $props);

		// Set the filter if it has one
		if ($filter = $this->get_filter($module)) {

			$ret[GD_DATALOAD_FILTER] = $filter;
		}

		return $ret;
	}

	function get_mutableonrequest_headdatasetmodule_data_properties($module, &$props) {

		$ret = parent::get_mutableonrequest_headdatasetmodule_data_properties($module, $props);

		$ret[GD_DATALOAD_QUERYARGS] = $this->get_mutableonrequest_dataload_query_args($module, $props);

		return $ret;
	}

	function get_dbobject_ids($module, &$props, &$data_properties) {

		$dataloader_manager = Dataloader_Manager_Factory::get_instance();

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
				array_filter($_REQUEST, function($param) use ($whitelisted_params) {
				    return in_array($param, $whitelisted_params);
				}, ARRAY_FILTER_USE_KEY),
				$data_properties[GD_DATALOAD_QUERYARGS]
			);
		}

		if ($queryhandler_name = $this->get_queryhandler($module)) {
					
			// Allow the queryhandler to override/normalize the query args
			$queryhandler_manager = QueryHandler_Manager_Factory::get_instance();
			$queryhandler = $queryhandler_manager->get($queryhandler_name);
			$queryhandler->prepare_query_args($data_properties[GD_DATALOAD_QUERYARGS]);
		}

		$dataloader = $dataloader_manager->get($this->get_dataloader($module));
		return $dataloader->get_dbobject_ids($data_properties);
	}

	function get_datasetmeta($module, &$props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids) {

		$ret = parent::get_datasetmeta($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

		if ($queryhandler_name = $this->get_queryhandler($module)) {
					
			$queryhandler_manager = QueryHandler_Manager_Factory::get_instance();
			$queryhandler = $queryhandler_manager->get($queryhandler_name);
			
			if ($query_state = $queryhandler->get_query_state($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)) {

				$ret['querystate'] = $query_state;
			}
			if ($query_params = $queryhandler->get_query_params($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)) {

				$ret['queryparams'] = $query_params;
			}
			if ($query_result = $queryhandler->get_query_result($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)) {

				$ret['queryresult'] = $query_result;
			}
		}

		return $ret;
	}
}
