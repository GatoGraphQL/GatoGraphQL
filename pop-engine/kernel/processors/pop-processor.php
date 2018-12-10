<?php

define ('POP_MODULECOMPONENT_MODULES', 'modules');
define ('POP_MODULECOMPONENT_DBOBJECTRELATIONALSUCCESSORMODULES', 'dbobject-relational-successor-modules');

class PoP_ProcessorBase {

	use PoP_ModulePathProcessorTrait;

	function __construct() {

		global $pop_module_processor_manager;
		$pop_module_processor_manager->add($this, $this->get_modules_to_process());
	}

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------
	function get_modules_to_process() {
	
		return array();
	}

	function get_modules($module) {

		return array();
	}

	function get_descendant_modules($module) {

		return $this->get_modulecomponents($module);
	}

	function get_hierarchy($module) {

		return null;
	}

	//-------------------------------------------------
	// New PUBLIC Functions: Atts
	//-------------------------------------------------

	function execute_init_atts_moduletree($eval_self_fn, $get_atts_for_descendant_modules_fn, $get_atts_for_descendant_datasetmodules_fn, $propagate_fn, $module, &$atts, $wildcard_atts_to_propagate, $targetted_atts_to_propagate) {

		global $pop_module_processor_manager;
		
		// Initialize. If this module had been added atts, then use them already
		// 1st element to merge: the general atts for this module passed down the line
		// 2nd element to merge: the atts set exactly to the path. They have more priority, that's why they are 2nd
		// It may contain more than one group (POP_ATTS_ATTRIBUTES). Eg: maybe also POP_ATTS_JSMETHODS
		$atts[$module] = array_merge_recursive(
			$targetted_atts_to_propagate[$module] ?? array(),
			$atts[$module] ?? array()
		);

		// The module must be at the head of the $atts array passed to all `init_model_atts`, so that function `get_path_head_module` can work
		$module_atts = array(
			$module => &$atts[$module],
		);

		// If ancestor modules set general atts, or atts targetted at this current module, then add them to the current module atts
		foreach ($wildcard_atts_to_propagate as $key => $value) {
			$this->add_att($module, $module_atts, $key, $value);
		}

		// Before initiating the current level, set the children attributes on the array, so that doing ->add_att, ->append_att, etc, keeps working
		$module_atts[$module][POP_ATTS_DESCENDANTATTRIBUTES] = array_merge(
			$module_atts[$module][POP_ATTS_DESCENDANTATTRIBUTES] ?? array(),
			$targetted_atts_to_propagate ?? array()
		);

		// Initiate the current level. 
		$this->$eval_self_fn($module, $module_atts);

		// Immediately after initiating the current level, extract all child attributes out from the $atts, and place it on the other variable
		$targetted_atts_to_propagate = $module_atts[$module][POP_ATTS_DESCENDANTATTRIBUTES];
		unset($module_atts[$module][POP_ATTS_DESCENDANTATTRIBUTES]);

		// But because modules can't repeat themselves down the line (or it would generate an infinite loop), then can remove the current module from the targeted atts
		unset($targetted_atts_to_propagate[$module]);

		// Allow the $module to add general atts for all its descendant modules
		$wildcard_atts_to_propagate = array_merge(
			$wildcard_atts_to_propagate,
			$this->$get_atts_for_descendant_modules_fn($module, $module_atts)
		);
		
		// Propagate
		$modulefilter_manager = PoP_ModuleFilterManager_Factory::get_instance();
		$submodules = $this->get_descendant_modules($module);
		$submodules = $modulefilter_manager->remove_excluded_submodules($module, $submodules);

		// This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
		$module_path_manager = PoP_ModulePathManager_Factory::get_instance();
		$module_path_manager->prepare_for_propagation($module);
		if ($submodules) {
			
			$atts[$module][POP_ATTS_MODULES] = $atts[$module][POP_ATTS_MODULES] ?? array();
			foreach ($submodules as $submodule) {

				$submodule_processor = $pop_module_processor_manager->get_processor($submodule);
				$submodule_wildcard_atts_to_propagate = $wildcard_atts_to_propagate;

				// If the submodule belongs to the same dataset (meaning that it doesn't have a dataloader of its own), then set the shared attributies for the same-dataset modules
				if (!$submodule_processor->get_dataloader($submodule)) {

					$submodule_wildcard_atts_to_propagate = array_merge(
						$submodule_wildcard_atts_to_propagate,
						$this->$get_atts_for_descendant_datasetmodules_fn($module, $module_atts)
					);
				}

				$submodule_processor->$propagate_fn($submodule, $atts[$module][POP_ATTS_MODULES], $submodule_wildcard_atts_to_propagate, $targetted_atts_to_propagate);
			}
		}
		$module_path_manager->restore_from_propagation($module);
	}

	function init_model_atts_moduletree($module, &$atts, $wildcard_atts_to_propagate, $targetted_atts_to_propagate) {

		$this->execute_init_atts_moduletree('init_model_atts', 'get_model_atts_for_descendant_modules', 'get_model_atts_for_descendant_datasetmodules', __FUNCTION__, $module, $atts, $wildcard_atts_to_propagate, $targetted_atts_to_propagate);
	}

