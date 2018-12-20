<?php

trait PoP_QueryDataProcessorTrait {

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

	function get_dbobject_ids($module, &$props, $data_properties) {

		global $gd_dataload_manager;

		// Prepare the Query to get data from the DB
		$datasource = $data_properties[GD_DATALOAD_DATASOURCE];
		if ($datasource == POP_DATALOAD_DATASOURCE_MUTABLEONREQUEST) {

			// Merge with $_REQUEST, so that params passed through the URL can be used for the query (eg: ?limit=5)
			$data_properties[GD_DATALOAD_QUERYARGS] = array_merge(
				$_REQUEST,
				$data_properties[GD_DATALOAD_QUERYARGS]
			);
		}

		if ($queryhandler_name = $this->get_queryhandler($module)) {
					
			// Allow the queryhandler to override/normalize the query args
			global $gd_dataload_queryhandler_manager;
			$queryhandler = $gd_dataload_queryhandler_manager->get($queryhandler_name);
			$queryhandler->prepare_query_args($data_properties[GD_DATALOAD_QUERYARGS]);
		}

		$dataloader = $gd_dataload_manager->get($this->get_dataloader($module));
		return $dataloader->get_dbobject_ids($data_properties);
	}

	function get_datasetmeta($module, &$props, $data_properties, $checkpoint_validation, $executed, $dbobjectids) {

		$ret = parent::get_datasetmeta($module, $props, $data_properties, $checkpoint_validation, $executed, $dbobjectids);

		if ($queryhandler_name = $this->get_queryhandler($module)) {
					
			global $gd_dataload_queryhandler_manager;
			$queryhandler = $gd_dataload_queryhandler_manager->get($queryhandler_name);
			
			if ($query_state = $queryhandler->get_query_state($data_properties, $checkpoint_validation, $executed, $dbobjectids)) {

				$ret['querystate'] = $query_state;
			}
			if ($query_params = $queryhandler->get_query_params($data_properties, $checkpoint_validation, $executed, $dbobjectids)) {

				$ret['queryparams'] = $query_params;
			}
			if ($query_result = $queryhandler->get_query_result($data_properties, $checkpoint_validation, $executed, $dbobjectids)) {

				$ret['queryresult'] = $query_result;
			}
		}

		return $ret;
	}
}