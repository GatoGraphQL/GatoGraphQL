<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ProcessorBase {

	function __construct() {

		add_action('init', array($this, 'init'));
	}

	function init() {

		global $gd_template_processor_manager;
		$gd_template_processor_manager->add($this, $this->get_templates_to_process());
	}

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------

	function get_templates_to_process() {
	
		return array();
	}

	function get_modules($template_id) {

		return array();
	}

	// function get_template_modules($template_id) {

	// 	// Allow 3rd parties to modify the modules. Eg: for the TPP website we re-use the MESYM Theme but we modify some of its elements, eg: adding the "What do you think about TPP?" modules in the fullview templates
	// 	return apply_filters('template:modules', $this->get_modules($template_id), $template_id);
	// }

	function get_extra_blocks($template_id) {

		return array();
	}
	function get_extra_modules($template_id) {
			
		return array();
	}

	// function get_decorated_template($template_id) {

	// 	return null;
	// }

	function get_modulecomponents($template_id, $components = array()) {

		global $gd_template_processor_manager;

		if (empty($components)) {
			$components = array('modules', 'subcomponent-modules'/*, 'decorated-template'*/);
		}

		$ret = array();
		
		if (in_array('modules', $components)) {
			$ret = array_unique(
				array_merge(
					$this->get_modules($template_id),
					$ret
				)
			);
		}
		
		// When fetching JSON for the Block, we don't need the extra-blocks
		// This is used only by hierarchy to load extra blocks from each block (module)
		if (in_array('extra-blocks', $components) && 
			!$this->load_only_main_block($template_id)) {

			// The extra blocks are specified as an array of EXTRABLOCK_GROUP => BLOCK,
			// So we only care about the array_values here, those are the actual blocks
			$extra_blocks = $this->get_extra_modules($template_id);
			
			// Allow extra blocks to also load extra blocks (eg: Comments List loads Login Modal as extra block which loads Lost Pwd as extra block)
			if (!empty($extra_blocks)) {
				foreach ($extra_blocks as $extra_block) {
					$extra_blocks = array_merge(
						$extra_blocks,
						$gd_template_processor_manager->get_processor($extra_block)->get_modulecomponents($extra_block, array('extra-blocks'))
					);
				}

				// Make sure no blocks are repeated
				$extra_blocks = array_unique($extra_blocks);
			}

			$ret = array_unique(
				array_merge(
					$extra_blocks,
					$ret
				)
			);
		}

		if (in_array('subcomponent-modules', $components)) {
			foreach ($this->get_subcomponent_modules($template_id) as $subcomponent_id_field => $subcomponent_options) {

				$subcomponent_modules = $subcomponent_options['modules'];
				$ret = array_unique(
					array_merge(
						$subcomponent_modules,
						$ret
					)
				);
			}
		}

		// if (in_array('decorated-template', $components)) {
		// 	if ($decorated_template = $this->get_decorated_template($template_id)) {

		// 		$ret[] = $decorated_template;
		// 	}
		// }

		return $ret;
	}

	function get_subcomponent_modules($template_id) {
	
		return array();
	}

	function get_data_fields($template_id, $atts) {
	
		return array();
	}

	function get_runtime_datafields($template_id, $atts) {
	
		return array();
	}

	function get_dataload_extend($template_id, $atts) {
	
		return null;
	}

	function get_database_key($template_id, $atts) {

		$ret = array();

		global $gd_dataload_manager;
		if ($subcomponents = $this->get_subcomponent_modules($template_id)) {
			
			$ret[GD_JS_SUBCOMPONENTS/*'subcomponents'*/] = array();
			foreach ($subcomponents as $subcomponent_id_field => $subcomponent_options) {

				$subcomponent_dataloader_name = $subcomponent_options['dataloader'];
				$subcomponent_dataloader = $gd_dataload_manager->get($subcomponent_dataloader_name);
				$ret[GD_JS_SUBCOMPONENTS/*'subcomponents'*/][$subcomponent_id_field]['db-key'] = $subcomponent_dataloader->get_database_key();
			}
		}

		return $ret;
	}

	function get_database_keys($template_id, $atts) {

		global $gd_template_processor_manager;

		// // If decorating another component, bring this one's result
		// if ($decorated_template = $this->get_decorated_template($template_id)) {
			
		// 	return $gd_template_processor_manager->get_processor($decorated_template)->get_database_keys($decorated_template, $atts);
		// }
		
		$ret = $this->get_database_key($template_id, $atts);
		
		foreach ($this->get_modulecomponents($template_id) as $component) {
		
			if ($component_ret = $gd_template_processor_manager->get_processor($component)->get_database_keys($component, $atts)) {
			
				$ret = array_merge_recursive(
					$ret,
					$component_ret
				);
			}
		}

		// Eliminate duplicates: just replace the array with their first item (it must always be the same,
		// otherwise there will be an exception coming from get_data_settings function)
		if ($ret[GD_JS_SUBCOMPONENTS/*'subcomponents'*/]) {
			foreach ($ret[GD_JS_SUBCOMPONENTS/*'subcomponents'*/] as $subcomponent_id_field => $subcomponent_ret) {

				if (is_array($subcomponent_ret['db-key'])) {
					$ret[GD_JS_SUBCOMPONENTS/*'subcomponents'*/][$subcomponent_id_field]['db-key'] = $subcomponent_ret['db-key'][0];
				}
			}
		}
		
		return $ret;
	}

	function get_data_setting($template_id, $atts) {

		// By default return nothing at the last level
		$ret = array(
			'data-fields' => array()
		);

		// If this level has data-fields, add them
		if ($data_fields = $this->get_compiled_data_fields(/*'static', */$template_id, $atts)) {

			$ret['data-fields'] = $data_fields;
		}

		// If this level has dataload-extend, add it
		if ($dataload_extend = $this->get_dataload_extend($template_id, $atts)) {

			$ret['dataload-extend'] = $dataload_extend;
		}

		return $ret;
	}

	function get_runtime_datasetting($template_id, $atts) {

		// By default return nothing at the last level
		$ret = array(
			// 'data-fields' => array()
		);

		// If this level has data-fields, add them
		// if ($data_fields = $this->get_compiled_data_fields('runtime', $template_id, $atts)) {
		if ($data_fields = $this->get_runtime_datafields($template_id, $atts)) {

			$ret['data-fields'] = $data_fields;
		}

		// Comment Leo 12/01/2017: I still don't see an use fo the $dataload_extend for runtime_datasettings,
		// so do nothing for the time being until need arises
		// // If this level has dataload-extend, add it
		// if ($dataload_extend = $this->get_dataload_extend($template_id, $atts)) {

		// 	$ret['dataload-extend'] = $dataload_extend;
		// }

		return $ret;
	}

	function get_data_settings($template_id, $atts) {
	
		// It is built in stages so that they can be customized by different templates
		// Eg: Typeahead doesn't need to propagate components
		$ret = $this->get_data_setting($template_id, $atts);

		// Propagate down to the components
		$ret = $this->propagate_data_settings_components('static', $ret, $template_id, $atts);

		// Propagate down to the subcomponent modules
		$ret = $this->propagate_data_settings_subcomponent_modules('static', $ret, $template_id, $atts);
		
		return $ret;
	}

	function get_runtime_datasettings($template_id, $atts) {
	
		// It is built in stages so that they can be customized by different templates
		// Eg: Typeahead doesn't need to propagate components
		$ret = $this->get_runtime_datasetting($template_id, $atts);

		// Propagate down to the components
		$ret = $this->propagate_data_settings_components('runtime', $ret, $template_id, $atts);

		// Propagate down to the subcomponent modules
		$ret = $this->propagate_data_settings_subcomponent_modules('runtime', $ret, $template_id, $atts);
		
		return $ret;
	}
		
	function get_component_configuration_type($template_id, $atts) {
			
		return GD_TEMPLATECOMPONENT_CONFIGURATION_TYPE_MAP;
	}
	
	function get_template_configurations($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$settings_id = $this->get_settings_id($template_id);
		$ret = array(
			$settings_id => $this->get_template_configuration($template_id, $atts)
		);
		
		$components_ret = array();
		foreach ($this->get_modulecomponents($template_id, array('modules', 'subcomponent-modules')) as $component) {
		
			$component_processor = $gd_template_processor_manager->get_processor($component);
			$component_ret = $component_processor->get_template_configurations($component, $atts);

			// Place all component configuration under this configuration. The component configurations
			// can be added under their own key, or as an array
			$component_configuration_type = $this->get_component_configuration_type($template_id, $atts);
			if ($component_configuration_type == GD_TEMPLATECOMPONENT_CONFIGURATION_TYPE_MAP) {

				$components_ret = array_merge(
					$components_ret,
					$component_ret
				);
			}
			elseif ($component_configuration_type == GD_TEMPLATECOMPONENT_CONFIGURATION_TYPE_ARRAY) {

				// Extract the content (remove the component_settings_id)
				$component_settings_id = $component_processor->get_settings_id($component);
				$components_ret[] = $component_ret[$component_settings_id];
			}
		}

		if ($components_ret) {

			$ret[$settings_id] = array_merge(
				$ret[$settings_id],
				array(
					GD_JS_MODULES/*'modules'*/ => $components_ret
				)
			);
		}
		
		return $ret;
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = array();

		// Template configuration for each level: 
		// For decorated templates, it will already bring the template, calling once again so that it can be overridden
		// with a custom template. Eg: BlockGroups
		$ret[GD_JS_TEMPLATE/*'template'*/] = $template_id;
		
		return $ret;
	}	

	function get_template_runtimeconfigurations($template_id, $atts) {
		global $gd_template_processor_manager;

		$ret = array();

		if ($runtimeconfiguration = $this->get_template_runtimeconfiguration($template_id, $atts)) {
			$ret[$template_id] = $runtimeconfiguration;
		}

		foreach ($this->get_modulecomponents($template_id) as $component) {
		
			if ($component_ret = $gd_template_processor_manager->get_processor($component)->get_template_runtimeconfigurations($component, $atts)) {
			
				// Do NOT use array_merge_recursive, because then for MultipleLayout, since they reference to the same submodules (eg: author layout),
				// and since the key is the ID, that js-settings will be generated many times and appended time and again
				// Right now, it is generating the same js-settings and overriding it... nothing to do about it (eg: 6 times generated for a MultipleLayout with 6 Layouts)
				$ret = array_merge(
					$ret,
					$component_ret
				);
			}
		}
		
		return $ret;
	}

	function get_template_runtimeconfiguration($template_id, $atts) {
	
		return array();
	}	
	
	function get_template_crawlableitems($template_id, $atts) {
		
		global $gd_template_processor_manager;

		$ret = $this->get_template_crawlableitem($template_id, $atts);

		foreach ($this->get_modulecomponents($template_id) as $component) {
		
			if ($component_ret = $gd_template_processor_manager->get_processor($component)->get_template_crawlableitems($component, $atts)) {
			
				$ret = array_merge(
					$ret,
					$component_ret
				);
			}
		}
		
		return $ret;
	}

	function get_template_crawlableitem($template_id, $atts) {
	
		return array();
	}	

	function get_template_runtimecrawlableitems($template_id, $atts) {
		
		global $gd_template_processor_manager;

		$ret = $this->get_template_runtimecrawlableitem($template_id, $atts);
		
		foreach ($this->get_modulecomponents($template_id) as $component) {
		
			if ($component_ret = $gd_template_processor_manager->get_processor($component)->get_template_runtimecrawlableitems($component, $atts)) {
			
				$ret = array_merge(
					$ret,
					$component_ret
				);
			}
		}
		
		return $ret;
	}

	function get_template_runtimecrawlableitem($template_id, $atts) {
	
		return array();
	}	
	

	function init_atts($template_id, &$atts) {
	
		global $gd_template_processor_manager;

		// if ($decorated_template = $this->get_decorated_template($template_id)) {
			
		// 	return $gd_template_processor_manager->get_processor($decorated_template)->init_atts($decorated_template, $atts);
		// }
		
		foreach ($this->get_modulecomponents($template_id) as $component) {

			$processor = $gd_template_processor_manager->get_processor($component);

			// If processor not found, there there's an error in the code, throw exception
			if (!$processor) {
				throw new Exception(sprintf('No processor for template \'%s\' loaded by template_id \'%s\' (%s)', $component, $template_id, full_url()));
			}
			$atts = $gd_template_processor_manager->get_processor($component)->init_atts($component, $atts);
		}
		
		return $atts;
	}

	function get_settings_id($template_id) {

		return $template_id;
	}

	//-------------------------------------------------
	// PROTECTED Functions
	//-------------------------------------------------

	protected function add_blocks(&$ret, $blocks, $block_key) {

		GD_TemplateManager_Utils::add_blocks($ret, $blocks, $block_key);
	}
	protected function add_blockgroups(&$ret, $blockgroups, $block_key) {

		GD_TemplateManager_Utils::add_blockgroups($ret, $blockgroups, $block_key);
	}

	protected function load_only_main_block($template_id) {

		$vars = GD_TemplateManager_Utils::get_vars();
		
		// When fetching JSON for the Block, we only need the main block and nothing else
		return $vars['fetching-json-data'];
	}

	protected function get_compiled_data_fields($template_id, $atts) {
	
		$data_fields = $this->get_data_fields($template_id, $atts);
		
		// Make sure to always return the 'id'			
		$base = array('id'); 

		// Return the data-fields as coming from all different sources (eg: Subcomponent modules keys are also data-fields)
		$subcomponent_modules_data_fields = array_keys($this->get_subcomponent_modules($template_id));

		return array_merge(
			$base,
			$data_fields,
			$subcomponent_modules_data_fields
		);
	}

	// protected function get_compiled_data_fields($mode, $template_id, $atts) {
	
	// 	$data_fields = $base = $subcomponent_modules_data_fields = array();
	// 	if ($mode == 'static') {

	// 		$data_fields = $this->get_data_fields($template_id, $atts);
			
	// 		// Make sure to always return the 'id'			
	// 		$base[] = 'id'; 

	// 		// Return the data-fields as coming from all different sources (eg: Subcomponent modules keys are also data-fields)
	// 		$subcomponent_modules_data_fields = array_keys($this->get_subcomponent_modules($template_id));
	// 	}
	// 	elseif ($mode == 'runtime') {

	// 		$data_fields = $this->get_runtime_datafields($template_id, $atts);
	// 	}

	// 	return array_merge(
	// 		$base,
	// 		$data_fields,
	// 		$subcomponent_modules_data_fields
	// 	);
	// }

	protected function propagate_data_settings_components($mode, &$ret, $template_id, $atts) {
	
		global $gd_template_processor_manager;
		
		// Exclude the subcomponent modules here
		foreach ($this->get_modulecomponents($template_id, array('modules'/*, 'decorated-template'*/)) as $component) {

			if ($mode == 'static') {
				$component_ret = $gd_template_processor_manager->get_processor($component)->get_data_settings($component, $atts);
			}
			elseif ($mode == 'runtime') {
				$component_ret = $gd_template_processor_manager->get_processor($component)->get_runtime_datasettings($component, $atts);
			}
			if ($component_ret) {

				if ($mode == 'static') {

					// Dataloader must be unique, so set only once (if set more than once, they must be the same!)
					if ($ret['dataloader'] && $component_ret['dataloader']) {
						
						// They must be the same, if not, raise exception
						if ($ret['dataloader'] != $component_ret['dataloader']) {
							throw new Exception('Different dataloaders!');
						}

						// Delete so it can be copied again when doing merge (otherwise, it creates an array)
						unset($ret['dataloader']);
					}
					// Do the same for its subcomponents
					if ($ret['subcomponents'] && $component_ret['subcomponents']) {

						foreach ($ret['subcomponents'] as $subcomponent => $subcomponent_settings) {

							if ($component_ret['subcomponents'][$subcomponent]) {

								if ($ret['subcomponents'][$subcomponent]['dataloader'] && $component_ret['subcomponents'][$subcomponent]['dataloader']) {

									// They must be the same, if not, raise exception
									if ($ret['subcomponents'][$subcomponent]['dataloader'] != $component_ret['subcomponents'][$subcomponent]['dataloader']) {
										throw new Exception('Different dataloaders! Subcomponent: ' . $subcomponent . ' Dataloaders: ' . $ret['subcomponents'][$subcomponent]['dataloader'] . ' and ' . $component_ret['subcomponents'][$subcomponent]['dataloader']);
									}

									// Delete so it can be copied again when doing merge (otherwise, it creates an array)
									unset($ret['subcomponents'][$subcomponent]['dataloader']);
								}
							}
						}
					}
				}
			
				// array_merge_recursive => data-fields from different sidebar-components can be integrated all together 
				$ret = array_merge_recursive(
					$ret,
					$component_ret
				);
				if ($ret['subcomponents']) {
					foreach ($ret['subcomponents'] as $subcomponent => $subcomponent_settings) {

						$ret['subcomponents'][$subcomponent]['data-fields'] = array_unique($ret['subcomponents'][$subcomponent]['data-fields']);

						// // If the array_merge_recursive created an array of dataloaders, then undo it now
						// if (is_array($ret['subcomponents'][$subcomponent]['dataloader'])) {
						// 	$ret['subcomponents'][$subcomponent]['dataloader'] = $ret['subcomponents'][$subcomponent]['dataloader'][0];
						// }
					}
				}
			}
		}
		// Array Merge appends values when under numeric keys, so we gotta filter duplicates out
		if ($ret['data-fields']) {
			$ret['data-fields'] = array_unique($ret['data-fields']);
		}

		return $ret;
	}

	protected function propagate_data_settings_subcomponent_modules($mode, &$ret, $template_id, $atts) {
	
		global $gd_template_processor_manager;
		
		// If it has subcomponent modules, integrate them under 'subcomponents'
		foreach ($this->get_subcomponent_modules($template_id) as $subcomponent_id_field => $subcomponent_options) {

			// $subcomponent_id_field: 'id-field' that contains the ID of the related field to fetch (eg: author, locations)
			// $subcomponent_options: what modules to bring / what it's dataloader
			$subcomponent_modules = $subcomponent_options['modules'];
			$subcomponent_dataloader_name = $subcomponent_options['dataloader'];

			$subcomponent_modules_data_settings = array(
				'data-fields' => array(),
				'subcomponents' => array()
			);
			foreach ($subcomponent_modules as $subcomponent_module) {

				if ($mode == 'static') {
					$subcomponent_module_data_settings = $gd_template_processor_manager->get_processor($subcomponent_module)->get_data_settings($subcomponent_module, $atts);
				}
				elseif ($mode == 'runtime') {
					$subcomponent_module_data_settings = $gd_template_processor_manager->get_processor($subcomponent_module)->get_runtime_datasettings($subcomponent_module, $atts);
				}				
				// $subcomponent_module_data_settings = $gd_template_processor_manager->get_processor($subcomponent_module)->get_data_settings($subcomponent_module, $atts);
				if ($subcomponent_module_data_settings) {
					$subcomponent_modules_data_settings = array_merge_recursive(
						$subcomponent_modules_data_settings,
						$subcomponent_module_data_settings
					);
				}
			}
			$subcomponent_modules_data_settings['data-fields'] = array_unique($subcomponent_modules_data_settings['data-fields']);
			
			// Incorporate the subcomponent bit
			// Possibly it was already filled in by another template, so combine them (except for dataloader, it can be unique, actually it must be the same)
			if (!$ret['subcomponents'][$subcomponent_id_field]) {

				$ret['subcomponents'][$subcomponent_id_field] = array(
					'data-fields' => array(),
					'subcomponents' => array(),
				);
				if ($mode == 'static') {
					$ret['subcomponents'][$subcomponent_id_field]['dataloader'] = $subcomponent_dataloader_name;
				}
			}
			
			$ret['subcomponents'][$subcomponent_id_field]['data-fields'] = array_unique(
				array_merge(
					$ret['subcomponents'][$subcomponent_id_field]['data-fields'],
					$subcomponent_modules_data_settings['data-fields']
				)
			);
			$ret['subcomponents'][$subcomponent_id_field]['subcomponents'] = array_merge_recursive(
				$ret['subcomponents'][$subcomponent_id_field]['subcomponents'],
				$subcomponent_modules_data_settings['subcomponents']
			);
			foreach ($ret['subcomponents'][$subcomponent_id_field]['subcomponents'] as $subcomponent_subcomponent_id_field => $subcomponent_subcomponent_data_settings) {

				$ret['subcomponents'][$subcomponent_id_field]['subcomponents'][$subcomponent_subcomponent_id_field]['data-fields'] = array_unique($subcomponent_subcomponent_data_settings['data-fields']);
			}
		}

		// When having MultipleLayouts, each layout might load its own dataloader, here make sure it's unique
		if ($ret['subcomponents']) {
			foreach ($ret['subcomponents'] as $subcomponent => $subcomponent_settings) {
				if ($subcomponent_settings['subcomponents']) {
					foreach ($subcomponent_settings['subcomponents'] as $subcomponent_subcomponent => $subcomponent_subcomponent_settings) {

						if ($mode == 'static') {
							if (is_array($subcomponent_subcomponent_settings['dataloader'])) {
								$subcomponent_subcomponent_settings_dataloader_names = array_unique($subcomponent_subcomponent_settings['dataloader']);
								if (count($subcomponent_subcomponent_settings_dataloader_names) > 1) {
									throw new Exception('Different dataloaders!');
								}
								$ret['subcomponents'][$subcomponent]['subcomponents'][$subcomponent_subcomponent]['dataloader'] = $subcomponent_subcomponent_settings_dataloader_names[0];
							}
						}
						$subcomponent_subcomponent_settings['data-fields'] = array_unique($subcomponent_subcomponent_settings['data-fields']);
					}
				}
			}
		}
		
		return $ret;
	}

	protected function add_att_group_field($template_id, &$atts, $group, $field, $value, $options = array()) {

		if (!$atts[$group][$template_id]) {
			$atts[$group][$template_id] = array();
		}

		// Using isset because the value can be set to 'false'
		$set = isset($atts[$group][$template_id][$field]);
		
		if ($options['append']) {

			if (!$set) {
				$atts[$group][$template_id][$field] = '';
			}
			$atts[$group][$template_id][$field] .= ' ' . $value;
		}
		elseif ($options['array']) {
			if (!$set) {
				$atts[$group][$template_id][$field] = array();
			}
			if ($options['merge']) {
				$atts[$group][$template_id][$field] = array_merge(
					$atts[$group][$template_id][$field],
					$value
				);
			}
			elseif ($options['merge-iterate-key']/* || $options['merge-iterate-key-recursive']*/) {
				foreach ($value as $value_key => $value_value) {
					if (!$atts[$group][$template_id][$field][$value_key]) {
						$atts[$group][$template_id][$field][$value_key] = array();
					}
					// Doing array_unique, because in the NotificationPreviewLayout, different layouts might impose a JS down the road, many times, and these get duplicated
					$atts[$group][$template_id][$field][$value_key] = array_unique(
						/*$options['merge-iterate-key'] ?
							*/array_merge(
								$atts[$group][$template_id][$field][$value_key],
								$value_value
							)/* :
							array_merge_recursive(
								$atts[$group][$template_id][$field][$value_key],
								$value_value
							)*/
					);
				}
			}
			elseif ($options['push']) {
				array_push($atts[$group][$template_id][$field], $value);
			}
		}
		else {
			// If already set, then do nothing
			if (!$set) {
				$atts[$group][$template_id][$field] = $value;
			}
		}
	}
	protected function get_att_group_field($template_id, $atts, $group, $field) {

		$group = $this->get_att_group($template_id, $atts, $group);
		return $group[$field];
	}
	protected function get_att_group($template_id, $atts, $group) {

		return $atts[$group][$template_id];
	}
	function add_att($template_id, &$atts, $field, $value) {

		$this->add_att_group_field($template_id, $atts, 'template-conf', $field, $value);
	}
	function force_att($template_id, &$atts, $field, $value) {

		// First delete the value, so that it will be overriden
		unset($atts['template-conf'][$template_id][$field]);

		// Then add it as usual
		$this->add_att($template_id, $atts, $field, $value);
	}
	function append_att($template_id, &$atts, $field, $value) {

		$this->add_att_group_field($template_id, $atts, 'template-conf', $field, $value, array('append' => true));
	}
	function merge_att($template_id, &$atts, $field, $value) {

		$this->add_att_group_field($template_id, $atts, 'template-conf', $field, $value, array('array' => true, 'merge' => true));
	}
	function merge_iterate_key_att($template_id, &$atts, $field, $value) {

		$this->add_att_group_field($template_id, $atts, 'template-conf', $field, $value, array('array' => true, 'merge-iterate-key' => true));
	}
	// function merge_iterate_key_recursive_att($template_id, &$atts, $field, $value) {

	// 	$this->add_att_group_field($template_id, $atts, 'template-conf', $field, $value, array('array' => true, 'merge-iterate-key-recursive' => true));
	// }
	function push_att($template_id, &$atts, $field, $value) {

		$this->add_att_group_field($template_id, $atts, 'template-conf', $field, $value, array('array' => true, 'push' => true));
	}
	function get_att($template_id, $atts, $field) {

		return $this->get_att_group_field($template_id, $atts, 'template-conf', $field);
	}
	function add_general_att(&$atts, $field, $value) {

		$block = $atts['block-template-id'];
		$this->add_att_group_field($block, $atts, 'template-generalconf', $field, $value);
	}
	function get_general_att($atts, $field) {

		$block = $atts['block-template-id'];
		return $this->get_att_group_field($block, $atts, 'template-generalconf', $field);
	}
}