	function get_model_atts_for_descendant_modules($module, &$atts) {

		$ret = array();

		// If we set property 'skip-data-load' on any module, not just dataset, spread it down to its children so it reaches its contained dataset submodules
		$skip_data_load = $this->get_att($module, $atts, 'skip-data-load');
		if (!is_null($skip_data_load)) {

			$ret['skip-data-load'] = $skip_data_load;
		}

		return $ret;
	}

	function get_model_atts_for_descendant_datasetmodules($module, &$atts) {

		$ret = array();

		// If this module loads data, then add several properties
		if ($this->get_dataloader($module)) {
			
			if ($this->queries_external_domain($module, $atts)) {

				$ret['external-domain'] = true;
			}

			// If it is multidomain, add a flag for inner layouts to know and react
			if ($this->is_multidomain($module, $atts)) {

				$ret['multidomain'] = true;
			}
		}

		return $ret;
	}
	
	function init_model_atts($module, &$atts) {

		// If it is a dataloader module, then set all the atts related to data
		if ($dataloader = $this->get_dataloader($module)) {

			$vars = PoP_ModuleManager_Vars::get_vars();

			// If it is multidomain, add a flag for inner layouts to know and react
			if ($this->is_multidomain($module, $atts)) {
			
				// $this->add_general_att($atts, 'is-multidomain', true);
				$this->append_att($module, $atts, 'class', 'pop-multidomain');
			}
		}

		/**---------------------------------------------------------------------------------------------------------------
		 * Allow to add more stuff
		 * ---------------------------------------------------------------------------------------------------------------*/
		do_action(
			'PoP_ProcessorBase:init_model_atts',
			array(&$atts),
			$module,
			$this
		);
	}

	function init_request_atts_moduletree($module, &$atts, $wildcard_atts_to_propagate, $targetted_atts_to_propagate) {

		$this->execute_init_atts_moduletree('init_request_atts', 'get_request_atts_for_descendant_modules', 'get_request_atts_for_descendant_datasetmodules', __FUNCTION__, $module, $atts, $wildcard_atts_to_propagate, $targetted_atts_to_propagate);
	}

	function get_request_atts_for_descendant_modules($module, &$atts) {

		return array();
	}

	function get_request_atts_for_descendant_datasetmodules($module, &$atts) {

		return array();
	}
	
	function init_request_atts($module, &$atts) {

		/**---------------------------------------------------------------------------------------------------------------
		 * Allow to add more stuff
		 * ---------------------------------------------------------------------------------------------------------------*/
		do_action(
			'PoP_ProcessorBase:init_request_atts',
			array(&$atts),
			$module,
			$this
		);
	}

	//-------------------------------------------------
	// PRIVATE Functions: Atts
	//-------------------------------------------------

	private function get_path_head_module(&$atts) {

		// From the root of the $atts we obtain the current module
		reset($atts);
		return key($atts);
	}

	private function is_descendant_module($module_or_modulepath, &$atts) {

		// If it is an array, then we're passing the path to find the module to which to add the att
		if (!is_array($module_or_modulepath)) {

			// From the root of the $atts we obtain the current module
			$module = $this->get_path_head_module($atts);

			// If the module were we are adding the att, is this same module, then we are already at the path
			// If it is not, then go down one level to that module
			return ($module !== $module_or_modulepath);
		}

		return false;
	}

	protected function get_modulepath($module_or_modulepath, &$atts) {

		if (!$atts) {
			return array();
		}

		// From the root of the $atts we obtain the current module
		$module = $this->get_path_head_module($atts);
		
		// Calculate the path to iterate down. It always starts with the current module
		$ret = array($module);

		// If it is an array, then we're passing the path to find the module to which to add the att
		if (is_array($module_or_modulepath)) {

			$ret = array_merge(
				$ret,
				$module_or_modulepath
			);
		}

		return $ret;
	}

