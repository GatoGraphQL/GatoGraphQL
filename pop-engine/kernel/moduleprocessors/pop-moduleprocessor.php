<?php
namespace PoP\Engine;

abstract class ModuleProcessorBase {

	use ModulePathProcessorTrait;

	function __construct() {

		$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();
		$moduleprocessor_manager->add($this, $this->get_modules_to_process());
	}

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------
	abstract function get_modules_to_process();


	

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

	function execute_init_props_moduletree($eval_self_fn, $get_props_for_descendant_modules_fn, $get_props_for_descendant_datasetmodules_fn, $propagate_fn, $module, &$props, $wildcard_props_to_propagate, $targetted_props_to_propagate) {

		$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();
		
		// Initialize. If this module had been added props, then use them already
		// 1st element to merge: the general props for this module passed down the line
		// 2nd element to merge: the props set exactly to the path. They have more priority, that's why they are 2nd
		// It may contain more than one group (POP_PROPS_ATTRIBUTES). Eg: maybe also POP_PROPS_JSMETHODS
		$props[$module] = array_merge_recursive(
			$targetted_props_to_propagate[$module] ?? array(),
			$props[$module] ?? array()
		);

		// The module must be at the head of the $props array passed to all `init_model_props`, so that function `get_path_head_module` can work
		$module_props = array(
			$module => &$props[$module],
		);

		// If ancestor modules set general props, or props targetted at this current module, then add them to the current module props
		foreach ($wildcard_props_to_propagate as $key => $value) {
			$this->set_prop($module, $module_props, $key, $value);
		}

		// Before initiating the current level, set the children attributes on the array, so that doing ->set_prop, ->append_prop, etc, keeps working
		$module_props[$module][POP_PROPS_DESCENDANTATTRIBUTES] = array_merge(
			$module_props[$module][POP_PROPS_DESCENDANTATTRIBUTES] ?? array(),
			$targetted_props_to_propagate ?? array()
		);

		// Initiate the current level. 
		$this->$eval_self_fn($module, $module_props);

		// Immediately after initiating the current level, extract all child attributes out from the $props, and place it on the other variable
		$targetted_props_to_propagate = $module_props[$module][POP_PROPS_DESCENDANTATTRIBUTES];
		unset($module_props[$module][POP_PROPS_DESCENDANTATTRIBUTES]);

		// But because modules can't repeat themselves down the line (or it would generate an infinite loop), then can remove the current module from the targeted props
		unset($targetted_props_to_propagate[$module]);

		// Allow the $module to add general props for all its descendant modules
		$wildcard_props_to_propagate = array_merge(
			$wildcard_props_to_propagate,
			$this->$get_props_for_descendant_modules_fn($module, $module_props)
		);
		
		// Propagate
		$modulefilter_manager = ModuleFilterManager_Factory::get_instance();
		$submodules = $this->get_descendant_modules($module);
		$submodules = $modulefilter_manager->remove_excluded_submodules($module, $submodules);

		// This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
		$module_path_manager = ModulePathManager_Factory::get_instance();
		$module_path_manager->prepare_for_propagation($module);
		if ($submodules) {
			
			$props[$module][POP_PROPS_MODULES] = $props[$module][POP_PROPS_MODULES] ?? array();
			foreach ($submodules as $submodule) {

				$submodule_processor = $moduleprocessor_manager->get_processor($submodule);
				$submodule_wildcard_props_to_propagate = $wildcard_props_to_propagate;

				// If the submodule belongs to the same dataset (meaning that it doesn't have a dataloader of its own), then set the shared attributies for the same-dataset modules
				if (!$submodule_processor->get_dataloader($submodule)) {

					$submodule_wildcard_props_to_propagate = array_merge(
						$submodule_wildcard_props_to_propagate,
						$this->$get_props_for_descendant_datasetmodules_fn($module, $module_props)
					);
				}

				$submodule_processor->$propagate_fn($submodule, $props[$module][POP_PROPS_MODULES], $submodule_wildcard_props_to_propagate, $targetted_props_to_propagate);
			}
		}
		$module_path_manager->restore_from_propagation($module);
	}

	function init_model_props_moduletree($module, &$props, $wildcard_props_to_propagate, $targetted_props_to_propagate) {

		$this->execute_init_props_moduletree('init_model_props', 'get_model_props_for_descendant_modules', 'get_model_props_for_descendant_datasetmodules', __FUNCTION__, $module, $props, $wildcard_props_to_propagate, $targetted_props_to_propagate);
	}

	function get_model_props_for_descendant_modules($module, &$props) {

		$ret = array();

		// If we set property 'skip-data-load' on any module, not just dataset, spread it down to its children so it reaches its contained dataset submodules
		$skip_data_load = $this->get_prop($module, $props, 'skip-data-load');
		if (!is_null($skip_data_load)) {

			$ret['skip-data-load'] = $skip_data_load;
		}

		return $ret;
	}

	function get_model_props_for_descendant_datasetmodules($module, &$props) {

		$ret = array();

		// If this module loads data, then add several properties
		if ($dataloader_name = $this->get_dataloader($module)) {
			
			if ($this->queries_external_domain($module, $props)) {

				$ret['external-domain'] = true;
			}

			// If it is multidomain, add a flag for inner layouts to know and react
			if ($this->is_multidomain($module, $props)) {

				$ret['multidomain'] = true;
			}
		}

		return $ret;
	}
	
	function init_model_props($module, &$props) {

		// If it is a dataloader module, then set all the props related to data
		if ($dataloader_name = $this->get_dataloader($module)) {

			$vars = Engine_Vars::get_vars();

			// If it is multidomain, add a flag for inner layouts to know and react
			if ($this->is_multidomain($module, $props)) {
			
				// $this->add_general_prop($props, 'is-multidomain', true);
				$this->append_prop($module, $props, 'class', 'pop-multidomain');
			}
		}

		// Set property "succeeding-dataloader" on every module, so they know which is their dataloader, needed to calculate the subcomponent data-fields when using dataloader "*"
		if ($dataloader_name) {
			$this->set_prop($module, $props, 'succeeding-dataloader', $dataloader_name);
		}
		// Get the prop assigned to the module by its ancestor
		else {
			$dataloader_name = $this->get_prop($module, $props, 'succeeding-dataloader');
		}
		if ($dataloader_name) {

			// Set the property "succeeding-dataloader" on all descendants: the same dataloader for all submodules, and the explicit one (or get the default one for "*") for relational objects
			foreach ($this->get_modules($module) as $submodule) {

				$this->set_prop($submodule, $props, 'succeeding-dataloader', $dataloader_name);
			}
			foreach ($this->get_dbobject_relational_successors($module) as $subcomponent_data_field => $subcomponent_dataloader_options) {
				foreach ($subcomponent_dataloader_options as $subcomponent_dataloader_name => $subcomponent_modules) {

					// If the subcomponent dataloader is not explicitly set in `get_dbobject_relational_successors`, then retrieve it now from the current dataloader's fieldprocessor
					if ($subcomponent_dataloader_name == POP_CONSTANT_SUBCOMPONENTDATALOADER_DEFAULTFROMFIELD) {

						$subcomponent_dataloader_name = DataloadUtils::get_default_dataloader_name_from_subcomponent_data_field($dataloader_name, $subcomponent_data_field);
					}

					// If passing a subcomponent fieldname that doesn't exist to the API, then $subcomponent_dataloader_name will be empty
					if ($subcomponent_dataloader_name) {

						foreach ($subcomponent_modules as $subcomponent_module) {
							$this->set_prop($subcomponent_module, $props, 'succeeding-dataloader', $subcomponent_dataloader_name);
						}
					}
				}
			}
		}

		/**---------------------------------------------------------------------------------------------------------------
		 * Allow to add more stuff
		 * ---------------------------------------------------------------------------------------------------------------*/
		do_action(
			'\PoP\Engine\ModuleProcessorBase:init_model_props',
			array(&$props),
			$module,
			$this
		);
	}

	function init_request_props_moduletree($module, &$props, $wildcard_props_to_propagate, $targetted_props_to_propagate) {

		$this->execute_init_props_moduletree('init_request_props', 'get_request_props_for_descendant_modules', 'get_request_props_for_descendant_datasetmodules', __FUNCTION__, $module, $props, $wildcard_props_to_propagate, $targetted_props_to_propagate);
	}

	function get_request_props_for_descendant_modules($module, &$props) {

		return array();
	}

	function get_request_props_for_descendant_datasetmodules($module, &$props) {

		return array();
	}
	
	function init_request_props($module, &$props) {

		/**---------------------------------------------------------------------------------------------------------------
		 * Allow to add more stuff
		 * ---------------------------------------------------------------------------------------------------------------*/
		do_action(
			'\PoP\Engine\ModuleProcessorBase:init_request_props',
			array(&$props),
			$module,
			$this
		);
	}

	//-------------------------------------------------
	// PRIVATE Functions: Atts
	//-------------------------------------------------

	private function get_path_head_module(&$props) {

		// From the root of the $props we obtain the current module
		reset($props);
		return key($props);
	}

	private function is_descendant_module($module_or_modulepath, &$props) {

		// If it is an array, then we're passing the path to find the module to which to add the att
		if (!is_array($module_or_modulepath)) {

			// From the root of the $props we obtain the current module
			$module = $this->get_path_head_module($props);

			// If the module were we are adding the att, is this same module, then we are already at the path
			// If it is not, then go down one level to that module
			return ($module !== $module_or_modulepath);
		}

		return false;
	}