	protected function add_att_group_field($group, $module_or_modulepath, &$atts, $field, $value, $starting_from_modulepath = array(), $options = array()) {

		// Iterate down to the submodule, which must be an array of modules
		if ($starting_from_modulepath) {

			// Attach the current module, which is not included on "starting_from", to step down this level too
			$module = $this->get_path_head_module($atts);
			array_unshift($starting_from_modulepath, $module);

			// Descend into the path to find the module for which to add the att
			$module_atts = &$atts;
			foreach ($starting_from_modulepath as $pathlevel) {

				$last_module_atts = &$module_atts;
				$last_module = $pathlevel;

				$module_atts[$pathlevel][POP_ATTS_MODULES] = $module_atts[$pathlevel][POP_ATTS_MODULES] ?? array();
				$module_atts = &$module_atts[$pathlevel][POP_ATTS_MODULES];
			}

			// This is the new $atts, so it starts from here
			// Save the current $atts, and restore later, to make sure this array has only one key, otherwise it will not work
			$current_atts = $atts;
			$atts = array(
				$last_module => &$last_module_atts[$last_module]
			);
		}

		// If the module is a string, there are 2 possibilities: either it is the current module or not
		// If it is not, then it is a descendant module, which will appear at some point down the path.
		// For that case, simply save it under some other entry, from where it will propagate the atts later on in `init_model_atts_moduletree`
		if ($this->is_descendant_module($module_or_modulepath, $atts)) {

			// It is a child module
			$att_module = $module_or_modulepath;

			// From the root of the $atts we obtain the current module
			$module = $this->get_path_head_module($atts);

			// Set the child attributes under a different entry
			$atts[$module][POP_ATTS_DESCENDANTATTRIBUTES] = $atts[$module][POP_ATTS_DESCENDANTATTRIBUTES] ?? array();
			$module_atts = &$atts[$module][POP_ATTS_DESCENDANTATTRIBUTES];
		}
		else {

			// Calculate the path to iterate down
			$modulepath = $this->get_modulepath($module_or_modulepath, $atts);

			// Extract the lastlevel, that's the module to with to add the att
			$att_module = array_pop($modulepath);

			// Descend into the path to find the module for which to add the att
			$module_atts = &$atts;
			foreach ($modulepath as $pathlevel) {

				$module_atts[$pathlevel][POP_ATTS_MODULES] = $module_atts[$pathlevel][POP_ATTS_MODULES] ?? array();
				$module_atts = &$module_atts[$pathlevel][POP_ATTS_MODULES];
			}
		}

		// Now can proceed to add the att
		$module_atts[$att_module][$group] = $module_atts[$att_module][$group] ?? array();

		if ($options['append']) {

			$module_atts[$att_module][$group][$field] = $module_atts[$att_module][$group][$field] ?? '';
			$module_atts[$att_module][$group][$field] .= ' ' . $value;
		}
		elseif ($options['array']) {
			
			$module_atts[$att_module][$group][$field] = $module_atts[$att_module][$group][$field] ?? array();
			if ($options['merge']) {
				$module_atts[$att_module][$group][$field] = array_merge(
					$module_atts[$att_module][$group][$field],
					$value
				);
			}
			elseif ($options['merge-iterate-key']) {
				foreach ($value as $value_key => $value_value) {
					if (!$module_atts[$att_module][$group][$field][$value_key]) {
						$module_atts[$att_module][$group][$field][$value_key] = array();
					}
					// Doing array_unique, because in the NotificationPreviewLayout, different layouts might impose a JS down the road, many times, and these get duplicated
					$module_atts[$att_module][$group][$field][$value_key] = array_unique(
						array_merge(
							$module_atts[$att_module][$group][$field][$value_key],
							$value_value
						)
					);
				}
			}
			elseif ($options['push']) {
				array_push($module_atts[$att_module][$group][$field], $value);
			}
		}
		else {
			// If already set, then do nothing
			if (!isset($module_atts[$att_module][$group][$field])) {
				$module_atts[$att_module][$group][$field] = $value;
			}
		}

		// Restore original $atts
		if ($starting_from_modulepath) {

			$atts = $current_atts;
		}
	}
	protected function get_att_group_field($group, $module, &$atts, $field, $starting_from_modulepath = array()) {

		$group = $this->get_att_group($group, $module, $atts, $starting_from_modulepath);
		return $group[$field];
	}
	protected function get_att_group($group, $module, &$atts, $starting_from_modulepath = array()) {

		if (!$atts) {
			return array();
		}

		$module_atts = &$atts;
		foreach ($starting_from_modulepath as $pathlevel) {

			$module_atts = &$module_atts[$pathlevel][POP_ATTS_MODULES];
		}

		return $module_atts[$module][$group] ?? array();
	}
	protected function add_group_att($group, $module_or_modulepath, &$atts, $field, $value, $starting_from_modulepath = array()) {

		$this->add_att_group_field($group, $module_or_modulepath, $atts, $field, $value, $starting_from_modulepath);
	}
	function add_att($module_or_modulepath, &$atts, $field, $value, $starting_from_modulepath = array()) {

		$this->add_group_att(POP_ATTS_ATTRIBUTES, $module_or_modulepath, $atts, $field, $value, $starting_from_modulepath);
	}
	function append_group_att($group, $module_or_modulepath, &$atts, $field, $value, $starting_from_modulepath = array()) {

		$this->add_att_group_field($group, $module_or_modulepath, $atts, $field, $value, $starting_from_modulepath, array('append' => true));
	}
	function append_att($module_or_modulepath, &$atts, $field, $value, $starting_from_modulepath = array()) {

		$this->append_group_att(POP_ATTS_ATTRIBUTES, $module_or_modulepath, $atts, $field, $value, $starting_from_modulepath);
	}
	function merge_group_att($group, $module_or_modulepath, &$atts, $field, $value, $starting_from_modulepath = array()) {

		$this->add_att_group_field($group, $module_or_modulepath, $atts, $field, $value, $starting_from_modulepath, array('array' => true, 'merge' => true));
	}
	function merge_att($module_or_modulepath, &$atts, $field, $value, $starting_from_modulepath = array()) {

		$this->merge_group_att(POP_ATTS_ATTRIBUTES, $module_or_modulepath, $atts, $field, $value, $starting_from_modulepath);
	}
	function get_group_att($group, $module, &$atts, $field, $starting_from_modulepath = array()) {

		return $this->get_att_group_field($group, $module, $atts, $field, $starting_from_modulepath);
	}
	function get_att($module, &$atts, $field, $starting_from_modulepath = array()) {

		return $this->get_group_att(POP_ATTS_ATTRIBUTES, $module, $atts, $field, $starting_from_modulepath);
	}
	function merge_group_iterate_key_att($group, $module_or_modulepath, &$atts, $field, $value, $starting_from_modulepath = array()) {

		$this->add_att_group_field($group, $module_or_modulepath, $atts, $field, $value, $starting_from_modulepath, array('array' => true, 'merge-iterate-key' => true));
	}
	function merge_iterate_key_att($module_or_modulepath, &$atts, $field, $value, $starting_from_modulepath = array()) {

		$this->merge_group_iterate_key_att(POP_ATTS_ATTRIBUTES, $module_or_modulepath, $atts, $field, $value, $starting_from_modulepath);
	}
	function push_att($group, $module_or_modulepath, &$atts, $field, $value, $starting_from_modulepath = array()) {

		$this->add_att_group_field($group, $module_or_modulepath, $atts, $field, $value, $starting_from_modulepath, array('array' => true, 'push' => true));
	}

	//-------------------------------------------------
	// New PUBLIC Functions: Model Static Settings
	//-------------------------------------------------

	function get_immutable_settings_moduletree($module, &$atts) {

		return $this->execute_on_self_and_propagate_to_modules('get_immutable_settings', __FUNCTION__, $module, $atts);
	}

	function get_immutable_settings($module, &$atts) {

		$ret = array();
		
		if ($configuration = $this->get_immutable_configuration($module, $atts)) {
			$ret['configuration'] = $configuration;
		}

		if ($database_keys = $this->get_database_keys($module, $atts)) {
			$ret['dbkeys'] = $database_keys;
		}
		
		return $ret;
	}

	function get_immutable_configuration($module, &$atts) {
	
		$ret = array(
			GD_JS_MODULE => $module,
		);

		if ($this->get_dataloader($module)) {

			$ret[GD_JS_SETTINGSID] = $this->get_settings_id($module);
		}

		return $ret;
	}

	function get_database_keys($module, &$atts) {

		$ret = array();

		if ($dataloader_name = $this->get_dataloader($module)) {
			
			global $gd_dataload_manager;
			$dataloader = $gd_dataload_manager->get($dataloader_name);

			if ($dbkey = $dataloader->get_database_key()) {

				// Place it under "id" because it is for fetching the current object from the DB, which is found through dbObject.id
				$ret['id'] = $dbkey;
			}
		}

		global $gd_dataload_manager;
		if ($subcomponents = $this->get_dbobject_relational_successors($module)) {
			
			foreach ($subcomponents as $subcomponent_data_field => $subcomponent_dataloader_options) {

				// Watch out that, if a module has 2 subcomponents on the same data-field but different dataloaders, then
				// the dataloaders' db-key must be the same! Otherwise, the 2nd one will override the 1st one
				// Eg: a module using POSTLIST, another one using CONVERTIBLEPOSTLIST, it doesn't conflict since the db-key for both is "posts"
				$subcomponent_dataloader_names = array_keys($subcomponent_dataloader_options);
				foreach ($subcomponent_dataloader_names as $subcomponent_dataloader_name) {

					$subcomponent_dataloader = $gd_dataload_manager->get($subcomponent_dataloader_name);
					$ret[$subcomponent_data_field] = $subcomponent_dataloader->get_database_key();
				}
			}
		}

		return $ret;
	}

	//-------------------------------------------------
	// New PUBLIC Functions: Model Stateful Settings
	//-------------------------------------------------

	function get_mutableonmodel_settings_moduletree($module, &$atts) {

		return $this->execute_on_self_and_propagate_to_modules('get_mutableonmodel_settings', __FUNCTION__, $module, $atts);
	}

	function get_mutableonmodel_settings($module, &$atts) {

		$ret = array();
		
		if ($configuration = $this->get_mutableonmodel_configuration($module, $atts)) {
			$ret['configuration'] = $configuration;
		}
		
		return $ret;
	}

	function get_mutableonmodel_configuration($module, &$atts) {
	
		return array();
	}

	//-------------------------------------------------
	// New PUBLIC Functions: Stateful Settings
	//-------------------------------------------------

	function get_mutableonrequest_settings_moduletree($module, &$atts) {

		return $this->execute_on_self_and_propagate_to_modules('get_mutableonrequest_settings', __FUNCTION__, $module, $atts);
	}

	function get_mutableonrequest_settings($module, &$atts) {

		$ret = array();
		
		if ($configuration = $this->get_mutableonrequest_configuration($module, $atts)) {
			$ret['configuration'] = $configuration;
		}
		
		return $ret;
	}	

	function get_mutableonrequest_configuration($module, &$atts) {

		return array();
	}	

	//-------------------------------------------------
	// New PUBLIC Functions: Static + Stateful Data
	//-------------------------------------------------
	
	function get_datasource($module, &$atts) {

		// Each module can only return one piece of data, and it must be indicated if it static or mutableonrequest
		// Retrieving only 1 piece is needed so that its children do not get confused what data their get_data_fields applies to
		return POP_DATALOAD_DATASOURCE_MUTABLEONREQUEST;
	}