	protected function get_modulepath($module_or_modulepath, &$props) {

		if (!$props) {
			return array();
		}

		// From the root of the $props we obtain the current module
		$module = $this->get_path_head_module($props);
		
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

	protected function add_prop_group_field($group, $module_or_modulepath, &$props, $field, $value, $starting_from_modulepath = array(), $options = array()) {

		// Iterate down to the submodule, which must be an array of modules
		if ($starting_from_modulepath) {

			// Attach the current module, which is not included on "starting_from", to step down this level too
			$module = $this->get_path_head_module($props);
			array_unshift($starting_from_modulepath, $module);

			// Descend into the path to find the module for which to add the att
			$module_props = &$props;
			foreach ($starting_from_modulepath as $pathlevel) {

				$last_module_props = &$module_props;
				$last_module = $pathlevel;

				$module_props[$pathlevel][POP_PROPS_MODULES] = $module_props[$pathlevel][POP_PROPS_MODULES] ?? array();
				$module_props = &$module_props[$pathlevel][POP_PROPS_MODULES];
			}

			// This is the new $props, so it starts from here
			// Save the current $props, and restore later, to make sure this array has only one key, otherwise it will not work
			$current_props = $props;
			$props = array(
				$last_module => &$last_module_props[$last_module]
			);
		}

		// If the module is a string, there are 2 possibilities: either it is the current module or not
		// If it is not, then it is a descendant module, which will appear at some point down the path.
		// For that case, simply save it under some other entry, from where it will propagate the props later on in `init_model_props_moduletree`
		if ($this->is_descendant_module($module_or_modulepath, $props)) {

			// It is a child module
			$att_module = $module_or_modulepath;

			// From the root of the $props we obtain the current module
			$module = $this->get_path_head_module($props);

			// Set the child attributes under a different entry
			$props[$module][POP_PROPS_DESCENDANTATTRIBUTES] = $props[$module][POP_PROPS_DESCENDANTATTRIBUTES] ?? array();
			$module_props = &$props[$module][POP_PROPS_DESCENDANTATTRIBUTES];
		}
		else {

			// Calculate the path to iterate down
			$modulepath = $this->get_modulepath($module_or_modulepath, $props);

			// Extract the lastlevel, that's the module to with to add the att
			$att_module = array_pop($modulepath);

			// Descend into the path to find the module for which to add the att
			$module_props = &$props;
			foreach ($modulepath as $pathlevel) {

				$module_props[$pathlevel][POP_PROPS_MODULES] = $module_props[$pathlevel][POP_PROPS_MODULES] ?? array();
				$module_props = &$module_props[$pathlevel][POP_PROPS_MODULES];
			}
		}

		// Now can proceed to add the att
		$module_props[$att_module][$group] = $module_props[$att_module][$group] ?? array();

		if ($options['append']) {

			$module_props[$att_module][$group][$field] = $module_props[$att_module][$group][$field] ?? '';
			$module_props[$att_module][$group][$field] .= ' ' . $value;
		}
		elseif ($options['array']) {
			
			$module_props[$att_module][$group][$field] = $module_props[$att_module][$group][$field] ?? array();
			if ($options['merge']) {
				$module_props[$att_module][$group][$field] = array_merge(
					$module_props[$att_module][$group][$field],
					$value
				);
			}
			elseif ($options['merge-iterate-key']) {
				foreach ($value as $value_key => $value_value) {
					if (!$module_props[$att_module][$group][$field][$value_key]) {
						$module_props[$att_module][$group][$field][$value_key] = array();
					}
					// Doing array_unique, because in the NotificationPreviewLayout, different layouts might impose a JS down the road, many times, and these get duplicated
					$module_props[$att_module][$group][$field][$value_key] = array_unique(
						array_merge(
							$module_props[$att_module][$group][$field][$value_key],
							$value_value
						)
					);
				}
			}
			elseif ($options['push']) {
				array_push($module_props[$att_module][$group][$field], $value);
			}
		}
		else {
			// If already set, then do nothing
			if (!isset($module_props[$att_module][$group][$field])) {
				$module_props[$att_module][$group][$field] = $value;
			}
		}

		// Restore original $props
		if ($starting_from_modulepath) {

			$props = $current_props;
		}
	}
	protected function get_prop_group_field($group, $module, &$props, $field, $starting_from_modulepath = array()) {

		$group = $this->get_prop_group($group, $module, $props, $starting_from_modulepath);
		return $group[$field];
	}
	protected function get_prop_group($group, $module, &$props, $starting_from_modulepath = array()) {

		if (!$props) {
			return array();
		}

		$module_props = &$props;
		foreach ($starting_from_modulepath as $pathlevel) {

			$module_props = &$module_props[$pathlevel][POP_PROPS_MODULES];
		}

		return $module_props[$module][$group] ?? array();
	}
	protected function add_group_prop($group, $module_or_modulepath, &$props, $field, $value, $starting_from_modulepath = array()) {

		$this->add_prop_group_field($group, $module_or_modulepath, $props, $field, $value, $starting_from_modulepath);
	}
	function set_prop($module_or_modulepath, &$props, $field, $value, $starting_from_modulepath = array()) {

		$this->add_group_prop(POP_PROPS_ATTRIBUTES, $module_or_modulepath, $props, $field, $value, $starting_from_modulepath);
	}
	function append_group_prop($group, $module_or_modulepath, &$props, $field, $value, $starting_from_modulepath = array()) {

		$this->add_prop_group_field($group, $module_or_modulepath, $props, $field, $value, $starting_from_modulepath, array('append' => true));
	}
	function append_prop($module_or_modulepath, &$props, $field, $value, $starting_from_modulepath = array()) {

		$this->append_group_prop(POP_PROPS_ATTRIBUTES, $module_or_modulepath, $props, $field, $value, $starting_from_modulepath);
	}
	function merge_group_prop($group, $module_or_modulepath, &$props, $field, $value, $starting_from_modulepath = array()) {

		$this->add_prop_group_field($group, $module_or_modulepath, $props, $field, $value, $starting_from_modulepath, array('array' => true, 'merge' => true));
	}
	function merge_prop($module_or_modulepath, &$props, $field, $value, $starting_from_modulepath = array()) {

		$this->merge_group_prop(POP_PROPS_ATTRIBUTES, $module_or_modulepath, $props, $field, $value, $starting_from_modulepath);
	}
	function get_group_prop($group, $module, &$props, $field, $starting_from_modulepath = array()) {

		return $this->get_prop_group_field($group, $module, $props, $field, $starting_from_modulepath);
	}
	function get_prop($module, &$props, $field, $starting_from_modulepath = array()) {

		return $this->get_group_prop(POP_PROPS_ATTRIBUTES, $module, $props, $field, $starting_from_modulepath);
	}
	function merge_group_iterate_key_prop($group, $module_or_modulepath, &$props, $field, $value, $starting_from_modulepath = array()) {

		$this->add_prop_group_field($group, $module_or_modulepath, $props, $field, $value, $starting_from_modulepath, array('array' => true, 'merge-iterate-key' => true));
	}
	function merge_iterate_key_prop($module_or_modulepath, &$props, $field, $value, $starting_from_modulepath = array()) {

		$this->merge_group_iterate_key_prop(POP_PROPS_ATTRIBUTES, $module_or_modulepath, $props, $field, $value, $starting_from_modulepath);
	}
	function push_prop($group, $module_or_modulepath, &$props, $field, $value, $starting_from_modulepath = array()) {

		$this->add_prop_group_field($group, $module_or_modulepath, $props, $field, $value, $starting_from_modulepath, array('array' => true, 'push' => true));
	}

	//-------------------------------------------------
	// New PUBLIC Functions: Model Static Settings
	//-------------------------------------------------

	function get_immutable_settings_moduletree($module, &$props) {

		return $this->execute_on_self_and_propagate_to_modules('get_immutable_settings', __FUNCTION__, $module, $props);
	}

	function get_immutable_settings($module, &$props) {

		$ret = array();
		
		if ($configuration = $this->get_immutable_configuration($module, $props)) {
			$ret['configuration'] = $configuration;
		}

		if ($database_keys = $this->get_database_keys($module, $props)) {
			$ret['dbkeys'] = $database_keys;
		}
		
		return $ret;
	}

	function get_immutable_configuration($module, &$props) {
	
		$ret = array(
			GD_JS_MODULE => $module,
		);

		if ($this->get_dataloader($module)) {

			$ret[GD_JS_SETTINGSID] = $this->get_settings_id($module);
		}

		return $ret;
	}

	function get_database_keys($module, &$props) {

		$ret = array();

		if ($dataloader_name = $this->get_dataloader($module)) {
			
			$dataloader_manager = Dataloader_Manager_Factory::get_instance();
			$dataloader = $dataloader_manager->get($dataloader_name);

			if ($dbkey = $dataloader->get_database_key()) {

				// Place it under "id" because it is for fetching the current object from the DB, which is found through dbObject.id
				$ret['id'] = $dbkey;
			}
		}

		$dataloader_manager = Dataloader_Manager_Factory::get_instance();
		if ($subcomponents = $this->get_dbobject_relational_successors($module)) {
			
			// This prop is set for both dataloading and non-dataloading modules
			$dataloader_name = $this->get_prop($module, $props, 'succeeding-dataloader');
			foreach ($subcomponents as $subcomponent_data_field => $subcomponent_dataloader_options) {

				// Watch out that, if a module has 2 subcomponents on the same data-field but different dataloaders, then
				// the dataloaders' db-key must be the same! Otherwise, the 2nd one will override the 1st one
				// Eg: a module using POSTLIST, another one using CONVERTIBLEPOSTLIST, it doesn't conflict since the db-key for both is "posts"
				$subcomponent_dataloader_names = array_keys($subcomponent_dataloader_options);
				foreach ($subcomponent_dataloader_names as $subcomponent_dataloader_name) {

					// If the subcomponent dataloader is not explicitly set in `get_dbobject_relational_successors`, then retrieve it now from the current dataloader's fieldprocessor
					if ($subcomponent_dataloader_name == POP_CONSTANT_SUBCOMPONENTDATALOADER_DEFAULTFROMFIELD) {
						
						$subcomponent_dataloader_name = DataloadUtils::get_default_dataloader_name_from_subcomponent_data_field($dataloader_name, $subcomponent_data_field);
					}

					// If passing a subcomponent fieldname that doesn't exist to the API, then $subcomponent_dataloader_name will be empty
					if ($subcomponent_dataloader_name) {

						$subcomponent_dataloader = $dataloader_manager->get($subcomponent_dataloader_name);
						$ret[$subcomponent_data_field] = $subcomponent_dataloader->get_database_key();
					}
				}
			}
		}

		return $ret;
	}

	//-------------------------------------------------
	// New PUBLIC Functions: Model Static Settings
	//-------------------------------------------------

	function get_immutable_settings_datasetmoduletree($module, &$props) {

		$options = array(
			'only-execute-on-dataloading-modules' => true,
		);
		return $this->execute_on_self_and_propagate_to_modules('get_immutable_datasetsettings', __FUNCTION__, $module, $props, true, $options);
	}

	function get_immutable_datasetsettings($module, &$props) {

		$ret = array();
		
		if ($database_keys = $this->get_dataset_database_keys($module, $props)) {
			$ret['dbkeys'] = $database_keys;
		}
		
		return $ret;
	}

	protected function has_no_dataloader($module) {

		$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();
		return is_null($moduleprocessor_manager->get_processor($module)->get_dataloader($module));
	}

	function add_to_dataset_database_keys($module, &$props, $path, &$ret) {

		// Add the current module's dbkeys
		$dbkeys = $this->get_database_keys($module, $props);
		foreach ($dbkeys as $field => $dbkey) {
			$ret[implode('.', array_merge($path, [$field]))] = $dbkey;
		}

		// Propagate to all submodules which have no dataloader
		$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();

		$module_path_manager = ModulePathManager_Factory::get_instance();
		$module_path_manager->prepare_for_propagation($module);
		foreach ($this->get_dbobject_relational_successors($module) as $subcomponent_data_field => $subcomponent_dataloader_options) {

			foreach ($subcomponent_dataloader_options as $subcomponent_dataloader_name => $subcomponent_modules) {
				
				// Only modules without dataloader
				$subcomponent_modules = array_filter($subcomponent_modules, array($this, 'has_no_dataloader'));
				foreach ($subcomponent_modules as $subcomponent_module) {
					$moduleprocessor_manager->get_processor($subcomponent_module)->add_to_dataset_database_keys($subcomponent_module, $props[$module][POP_PROPS_MODULES], array_merge($path, [$subcomponent_data_field]), $ret);
				}
			}
		}

		// Only modules without dataloader
		$submodules = array_filter($this->get_modules($module), array($this, 'has_no_dataloader'));
		foreach ($submodules as $submodule) {

			$moduleprocessor_manager->get_processor($submodule)->add_to_dataset_database_keys($submodule, $props[$module][POP_PROPS_MODULES], $path, $ret);
		}
		$module_path_manager->restore_from_propagation($module);
	}

	function get_dataset_database_keys($module, &$props) {

		$ret = array();
		$this->add_to_dataset_database_keys($module, $props, array(), $ret);
		return $ret;
	}

	//-------------------------------------------------
	// New PUBLIC Functions: Model Stateful Settings
	//-------------------------------------------------

	function get_mutableonmodel_settings_moduletree($module, &$props) {

		return $this->execute_on_self_and_propagate_to_modules('get_mutableonmodel_settings', __FUNCTION__, $module, $props);
	}

	function get_mutableonmodel_settings($module, &$props) {

		$ret = array();
		
		if ($configuration = $this->get_mutableonmodel_configuration($module, $props)) {
			$ret['configuration'] = $configuration;
		}
		
		return $ret;
	}

	function get_mutableonmodel_configuration($module, &$props) {
	
		return array();
	}

	//-------------------------------------------------
	// New PUBLIC Functions: Stateful Settings
	//-------------------------------------------------

	function get_mutableonrequest_settings_moduletree($module, &$props) {

		return $this->execute_on_self_and_propagate_to_modules('get_mutableonrequest_settings', __FUNCTION__, $module, $props);
	}

	function get_mutableonrequest_settings($module, &$props) {

		$ret = array();
		
		if ($configuration = $this->get_mutableonrequest_configuration($module, $props)) {
			$ret['configuration'] = $configuration;
		}
		
		return $ret;
	}	

	function get_mutableonrequest_configuration($module, &$props) {

		return array();
	}	

	//-------------------------------------------------
	// New PUBLIC Functions: Static + Stateful Data
	//-------------------------------------------------
	
	function get_datasource($module, &$props) {

		// Each module can only return one piece of data, and it must be indicated if it static or mutableonrequest
		// Retrieving only 1 piece is needed so that its children do not get confused what data their get_data_fields applies to
		return POP_DATALOAD_DATASOURCE_MUTABLEONREQUEST;
	}

	function get_dbobject_ids($module, &$props, &$data_properties) {
	
		return array();
	}

	function get_dataloader($module) {
	
		return null;
	}

	function get_actionexecuter($module) {

		return null;
	}

	function prepare_data_properties_after_actionexecution($module, &$props, &$data_properties) {
    
		// Do nothing
	}

	function get_data_fields($module, $props) {
	
		return array();
	}

	function get_dbobject_relational_successors($module) {
	
		return array();
	}

	//-------------------------------------------------
	// New PUBLIC Functions: Data Properties
	//-------------------------------------------------

	function get_immutable_data_properties_datasetmoduletree($module, &$props) {

		// The data-properties start on a dataloading module, and finish on the next dataloding module down the line
		// This way, we can collect all the data-fields that the module will need to load for its dbobjects
		return $this->execute_on_self_and_propagate_to_modules('get_immutable_data_properties_datasetmoduletree_fullsection', __FUNCTION__, $module, $props, false);
	}

	function get_immutable_data_properties_datasetmoduletree_fullsection($module, &$props) {

		$ret = array();

		// Only if this module has a dataloader => We are at the head nodule of the dataset section
		if ($this->get_dataloader($module)) {

			// Load the data-fields from all modules inside this section
			// And then, only for the top node, add its extra properties
			$properties = array_merge(
				$this->get_datasetmoduletree_section_flattened_data_fields($module, $props), 
				$this->get_immutable_headdatasetmodule_data_properties($module, $props)
			);

			if ($properties) {

				$ret[POP_CONSTANT_DATAPROPERTIES] = $properties;
			}
		}
	
		return $ret;
	}

	function get_datasetmoduletree_section_flattened_data_fields($module, $props) {

		$ret = array();

		// Calculate the data-fields from merging them with the subcomponent modules' keys, which are data-fields too
		if ($data_fields = array_unique(array_merge(
			$this->get_data_fields($module, $props),
			array_keys($this->get_dbobject_relational_successors($module))
		))) {

			$ret['data-fields'] = $data_fields;
		}

		// Propagate down to the components
		$this->flatten_datasetmoduletree_data_properties(__FUNCTION__, $ret, $module, $props);

		// Propagate down to the subcomponent modules
		$this->flatten_relationaldbobject_data_properties(__FUNCTION__, $ret, $module, $props);
		
		return $ret;
	}

	function get_immutable_headdatasetmodule_data_properties($module, &$props) {

		// By default return nothing at the last level
		$ret = array();

		// From the State property we find out if it's Static of Stateful
		$datasource = $this->get_datasource($module, $props);
		$ret[GD_DATALOAD_DATASOURCE] = $datasource;

		// Add the properties below either as static or mutableonrequest
		if ($datasource == POP_DATALOAD_DATASOURCE_IMMUTABLE) {

			$this->add_headdatasetmodule_data_properties($ret, $module, $props);
		}

		return $ret;
	}

	function is_lazyload($module, $props) {

		$vars = Engine_Vars::get_vars();

		// Do not load data if doing lazy load. It can be true only if:
		// 1. Setting 'lazy-load' => true by $props
		// 2. When querying data from another domain, since evidently we don't have that data in this site, then the load must be triggered from the client
		return $this->queries_external_domain($module, $props) || $this->get_prop($module, $props, 'lazy-load');
	}

	protected function add_headdatasetmodule_data_properties(&$ret, $module, $props) {

		$vars = Engine_Vars::get_vars();

		// Is the component lazy-load?
		$ret[GD_DATALOAD_LAZYLOAD] = $this->is_lazyload($module, $props);

		// Do not load data when doing lazy load, unless passing URL param ?action=loadlazy, which is needed to initialize the lazy components.
		// Do not load data for Search page (initially, before the query was submitted)
		$ret[GD_DATALOAD_SKIPDATALOAD] = 
			($vars['action'] != POP_ACTION_LOADLAZY && $ret[GD_DATALOAD_LAZYLOAD]) || 
			$this->get_prop($module, $props, 'skip-data-load');

		// Use Mock DB Object Data for the Skeleton Screen
		$ret[GD_DATALOAD_USEMOCKDBOBJECTDATA] = $this->get_prop($module, $props, 'use-mock-dbobject-data') ?? false;

		/**---------------------------------------------------------------------------------------------------------------
		 * Allow to add more stuff
		 * ---------------------------------------------------------------------------------------------------------------*/
		do_action(
			'\PoP\Engine\ModuleProcessorBase:add_headdatasetmodule_data_properties',
			array(&$ret),
			$module,
			array(&$props),
			$this
		);
	}

	function get_mutableonmodel_data_properties_datasetmoduletree($module, &$props) {

		return $this->execute_on_self_and_propagate_to_modules('get_mutableonmodel_data_properties_datasetmoduletree_fullsection', __FUNCTION__, $module, $props, false);
	}

	function get_mutableonmodel_data_properties_datasetmoduletree_fullsection($module, &$props) {

		$ret = array();

		// Only if this module has a dataloader
		if ($this->get_dataloader($module)) {

			$properties = $this->get_mutableonmodel_headdatasetmodule_data_properties($module, $props);
			if ($properties) {

				$ret[POP_CONSTANT_DATAPROPERTIES] = $properties;
			}
		}
	
		return $ret;
	}

	function get_mutableonmodel_headdatasetmodule_data_properties($module, &$props) {

		$ret = array();

		// Add the properties below either as static or mutableonrequest
		$datasource = $this->get_datasource($module, $props);
		if ($datasource == POP_DATALOAD_DATASOURCE_MUTABLEONMODEL) {

			$this->add_headdatasetmodule_data_properties($ret, $module, $props);
		}

		return $ret;
	}

	function get_mutableonrequest_data_properties_datasetmoduletree($module, &$props) {

		return $this->execute_on_self_and_propagate_to_modules('get_mutableonrequest_data_properties_datasetmoduletree_fullsection', __FUNCTION__, $module, $props, false);
	}

	function get_mutableonrequest_data_properties_datasetmoduletree_fullsection($module, &$props) {

		$ret = array();

		// Only if this module has a dataloader
		if ($this->get_dataloader($module)) {

			// // Load the data-fields from all modules inside this section
			// // And then, only for the top node, add its extra properties
			// $properties = array_merge(
			// 	$this->get_mutableonrequest_data_properties_datasetmoduletree_section($module, $props), 
			// 	$this->get_mutableonrequest_headdatasetmodule_data_properties($module, $props)
			// );
			$properties = $this->get_mutableonrequest_headdatasetmodule_data_properties($module, $props);

			if ($properties) {

				$ret[POP_CONSTANT_DATAPROPERTIES] = $properties;
			}
		}
	
		return $ret;
	}

	function get_mutableonrequest_headdatasetmodule_data_properties($module, &$props) {

		$ret = array();

		// Add the properties below either as static or mutableonrequest
		$datasource = $this->get_datasource($module, $props);
		if ($datasource == POP_DATALOAD_DATASOURCE_MUTABLEONREQUEST) {

			$this->add_headdatasetmodule_data_properties($ret, $module, $props);
		}

		if ($dataload_source = $this->get_dataload_source($module, $props)) {
			$ret[GD_DATALOAD_SOURCE] = $dataload_source;
		}
	
		// When loading data or execution an action, check if to validate checkpoints?
		// This is in MUTABLEONREQUEST instead of STATIC because the checkpoints can change depending on doing_post() 
		// (such as done to set-up checkpoint configuration for POP_USERSTANCE_PAGE_ADDOREDITSTANCE, or within POPUSERLOGIN_CHECKPOINTCONFIGURATION_REQUIREUSERSTATEONDOINGPOST)
		// if ($checkpoint_configuration = $this->get_dataaccess_checkpoint_configuration($module, $props)) {
		if ($checkpoints = $this->get_dataaccess_checkpoints($module, $props)) {
			
			// if (Utils::checkpoint_validation_required($checkpoint_configuration)) {

			// Pass info for PoP Engine
			// $ret[GD_DATALOAD_DATAACCESSCHECKPOINTS] = $checkpoint_configuration['checkpoints'];
			$ret[GD_DATALOAD_DATAACCESSCHECKPOINTS] = $checkpoints;
			// }
		}
	
		// To trigger the actionexecuter, its own checkpoints must be successful
		// if ($checkpoint_configuration = $this->get_actionexecution_checkpoint_configuration($module, $props)) {
		if ($checkpoints = $this->get_actionexecution_checkpoints($module, $props)) {
			
			// if (Utils::checkpoint_validation_required($checkpoint_configuration)) {

			// Pass info for PoP Engine
			// $ret[GD_DATALOAD_ACTIONEXECUTIONCHECKPOINTS] = $checkpoint_configuration['checkpoints'];
			$ret[GD_DATALOAD_ACTIONEXECUTIONCHECKPOINTS] = $checkpoints;
			// }
		}

		return $ret;
	}

	//-------------------------------------------------
	// New PUBLIC Functions: Data Feedback
	//-------------------------------------------------

	function get_data_feedback_datasetmoduletree($module, &$props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids) {

		return $this->execute_on_self_and_propagate_to_datasetmodules('get_data_feedback_moduletree', __FUNCTION__, $module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);
	}

	function get_data_feedback_moduletree($module, &$props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids) {

		$ret = array();

		if ($feedback = $this->get_data_feedback($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)) {

			$ret[POP_CONSTANT_FEEDBACK] = $feedback;
		}
	
		return $ret;
	}

	function get_data_feedback($module, &$props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids) {
	
		return array();
	}

	function get_data_feedback_interreferenced_modulepath($module, &$props) {
	
		return null;
	}

	//-------------------------------------------------
	// Background URLs
	//-------------------------------------------------

	function get_backgroundurls_mergeddatasetmoduletree($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids) {

		return $this->execute_on_self_and_merge_with_datasetmodules('get_backgroundurls', __FUNCTION__, $module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);
	}

	function get_backgroundurls($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids) {
	
		return array();
	}

	//-------------------------------------------------
	// Dataset Meta
	//-------------------------------------------------

	function get_datasetmeta($module, &$props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids) {

		$ret = array();

		if ($query_multidomain_urls = $this->get_dataload_multidomain_sources($module, $props)) {

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

	function get_relevant_page($module, &$props) {

		return null;
	}

	function get_relevant_page_checkpoint_target($module, &$props) {

		return GD_DATALOAD_DATAACCESSCHECKPOINTS;
	}

	protected function maybe_override_checkpoints($checkpoints) {

		// Allow URE to add the extra checkpoint condition of the user having the Profile role
		return apply_filters(
			'ModuleProcessor:checkpoints',
			 $checkpoints
		);
	}

	// function get_dataaccess_checkpoint_configuration($module, &$props) {
	function get_dataaccess_checkpoints($module, &$props) {

		if ($page_id = $this->get_relevant_page($module, $props)) {
			if ($this->get_relevant_page_checkpoint_target($module, $props) == GD_DATALOAD_DATAACCESSCHECKPOINTS) {
			
				// // return Utils::get_checkpoint_configuration($page_id);
				// return Utils::get_checkpoints($page_id);
				return $this->maybe_override_checkpoints(Settings\SettingsManager_Factory::get_instance()->get_checkpoints($page_id));
			}
		}
		
		// return null;
		return array();
	}

	// function get_actionexecution_checkpoint_configuration($module, &$props) {
	function get_actionexecution_checkpoints($module, &$props) {

		if ($page_id = $this->get_relevant_page($module, $props)) {
			if ($this->get_relevant_page_checkpoint_target($module, $props) == GD_DATALOAD_ACTIONEXECUTIONCHECKPOINTS) {
			
				// // return Utils::get_checkpoint_configuration($page_id);
				// return Utils::get_checkpoints($page_id);
				return $this->maybe_override_checkpoints(Settings\SettingsManager_Factory::get_instance()->get_checkpoints($page_id));
			}
		}
		
		// return null;
		return array();
	}

	function execute_action($module, &$props) {

		// By default, execute only if the module is targeted for execution and doing POST
		$vars = Engine_Vars::get_vars();		
		return doing_post() && $vars['actionpath'] == ModulePathManager_Utils::get_stringified_module_propagation_current_path($module);
	}

	function get_dataload_source($module, $props) {

		// Because a component can interact with itself by adding ?modulepaths=...,
		// then, by default, we simply set the dataload source to point to itself!
		$stringified_module_propagation_current_path = ModulePathManager_Utils::get_stringified_module_propagation_current_path($module);
		$ret = add_query_arg(
			GD_URLPARAM_MODULEFILTER,
			POP_MODULEFILTER_MODULEPATHS,
			add_query_arg(
				GD_URLPARAM_MODULEPATHS.'[]',
				$stringified_module_propagation_current_path,
				Utils::get_current_url()
			)
		);

		// Allow to add extra modulepaths set from above
		if ($extra_module_paths = $this->get_prop($module, $props, 'dataload-source-add-modulepaths')) {

			foreach ($extra_module_paths as $modulepath) {

				$ret = add_query_arg(
					GD_URLPARAM_MODULEPATHS.'[]',
					ModulePathManager_Utils::stringify_module_path($modulepath),
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
		if ($this instanceof \PoP\Engine\FormattableModule) {

			if ($format = $this->get_format($module)) {

				$ret = add_query_arg(GD_URLPARAM_FORMAT, $format, $ret);
			}
		}

		return $ret;
	}

	function get_dataload_multidomain_sources($module, $props) {

		if ($sources = $this->get_prop($module, $props, 'dataload-multidomain-sources')) {

			if (!is_array($sources)) {
				$sources = array($sources);
			}

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

	function queries_external_domain($module, $props) {

		if ($sources = $this->get_dataload_multidomain_sources($module, $props)) {
			
			$domain = get_site_url();
			foreach ($sources as $source) {

				if (substr($source, 0, strlen($domain)) != $domain) {

					return true;
				}
			}
		}

		return false;
	}

	function is_multidomain($module, $props) {

		if (!$this->queries_external_domain($module, $props)) {

			return false;
		}

		$multidomain_urls = $this->get_dataload_multidomain_sources($module, $props);
		return is_array($multidomain_urls) && count($multidomain_urls) >= 2;
	}

	function get_modules_to_propagate_data_properties($module) {

		return $this->get_modulecomponents($module, array(POP_MODULECOMPONENT_MODULES));
	}

	protected function flatten_datasetmoduletree_data_properties($propagate_fn, &$ret, $module, $props) {
	
		$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();
		
		// Exclude the subcomponent modules here
		if ($submodules = $this->get_modules_to_propagate_data_properties($module)) {
			
			foreach ($submodules as $submodule) {

				$submodule_processor = $moduleprocessor_manager->get_processor($submodule);

				// Propagate only if the submodule doesn't have a dataloader. If it does, this is the end of the data line, and the submodule is the beginning of a new datasetmoduletree
				if (!$submodule_processor->get_dataloader($submodule, $props[$module][POP_PROPS_MODULES])) {
					
					if ($submodule_ret = $submodule_processor->$propagate_fn($submodule, $props[$module][POP_PROPS_MODULES])) {

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

	protected function flatten_relationaldbobject_data_properties($propagate_fn, &$ret, $module, $props) {
	
		$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();
		
		// If it has subcomponent modules, integrate them under 'subcomponents'
		foreach ($this->get_dbobject_relational_successors($module) as $subcomponent_data_field => $subcomponent_dataloader_options) {
			foreach ($subcomponent_dataloader_options as $subcomponent_dataloader_name => $subcomponent_modules) {

				$subcomponent_modules_data_properties = array(
					'data-fields' => array(),
					'subcomponents' => array()
				);
				foreach ($subcomponent_modules as $subcomponent_module) {

					if ($subcomponent_module_data_properties = $moduleprocessor_manager->get_processor($subcomponent_module)->$propagate_fn($subcomponent_module, $props[$subcomponent_module][POP_PROPS_MODULES])) {

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

	function get_model_supplementary_dbobjectdata_moduletree($module, &$props) {
	
		return $this->execute_on_self_and_merge_with_modules('get_model_supplementary_dbobjectdata', __FUNCTION__, $module, $props);
	}

	function get_model_supplementary_dbobjectdata($module, &$props) {
	
		return array();
	}

	//-------------------------------------------------
	// New PUBLIC Functions: Stateful Data
	//-------------------------------------------------

	function get_mutableonrequest_supplementary_dbobjectdata_moduletree($module, &$props) {
	
		return $this->execute_on_self_and_merge_with_modules('get_mutableonrequest_supplementary_dbobjectdata', __FUNCTION__, $module, $props);
	}

	function get_mutableonrequest_supplementary_dbobjectdata($module, &$props) {
	
		return array();
	}

	private function get_modulecomponents($module, $components = array()) {

		$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();

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