	function get_dbobject_ids($module, &$atts, $data_properties) {
	
		return array();
	}

	function get_dataloader($module) {
	
		return null;
	}

	function get_actionexecuter($module) {

		return null;
	}

	function prepare_data_properties_after_actionexecution($module, &$atts, &$data_properties) {
    
		// Do nothing
	}

	function get_data_fields($module, $atts) {
	
		return array();
	}

	function get_dbobject_relational_successors($module) {
	
		return array();
	}

	//-------------------------------------------------
	// New PUBLIC Functions: Data Properties
	//-------------------------------------------------

	function get_immutable_data_properties_datasetmoduletree($module, &$atts) {

		// The data-properties start on a dataloading module, and finish on the next dataloding module down the line
		// This way, we can collect all the data-fields that the module will need to load for its dbobjects
		return $this->execute_on_self_and_propagate_to_modules('get_immutable_data_properties_datasetmoduletree_fullsection', __FUNCTION__, $module, $atts, false);
	}

	function get_immutable_data_properties_datasetmoduletree_fullsection($module, &$atts) {

		$ret = array();

		// Only if this module has a dataloader => We are at the head nodule of the dataset section
		if ($this->get_dataloader($module)) {

			// Load the data-fields from all modules inside this section
			// And then, only for the top node, add its extra properties
			$properties = array_merge(
				$this->get_datasetmoduletree_section_flattened_data_fields($module, $atts), 
				$this->get_immutable_headdatasetmodule_data_properties($module, $atts)
			);

			if ($properties) {

				$ret[POP_CONSTANT_DATAPROPERTIES] = $properties;
			}
		}
	
		return $ret;
	}

	function get_datasetmoduletree_section_flattened_data_fields($module, $atts) {

		$ret = array();

		// Calculate the data-fields from merging them with the subcomponent modules' keys, which are data-fields too
		if ($data_fields = array_unique(array_merge(
			$this->get_data_fields($module, $atts),
			array_keys($this->get_dbobject_relational_successors($module))
		))) {

			$ret['data-fields'] = $data_fields;
		}

		// Propagate down to the components
		$this->flatten_datasetmoduletree_data_properties(__FUNCTION__, $ret, $module, $atts);

		// Propagate down to the subcomponent modules
		$this->flatten_relationaldbobject_data_properties(__FUNCTION__, $ret, $module, $atts);
		
		return $ret;
	}

	function get_immutable_headdatasetmodule_data_properties($module, &$atts) {

		// By default return nothing at the last level
		$ret = array();

		// From the State property we find out if it's Static of Stateful
		$datasource = $this->get_datasource($module, $atts);
		$ret[GD_DATALOAD_DATASOURCE] = $datasource;

		// Add the properties below either as static or mutableonrequest
		if ($datasource == POP_DATALOAD_DATASOURCE_IMMUTABLE) {

			$this->add_headdatasetmodule_data_properties($ret, $module, $atts);
		}

		return $ret;
	}

	function is_lazyload($module, $atts) {

		$vars = PoP_ModuleManager_Vars::get_vars();

		// Do not load data if doing lazy load. It can be true only if:
		// 1. Setting 'lazy-load' => true by $atts
		// 2. When querying data from another domain, since evidently we don't have that data in this site, then the load must be triggered from the client
		return $this->queries_external_domain($module, $atts) || $this->get_att($module, $atts, 'lazy-load');
	}

	protected function add_headdatasetmodule_data_properties(&$ret, $module, $atts) {

		$vars = PoP_ModuleManager_Vars::get_vars();

		// Is the component lazy-load?
		$ret[GD_DATALOAD_LAZYLOAD] = $this->is_lazyload($module, $atts);

		// Do not load data when doing lazy load, unless passing URL param ?action=loadlazy, which is needed to initialize the lazy components.
		// Do not load data for Search page (initially, before the query was submitted)
		$ret[GD_DATALOAD_SKIPDATALOAD] = 
			($vars['action'] != POP_ACTION_LOADLAZY && $ret[GD_DATALOAD_LAZYLOAD]) || 
			$this->get_att($module, $atts, 'skip-data-load');

		// Use Mock DB Object Data for the Skeleton Screen
		$ret[GD_DATALOAD_USEMOCKDBOBJECTDATA] = $this->get_att($module, $atts, 'use-mock-dbobject-data') ?? false;

		/**---------------------------------------------------------------------------------------------------------------
		 * Allow to add more stuff
		 * ---------------------------------------------------------------------------------------------------------------*/
		do_action(
			'PoP_ProcessorBase:add_headdatasetmodule_data_properties',
			array(&$ret),
			$module,
			array(&$atts),
			$this
		);
	}

	function get_mutableonmodel_data_properties_datasetmoduletree($module, &$atts) {

		return $this->execute_on_self_and_propagate_to_modules('get_mutableonmodel_data_properties_datasetmoduletree_fullsection', __FUNCTION__, $module, $atts, false);
	}

	function get_mutableonmodel_data_properties_datasetmoduletree_fullsection($module, &$atts) {

		$ret = array();

		// Only if this module has a dataloader
		if ($this->get_dataloader($module)) {

			$properties = $this->get_mutableonmodel_headdatasetmodule_data_properties($module, $atts);
			if ($properties) {

				$ret[POP_CONSTANT_DATAPROPERTIES] = $properties;
			}
		}
	
		return $ret;
	}

	function get_mutableonmodel_headdatasetmodule_data_properties($module, &$atts) {

		$ret = array();

		// Add the properties below either as static or mutableonrequest
		$datasource = $this->get_datasource($module, $atts);
		if ($datasource == POP_DATALOAD_DATASOURCE_MUTABLEONMODEL) {

			$this->add_headdatasetmodule_data_properties($ret, $module, $atts);
		}

		return $ret;
	}

	function get_mutableonrequest_data_properties_datasetmoduletree($module, &$atts) {

		return $this->execute_on_self_and_propagate_to_modules('get_mutableonrequest_data_properties_datasetmoduletree_fullsection', __FUNCTION__, $module, $atts, false);
	}

	function get_mutableonrequest_data_properties_datasetmoduletree_fullsection($module, &$atts) {

		$ret = array();

		// Only if this module has a dataloader
		if ($this->get_dataloader($module)) {

			// // Load the data-fields from all modules inside this section
			// // And then, only for the top node, add its extra properties
			// $properties = array_merge(
			// 	$this->get_mutableonrequest_data_properties_datasetmoduletree_section($module, $atts), 
			// 	$this->get_mutableonrequest_headdatasetmodule_data_properties($module, $atts)
			// );
			$properties = $this->get_mutableonrequest_headdatasetmodule_data_properties($module, $atts);

			if ($properties) {

				$ret[POP_CONSTANT_DATAPROPERTIES] = $properties;
			}
		}
	
		return $ret;
	}

	function get_mutableonrequest_headdatasetmodule_data_properties($module, &$atts) {

		$ret = array();

		// Add the properties below either as static or mutableonrequest
		$datasource = $this->get_datasource($module, $atts);
		if ($datasource == POP_DATALOAD_DATASOURCE_MUTABLEONREQUEST) {

			$this->add_headdatasetmodule_data_properties($ret, $module, $atts);
		}

		if ($dataload_source = $this->get_dataload_source($module, $atts)) {
			$ret[GD_DATALOAD_SOURCE] = $dataload_source;
		}
	
		// When loading data or execution an action, check if to validate checkpoints?
		// This is in MUTABLEONREQUEST instead of STATIC because the checkpoints can change depending on doing_post() 
		// (such as done to set-up checkpoint configuration for POP_USERSTANCE_PAGE_ADDOREDITSTANCE, or within POPUSERLOGIN_CHECKPOINTCONFIGURATION_REQUIREUSERSTATEONDOINGPOST)
		if ($checkpoint_configuration = $this->get_checkpoint_configuration($module, $atts)) {
			
			if (PoP_ModuleManager_Utils::checkpoint_validation_required($checkpoint_configuration)) {

				// Pass info for PoP Engine
				$ret[GD_DATALOAD_CHECKPOINTS] = $checkpoint_configuration['checkpoints'];
			}
		}
	
		// To trigger the actionexecuter, its own checkpoints must be successful
		if ($checkpoint_configuration = $this->get_actionexecution_checkpoint_configuration($module, $atts)) {
			
			if (PoP_ModuleManager_Utils::checkpoint_validation_required($checkpoint_configuration)) {

				// Pass info for PoP Engine
				$ret[GD_DATALOAD_ACTIONEXECUTIONCHECKPOINTS] = $checkpoint_configuration['checkpoints'];
			}
		}

		return $ret;
	}

	//-------------------------------------------------
	// New PUBLIC Functions: Data Feedback
	//-------------------------------------------------

	function get_data_feedback_datasetmoduletree($module, &$atts, $data_properties, $checkpoint_validation, $executed, $dbobjectids) {

		return $this->execute_on_self_and_propagate_to_datasetmodules('get_data_feedback_moduletree', __FUNCTION__, $module, $atts, $data_properties, $checkpoint_validation, $executed, $dbobjectids);
	}

	function get_data_feedback_moduletree($module, &$atts, $data_properties, $checkpoint_validation, $executed, $dbobjectids) {

		$ret = array();

		if ($feedback = $this->get_data_feedback($module, $atts, $data_properties, $checkpoint_validation, $executed, $dbobjectids)) {

			$ret[POP_CONSTANT_FEEDBACK] = $feedback;
		}
	
		return $ret;
	}

	function get_data_feedback($module, &$atts, $data_properties, $checkpoint_validation, $executed, $dbobjectids) {
	
		return array();
	}

	function get_data_feedback_interreferenced_modulepath($module, &$atts) {
	
		return null;
	}

	//-------------------------------------------------
	// Background URLs
	//-------------------------------------------------

	function get_backgroundurls_mergeddatasetmoduletree($module, $atts, $data_properties, $checkpoint_validation, $executed, $dbobjectids) {

		return $this->execute_on_self_and_merge_with_datasetmodules('get_backgroundurls', __FUNCTION__, $module, $atts, $data_properties, $checkpoint_validation, $executed, $dbobjectids);
	}

	function get_backgroundurls($module, $atts, $data_properties, $checkpoint_validation, $executed, $dbobjectids) {
	
		return array();
	}

	//-------------------------------------------------
	// Dataset Meta
	//-------------------------------------------------

	function get_datasetmeta($module, &$atts, $data_properties, $checkpoint_validation, $executed, $dbobjectids) {

		$ret = array();

		if ($query_multidomain_urls = $this->get_dataload_multidomain_sources($module, $atts)) {

			$ret['multidomaindataloadsources'] = $query_multidomain_urls;
		}
		elseif ($dataload_source = $data_properties[GD_DATALOAD_SOURCE]) {

			$ret['dataloadsource'] = $dataload_source;
		}

		if ($data_properties[GD_DATALOAD_LAZYLOAD]) {

			$ret['lazyload'] = true;
		}

		return $ret;
	}

	//-------------------------------------------------
	// Others
	//-------------------------------------------------

	function get_relevant_page($module, &$atts) {

		return null;
	}

	function get_checkpoint_configuration($module, &$atts) {

		if ($page_id = $this->get_relevant_page($module, $atts)) {
			
			return PoP_ModuleManager_Utils::get_checkpoint_configuration($page_id);
		}
		
		return null;
	}

	protected function get_actionexecution_checkpoint_configuration($module, &$atts) {
		
		// By default, validate that we are doing POST and that the ?actionpath corresponds to the given $module
		return PoP_Engine_CheckpointUtils::get_checkpoint_configuration(POPENGINE_CHECKPOINTCONFIGURATION_ACTIONPATHISMODULE_POST);
	}

	function get_dataload_source($module, $atts) {

		// Because a component can interact with itself by adding ?modulepaths=...,
		// then, by default, we simply set the dataload source to point to itself!
		$stringified_module_propagation_current_path = PoP_ModulePathManager_Utils::get_stringified_module_propagation_current_path($module);
		$ret = add_query_arg(
			GD_URLPARAM_MODULEFILTER,
			POP_MODULEFILTER_MODULEPATHS,
			add_query_arg(
				GD_URLPARAM_MODULEPATHS.'[]',
				$stringified_module_propagation_current_path,
				PoP_ModuleManager_Utils::get_current_url()
			)
		);

		// Allow to add extra modulepaths set from above
		if ($extra_module_paths = $this->get_att($module, $atts, 'dataload-source-add-modulepaths')) {

			foreach ($extra_module_paths as $modulepath) {

				$ret = add_query_arg(
					GD_URLPARAM_MODULEPATHS.'[]',
					PoP_ModulePathManager_Utils::stringify_module_path($modulepath),
					$ret
				);
			}
		}

		// Add the actionpath too
		if ($this->get_actionexecuter($module)) {

			$ret = add_query_arg(
				GD_URLPARAM_ACTIONPATH,
				$stringified_module_propagation_current_path,
				$ret
			);
		}

		// Add the format to the query url
		if ($this instanceof FormattableModule) {

			if ($format = $this->get_format($module)) {

				$ret = add_query_arg(GD_URLPARAM_FORMAT, $format, $ret);
			}
		}

		return $ret;
	}

	function get_dataload_multidomain_sources($module, $atts) {

		if ($sources = $this->get_att($module, $atts, 'dataload-multidomain-sources')) {

			return array_map(
				function($source) use ($module) {
					return add_query_arg(
						GD_URLPARAM_MODULEFILTER,
						POP_MODULEFILTER_HEADMODULE,
						add_query_arg(
							GD_URLPARAM_HEADMODULE,
							$module,
							$source
						)
					);
				}, 
				$sources
			);
		}

		return array();
	}

	function queries_external_domain($module, $atts) {

		if ($sources = $this->get_dataload_multidomain_sources($module, $atts)) {
			
			$domain = get_site_url();
			foreach ($sources as $source) {

				if (substr($source, 0, strlen($domain)) != $domain) {

					return true;
				}
			}
		}

		return false;
	}

	function is_multidomain($module, $atts) {

		if (!$this->queries_external_domain($module, $atts)) {

			return false;
		}

		$multidomain_urls = $this->get_dataload_multidomain_sources($module, $atts);
		return is_array($multidomain_urls) && count($multidomain_urls) >= 2;
	}

	function get_modules_to_propagate_data_properties($module) {

		return $this->get_modulecomponents($module, array(POP_MODULECOMPONENT_MODULES));
	}

	protected function flatten_datasetmoduletree_data_properties($propagate_fn, &$ret, $module, $atts) {
	
		global $pop_module_processor_manager;
		
		// Exclude the subcomponent modules here
		if ($submodules = $this->get_modules_to_propagate_data_properties($module)) {
			
			foreach ($submodules as $submodule) {

				$submodule_processor = $pop_module_processor_manager->get_processor($submodule);

				// Propagate only if the submodule doesn't have a dataloader. If it does, this is the end of the data line, and the submodule is the beginning of a new datasetmoduletree
				if (!$submodule_processor->get_dataloader($submodule, $atts[$module][POP_ATTS_MODULES])) {
					
					if ($submodule_ret = $submodule_processor->$propagate_fn($submodule, $atts[$module][POP_ATTS_MODULES])) {

						// array_merge_recursive => data-fields from different sidebar-components can be integrated all together 
						$ret = array_merge_recursive(
							$ret,
							$submodule_ret
						);
					}
				}
			}
			
			// Array Merge appends values when under numeric keys, so we gotta filter duplicates out
			if ($ret['data-fields']) {

				$ret['data-fields'] = array_values(array_unique($ret['data-fields']));
			}
		}
	}

	protected function flatten_relationaldbobject_data_properties($propagate_fn, &$ret, $module, $atts) {
	
		global $pop_module_processor_manager;
		
		// If it has subcomponent modules, integrate them under 'subcomponents'
		foreach ($this->get_dbobject_relational_successors($module) as $subcomponent_data_field => $subcomponent_dataloader_options) {
			foreach ($subcomponent_dataloader_options as $subcomponent_dataloader_name => $subcomponent_modules) {

				$subcomponent_modules_data_properties = array(
					'data-fields' => array(),
					'subcomponents' => array()
				);
				foreach ($subcomponent_modules as $subcomponent_module) {

					if ($subcomponent_module_data_properties = $pop_module_processor_manager->get_processor($subcomponent_module)->$propagate_fn($subcomponent_module, $atts[$subcomponent_module][POP_ATTS_MODULES])) {

						$subcomponent_modules_data_properties = array_merge_recursive(
							$subcomponent_modules_data_properties,
							$subcomponent_module_data_properties
						);
					}
				}
				
				$ret['subcomponents'][$subcomponent_data_field][$subcomponent_dataloader_name] = $ret['subcomponents'][$subcomponent_data_field][$subcomponent_dataloader_name] ?? array();
				if ($subcomponent_modules_data_properties['data-fields']) {

					$subcomponent_modules_data_properties['data-fields'] = array_unique($subcomponent_modules_data_properties['data-fields']);
					
					$ret['subcomponents'][$subcomponent_data_field][$subcomponent_dataloader_name]['data-fields'] = $ret['subcomponents'][$subcomponent_data_field][$subcomponent_dataloader_name]['data-fields'] ?? array();
					$ret['subcomponents'][$subcomponent_data_field][$subcomponent_dataloader_name]['data-fields'] = array_unique(
						array_merge(
							$ret['subcomponents'][$subcomponent_data_field][$subcomponent_dataloader_name]['data-fields'],
							$subcomponent_modules_data_properties['data-fields']
						)
					);
				}

				if ($subcomponent_modules_data_properties['subcomponents']) {
					
					$ret['subcomponents'][$subcomponent_data_field][$subcomponent_dataloader_name]['subcomponents'] = $ret['subcomponents'][$subcomponent_data_field][$subcomponent_dataloader_name]['subcomponents'] ?? array();
					$ret['subcomponents'][$subcomponent_data_field][$subcomponent_dataloader_name]['subcomponents'] = array_merge_recursive(
						$ret['subcomponents'][$subcomponent_data_field][$subcomponent_dataloader_name]['subcomponents'],
						$subcomponent_modules_data_properties['subcomponents']
					);
				}
			}
		}
	}



	//-------------------------------------------------
	// New PUBLIC Functions: Static Data
	//-------------------------------------------------

	function get_model_supplementary_dbobjectdata_moduletree($module, &$atts) {
	
		return $this->execute_on_self_and_merge_with_modules('get_model_supplementary_dbobjectdata', __FUNCTION__, $module, $atts);
	}

	function get_model_supplementary_dbobjectdata($module, &$atts) {
	
		return array();
	}

	//-------------------------------------------------
	// New PUBLIC Functions: Stateful Data
	//-------------------------------------------------

	function get_mutableonrequest_supplementary_dbobjectdata_moduletree($module, &$atts) {
	
		return $this->execute_on_self_and_merge_with_modules('get_mutableonrequest_supplementary_dbobjectdata', __FUNCTION__, $module, $atts);
	}

	function get_mutableonrequest_supplementary_dbobjectdata($module, &$atts) {
	
		return array();
	}

	private function get_modulecomponents($module, $components = array()) {

		global $pop_module_processor_manager;

		if (empty($components)) {
			$components = array(
				POP_MODULECOMPONENT_MODULES, 
				POP_MODULECOMPONENT_DBOBJECTRELATIONALSUCCESSORMODULES,
			);
		}

		$ret = array();
		
		if (in_array(POP_MODULECOMPONENT_MODULES, $components)) {

			$ret = array_unique(
				array_merge(
					$this->get_modules($module),
					$ret
				)
			);
		}

		if (in_array(POP_MODULECOMPONENT_DBOBJECTRELATIONALSUCCESSORMODULES, $components)) {

			foreach ($this->get_dbobject_relational_successors($module) as $subcomponent_data_field => $subcomponent_dataloader_options) {
				foreach ($subcomponent_dataloader_options as $subcomponent_dataloader_name => $subcomponent_modules) {

					$ret = array_values(array_unique(array_merge(
						$subcomponent_modules,
						$ret
					)));
				}
			}
		}

		return $ret;
	}
}