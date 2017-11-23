<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_PageSectionsBase extends GD_Template_ProcessorBase {

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_query_url($template_id, $atts) {
			
		global $gd_template_processor_manager;
		
		// All code below commented because get_query_url does not need to go all the way down to template-processor.php,
		// Only blocks will implement this function
		
		$ret = array();				

		// In the Hierarchy Processor, all subcomponent templates are blocks
		foreach ($this->get_modulecomponents($template_id, array('modules', 'extra-blocks')) as $component) {

			$component_processor = $gd_template_processor_manager->get_processor($component);
			$component_atts = $atts[$component];
			$component_settings_id = $component_processor->get_settings_id($component);
			
			$component_ret = $component_processor->get_query_url($component, $component_atts);
			
			// Place all block configurations below $block_id
			$ret[$component_settings_id] = $component_ret;
		}

		return $ret;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_query_multidomain_urls($template_id, $atts) {
			
		global $gd_template_processor_manager;
		
		// All code below commented because get_query_url does not need to go all the way down to template-processor.php,
		// Only blocks will implement this function
		
		$ret = array();				

		// In the Hierarchy Processor, all subcomponent templates are blocks
		foreach ($this->get_modulecomponents($template_id, array('modules', 'extra-blocks')) as $component) {

			$component_processor = $gd_template_processor_manager->get_processor($component);
			$component_atts = $atts[$component];
			$component_settings_id = $component_processor->get_settings_id($component);
			
			$component_ret = $component_processor->get_query_multidomain_urls($component, $component_atts);
			
			// Place all block configurations below $block_id
			$ret[$component_settings_id] = $component_ret;
		}

		return $ret;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_query_domains($template_id, $atts) {
			
		global $gd_template_processor_manager;
		
		// All code below commented because get_query_url does not need to go all the way down to template-processor.php,
		// Only blocks will implement this function
		// Return a list of domains
		$ret = array();				

		// In the Hierarchy Processor, all subcomponent templates are blocks
		foreach ($this->get_modulecomponents($template_id, array('modules', 'extra-blocks')) as $component) {

			$component_processor = $gd_template_processor_manager->get_processor($component);
			$component_atts = $atts[$component];
			
			// At the block level, dataloadsource_domain might be null, then do not add it
			// if ($component_ret = $component_processor->get_dataloadsource_domain($component, $component_atts)) {
			
			// 	$ret[] = $component_ret;
			// }
			if ($component_ret = $component_processor->get_dataload_multidomain_sources($component, $component_atts)) {
			
				$ret[] = array_unique(array_merge(
					$ret,
					$component_ret
				));
			}
		}

		return array_unique($ret);
	}

	//-------------------------------------------------
	// PUBLIC Overriding Functions
	//-------------------------------------------------

	function is_frontend_id_unique($template_id, $atts) {

		return true;
	}

	function fixed_id($template_id, $atts) {

		return true;
	}	

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function init_atts($template_id, &$atts) {
	
		global $gd_template_processor_manager;

		$atts = $this->get_atts_hierarchy_initial($template_id);
		
		// Initialize all block atts
		$blocks = $this->get_modulecomponents($template_id, array('modules', 'extra-blocks'));
		$blockgroups = $this->get_all_blockgroups($template_id);
		$blockunits = array_merge(
			$blocks,
			$blockgroups
		);
		$pagesection_settings_id = $this->get_settings_id($template_id);
		$pagesection_frontend_id = $this->get_frontend_id($template_id, $atts);
		foreach ($blockunits as $component) {
			
			$atts[$component] = $this->get_atts_block_initial($template_id, $component);

			// Hierarchy id
			$atts[$component]['pagesection-settings-id'] = $pagesection_settings_id;
			$atts[$component]['pagesection-id'] = $pagesection_frontend_id;

			// Only after getting the first block_atts, we can get the block-settings-id
			$component_processor = $gd_template_processor_manager->get_processor($component);
			$component_settings_id = $component_processor->get_settings_id($component);
			$atts[$component]['block-settings-id'] = $component_settings_id;
			$atts[$component]['block-id'] = $component_processor->get_frontend_id($component, $atts[$component]);

			// Is the block replicable?
			$atts[$component]['replicable'] = $this->is_replicable($template_id, $component);
		}

		// Allow the BlockGroups to first set the atts for their contained Blocks
		$this->set_blockgroup_atts($atts, $template_id, $blockgroups);
		foreach ($blocks as $component) {
			
			// Add the recursive get_atts for each block, down to infinity
			$this->add_module_atts($template_id, $atts, $component);
		}

		return $atts;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_pagesection_extensions($template_id) {

		return array();
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_template_configurations($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$components_ret = array();				

		// In the Hierarchy Processor, all subcomponent templates are blocks
		foreach ($this->get_modulecomponents($template_id, array('modules', 'extra-blocks')) as $component) {
		
			$component_processor = $gd_template_processor_manager->get_processor($component);
			$component_atts = $atts[$component];
			$component_settings_id = $component_processor->get_settings_id($component);

			$component_ret = $component_processor->get_template_configurations($component, $component_atts);
			
			$components_ret = array_merge(
				$components_ret,
				$component_ret
			);
		}

		// Add this level configuration, and place the components under 'modules'
		$settings_id = $this->get_settings_id($template_id);
		$ret = array(
			$settings_id => $this->get_template_configuration($template_id, $atts)
		);
		
		// Comment Leo: add the modules key only if there are modules. If not, jQuery will assume the empty object as an array, not an object, and this will create mess
		if ($components_ret) {
			$ret[$settings_id][GD_JS_MODULES/*'modules'*/] = $components_ret;
		}
		
		return $ret;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_template_extra_sources($template_id, $atts) {

		// Add the extension templates
		$ret = parent::get_template_extra_sources($template_id, $atts);
		$ret['extensions'] = $this->get_pagesection_extensions($template_id);
		return $ret;
	}

	function get_template_configuration($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = parent::get_template_configuration($template_id, $atts);	
		
		/***********************************************************/
		/** Repeated from "parent" class! */
		/***********************************************************/
		
		$ret[GD_JS_SETTINGSID/*'settings-id'*/] = $this->get_settings_id($template_id);

		// // Add the extension templates
		// $ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['extensions'] = $this->get_pagesection_extensions($template_id);		

		/***********************************************************/


		// Load the configuration for all the blocks
		$blockunits = $this->get_blockunits($template_id);
		$blockunit_groups = array(
			GD_TEMPLATEBLOCKSETTINGS_MAIN,
			GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP
		);
		$blockunitframe_groups = array(
			GD_TEMPLATEBLOCKSETTINGS_FRAME,
			// GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUPFRAME
		);
		$blockunitreplicable_groups = array(
			GD_TEMPLATEBLOCKSETTINGS_REPLICABLE,
			GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUPREPLICABLE,
			// GD_TEMPLATEBLOCKSETTINGS_FRAMEREPLICABLE,
		);
		// $blockunitframereplicable_groups = array(
		// 	GD_TEMPLATEBLOCKSETTINGS_FRAMEREPLICABLE,
		// );
		$block_ids = array();
		$blockunit_settingids = $blockunitreplicable_settingids = $blockunitframe_settingids = $blockunitframereplicable_settingids = array();
		foreach ($blockunits as $blocks_group => $components) {

			$block_ids[$blocks_group] = array();

			foreach ($components as $component) {

				$component_processor = $gd_template_processor_manager->get_processor($component);
				$component_settings_id = $component_processor->get_settings_id($component);
				$block_ids[$blocks_group][] = $component_settings_id;
			}

			// both MAIN and BLOCKGROUP are blockunits, so also add them as such so we can reference these 2 together under key settings-ids.blockunits
			if (in_array($blocks_group, $blockunit_groups)) {
				$blockunit_settingids = array_merge(
					$blockunit_settingids,
					$block_ids[$blocks_group]
				);
			}
			elseif (in_array($blocks_group, $blockunitreplicable_groups)) {
				$blockunitreplicable_settingids = array_merge(
					$blockunitreplicable_settingids,
					$block_ids[$blocks_group]
				);
			}
			elseif (in_array($blocks_group, $blockunitframe_groups)) {
				$blockunitframe_settingids = array_merge(
					$blockunitframe_settingids,
					$block_ids[$blocks_group]
				);
			}
			// elseif (in_array($blocks_group, $blockunitframereplicable_groups)) {
			// 	$blockunitframereplicable_settingids = array_merge(
			// 		$blockunitframereplicable_settingids,
			// 		$block_ids[$blocks_group]
			// 	);
			// }
		}

		$block_ids[GD_JS_BLOCKUNITS/*'blockunits'*/] = $blockunit_settingids;
		$block_ids[GD_JS_BLOCKUNITSFRAME/*'blockunits-frame'*/] = $blockunitframe_settingids;
		$block_ids[GD_JS_BLOCKUNITSREPLICABLE/*'blockunits-replicable'*/] = $blockunitreplicable_settingids;
		// $block_ids['blockunits-framereplicable'] = $blockunitframereplicable_settingids;
		$ret[GD_JS_BLOCKSETTINGSIDS/*'block-settings-ids'*/] = $block_ids;

		return $ret;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_template_runtimeconfigurations($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = array();		
		if ($runtimeconfiguration = $this->get_template_runtimeconfiguration($template_id, $atts)) {

			$settings_id = $this->get_settings_id($template_id);
			$ret[$settings_id] = $runtimeconfiguration;
		}

		// In the Hierarchy Processor, all subcomponent templates are blocks
		foreach ($this->get_modulecomponents($template_id, array('modules', 'extra-blocks')) as $component) {
		
			$component_processor = $gd_template_processor_manager->get_processor($component);
			$component_atts = $atts[$component];
			$component_settings_id = $component_processor->get_settings_id($component);

			$ret[$component_settings_id] = $component_processor->get_template_runtimeconfigurations($component, $component_atts);
		}

		return $ret;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_template_crawlableitems($template_id, $atts) {
		
		global $gd_template_processor_manager;

		$ret = $this->get_template_crawlableitem($template_id, $atts);

		// Override the parent to add the 'extra-blocks'
		foreach ($this->get_modulecomponents($template_id, array('modules', 'extra-blocks')) as $component) {
		
			$component_atts = $atts[$component];
			if ($component_ret = $gd_template_processor_manager->get_processor($component)->get_template_crawlableitems($component, $component_atts)) {
			
				$ret = array_merge(
					$ret,
					$component_ret
				);
			}
		}
		
		return $ret;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_template_runtimecrawlableitems($template_id, $atts) {
		
		global $gd_template_processor_manager;

		$ret = $this->get_template_runtimecrawlableitem($template_id, $atts);

		// Override the parent to add the 'extra-blocks'
		foreach ($this->get_modulecomponents($template_id, array('modules', 'extra-blocks')) as $component) {
		
			$component_atts = $atts[$component];
			if ($component_ret = $gd_template_processor_manager->get_processor($component)->get_template_runtimecrawlableitems($component, $component_atts)) {
			
				$ret = array_merge(
					$ret,
					$component_ret
				);
			}
		}
		
		return $ret;
	}
	
	function is_dynamic_template_source($template_id, $atts) {
	
		return false;
	}
	
	function get_dynamic_templates_sources($template_id, $atts) {
	
		global $gd_template_processor_manager;

		// If the template path has been set to true, then from this template downwards all templates are dynamic
		$ret = $this->get_currentlevel_dynamic_templates_sources($template_id, $atts);
		
		// If not, then keep iterating down the road
		if (empty($ret)) {
		
			// In the Hierarchy Processor, all subcomponent templates are blocks
			foreach ($this->get_modulecomponents($template_id, array('modules', 'extra-blocks')) as $component) {
			
				$component_processor = $gd_template_processor_manager->get_processor($component);
				$component_atts = $atts[$component];					
				$component_ret = $component_processor->get_dynamic_templates_sources($component, $component_atts);

				$ret = array_unique(array_merge(
					$ret,
					$component_ret
				));
			}
		}
		
		return $ret;
	}
	
	function get_templates_sources($template_id, $atts) {
	
		global $gd_template_processor_manager;
	
		$ret = array();
		$template_source = $this->get_template_source($template_id, $atts);
		// Only add the ones who are different to itself, to compress output file
		// Comment Leo 28/09/2017: we must send always the template_source, even if it's similar to the template_id,
		// so that we have the information of all required template-sources for the ResourceLoader
		// Eg: otherwise loading template 'status' fails
		// if ($template_id != $template_source) {
		$ret[$template_id] = $template_source;
		// }
		
		// In the Hierarchy Processor, all subcomponent templates are blocks
		foreach ($this->get_modulecomponents($template_id, array('modules', 'extra-blocks')) as $component) {
		
			$component_processor = $gd_template_processor_manager->get_processor($component);
			$component_atts = $atts[$component];					
			$component_ret = $component_processor->get_templates_sources($component, $component_atts);

			$ret = array_merge(
				$ret,
				$component_ret
			);
		}
		
		return $ret;
	}
	
	function get_templates_extra_sources($template_id, $atts) {
	
		global $gd_template_processor_manager;
	
		$ret = array();
		if ($template_extra_sources = $this->get_template_extra_sources($template_id, $atts)) {
			$ret[$template_id] = $template_extra_sources;
		}
		
		// In the Hierarchy Processor, all subcomponent templates are blocks
		foreach ($this->get_modulecomponents($template_id, array('modules', 'extra-blocks')) as $component) {
		
			$component_processor = $gd_template_processor_manager->get_processor($component);
			$component_atts = $atts[$component];					
			$component_ret = $component_processor->get_templates_extra_sources($component, $component_atts);

			$ret = array_merge(
				$ret,
				$component_ret
			);
		}
		
		return $ret;
	}
	
	function get_templates_resources($template_id, $atts) {
	
		global $gd_template_processor_manager;
	
		$ret = array();
		if ($template_resources = $this->get_template_resources($template_id, $atts)) {
			$ret[$template_id] = $template_resources;
		}
		
		// In the Hierarchy Processor, all subcomponent templates are blocks
		foreach ($this->get_modulecomponents($template_id, array('modules', 'extra-blocks')) as $component) {
		
			$component_processor = $gd_template_processor_manager->get_processor($component);
			$component_atts = $atts[$component];					
			$component_ret = $component_processor->get_templates_resources($component, $component_atts);

			$ret = array_merge(
				$ret,
				$component_ret
			);
		}
		
		return $ret;
	}

	function get_templates_cbs($template_id, $atts) {
	
		global $gd_template_processor_manager;
	
		// Add the initial template for the Hierarchy Template
		$settings_id = $this->get_settings_id($template_id);
		$ret = array(
			$settings_id => array(
				'cbs' => array(),
				'actions' => array()
			)
		);
		if ($this->get_template_cb($template_id, $atts)) {
			$ret[$settings_id]['cbs'][] = $template_id;

			// The cb applies to what actions
			if ($template_cb_actions = $this->get_template_cb_actions($template_id, $atts)) {

				$ret[$settings_id]['actions'][$template_id] = $template_cb_actions;
			}
		}
		
		// In the Hierarchy Processor, all subcomponent templates are blocks
		foreach ($this->get_modulecomponents($template_id, array('modules', 'extra-blocks')) as $component) {
		
			$component_processor = $gd_template_processor_manager->get_processor($component);
			$component_atts = $atts[$component];
			$component_settings_id = $component_processor->get_settings_id($component);
					
			$component_ret = $component_processor->get_templates_cbs($component, $component_atts);

			$ret[$component_settings_id] = $component_ret;
		}
		
		return $ret;
	}

	function get_templates_paths($template_id, $atts) {
	
		global $gd_template_processor_manager;
	
		// Add the initial template for the Hierarchy Template
		$settings_id = $this->get_settings_id($template_id);
		$ret = array(
			$settings_id => array(
				
				$template_id => array()
			)
		);

		// In the Hierarchy Processor, all subcomponent templates are blocks
		foreach ($this->get_modulecomponents($template_id, array('modules', 'extra-blocks')) as $component) {
		
			$component_processor = $gd_template_processor_manager->get_processor($component);
			$component_atts = $atts[$component];
			$component_settings_id = $component_processor->get_settings_id($component);
					
			$component_ret = $component_processor->get_templates_paths($component, $component_atts);

			$ret[$component_settings_id] = $component_ret;
		}
		
		return $ret;
	}
	

	function get_template_cb($template_id, $atts) {
	
		return $template_id;
	}

	function get_js_setting($template_id, $atts) {

		$ret = parent::get_js_setting($template_id, $atts);

		if ($branches = $this->get_initjs_blockbranches($template_id, $atts)) {
			
			$ret['initjs-blockbranches'] = $branches;
		}

		return $ret;
	}

	function get_js_setting_key($template_id, $atts) {

		return $this->get_settings_id($template_id);
	}

	function get_js_settings($template_id, $atts) {
			
		global $gd_template_processor_manager;

		$ret = array();		
		if ($js_setting = $this->get_js_setting($template_id, $atts)) {

			$ret[$this->get_js_setting_key($template_id, $atts)] = $js_setting;
			// $ret[$this->get_settings_id($template_id)] = $js_setting;
		}

		// In the Hierarchy Processor, all subcomponent templates are blocks
		foreach ($this->get_modulecomponents($template_id, array('modules', 'extra-blocks')) as $component) {
		
			$component_processor = $gd_template_processor_manager->get_processor($component);
			$component_atts = $atts[$component];
			$component_settings_id = $component_processor->get_settings_id($component);

			$ret[$component_settings_id] = $component_processor->get_js_settings($component, $component_atts);
		}

		return $ret;
	}

	function get_js_runtimesettings($template_id, $atts) {
			
		global $gd_template_processor_manager;

		$ret = array();		
		if ($js_setting = $this->get_js_runtimesetting($template_id, $atts)) {

			$ret[$this->get_js_setting_key($template_id, $atts)] = $js_setting;
		}

		// In the Hierarchy Processor, all subcomponent templates are blocks
		foreach ($this->get_modulecomponents($template_id, array('modules', 'extra-blocks')) as $component) {
		
			$component_processor = $gd_template_processor_manager->get_processor($component);
			$component_atts = $atts[$component];
			$component_settings_id = $component_processor->get_settings_id($component);

			$ret[$component_settings_id] = $component_processor->get_js_runtimesettings($component, $component_atts);
		}

		return $ret;
	}

	function get_pagesection_jsmethods($template_id, $atts) {
			
		global $gd_template_processor_manager;

		$ret = array();		
		
		// In the Hierarchy Processor, all subcomponent templates are blocks
		foreach ($this->get_modulecomponents($template_id, array('modules', 'extra-blocks')) as $component) {
		
			$component_processor = $gd_template_processor_manager->get_processor($component);
			$component_atts = $atts[$component];
			$ret = array_merge(
				$ret,
				$component_processor->get_pagesection_jsmethods($component, $component_atts)
			);
		}

		// for the decorated: first call the recursive method, then assign this value, so if there's a decorated value,
		// we wait for the decorated to first have its value assigned
		if ($jsmethod = $this->get_filtered_pagesection_jsmethod($template_id, $atts)) {
			$ret[$template_id] = $jsmethod;
		}

		return $ret;
	}

	function get_block_jsmethods($template_id, $atts) {
			
		global $gd_template_processor_manager;

		$ret = array();	

		// In the Hierarchy Processor, all subcomponent templates are blocks
		foreach ($this->get_modulecomponents($template_id, array('modules', 'extra-blocks')) as $component) {
		
			$component_processor = $gd_template_processor_manager->get_processor($component);
			$component_atts = $atts[$component];
			$component_settings_id = $component_processor->get_settings_id($component);
			$ret[$component_settings_id] = $component_processor->get_block_jsmethods($component, $component_atts);
		}

		return $ret;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_data_setting($template_id, $atts) {

		$ret = parent::get_data_setting($template_id, $atts);
		$ret['iohandler'] = $this->get_iohandler($template_id);
		return $ret;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_data_settings($template_id, $atts) {
			
		global $gd_template_processor_manager;
		
		$settings_id = $this->get_settings_id($template_id);
		$data_setting = $this->get_data_setting($template_id, $atts);
		$ret = array(
			$settings_id => $data_setting
		);

		// In the Hierarchy Processor, all subcomponent templates are blocks
		foreach ($this->get_modulecomponents($template_id, array('modules', 'extra-blocks')) as $component) {
		
			$component_processor = $gd_template_processor_manager->get_processor($component);
			$component_atts = $atts[$component];
			$component_settings_id = $component_processor->get_settings_id($component);
			
			$component_ret = $component_processor->get_data_settings($component, $component_atts);
			
			// Place all block configurations below $block_id
			$ret[$component_settings_id] = $component_ret;
		}

		return $ret;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_runtime_datasettings($template_id, $atts) {
			
		global $gd_template_processor_manager;
		
		$settings_id = $this->get_settings_id($template_id);
		$data_setting = $this->get_runtime_datasetting($template_id, $atts);
		$ret = array(
			$settings_id => $data_setting
		);

		// In the Hierarchy Processor, all subcomponent templates are blocks
		foreach ($this->get_modulecomponents($template_id, array('modules', 'extra-blocks')) as $component) {
		
			$component_processor = $gd_template_processor_manager->get_processor($component);
			$component_atts = $atts[$component];
			$component_settings_id = $component_processor->get_settings_id($component);
			
			$component_ret = $component_processor->get_runtime_datasettings($component, $component_atts);
			
			// Place all block configurations below $block_id
			$ret[$component_settings_id] = $component_ret;
		}

		return $ret;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_modules($template_id) {
			
		if ($blocks = $this->get_blocks($template_id)) {
			return array_unique(array_flatten(array_values($blocks)));
		}

		return parent::get_modules($template_id);
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_extra_modules($template_id) {
			
		if ($blocks = $this->get_extra_blocks($template_id)) {
			return array_unique(array_flatten(array_values($blocks)));
		}

		return parent::get_extra_modules($template_id);
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_extra_blocks($template_id) {
			
		global $gd_template_processor_manager;
		$ret = array();
		// In the Hierarchy Processor, all subcomponent templates are blocks
		foreach ($this->get_modulecomponents($template_id, array('modules')) as $component) {
			$component_processor = $gd_template_processor_manager->get_processor($component);
			$component_atts = $atts[$component];
			$component_settings_id = $component_processor->get_settings_id($component);
			
			$component_ret = $component_processor->get_extra_blocks($component);
			
			// Place all block configurations below $block_id
			$ret = array_merge_recursive(
				$ret,
				$component_ret
			);
		}

		// Eliminate duplicates && remove unique blocks (these will be included later, through GD_TEMPLATE_PAGESECTION_OPERATIONAL)
		$unique_blocks = GD_TemplateManager_Utils::get_unique_blocks();
		$unique_blockgroups = GD_TemplateManager_Utils::get_unique_blockgroups();
		foreach ($ret as $key => $components) {
			$components = array_diff(
				$components, 
				$unique_blocks,
				$unique_blockgroups
			);
			$ret[$key] = array_unique($components);
		}

		return $ret;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_database_keys($template_id, $atts) {
			
		global $gd_template_processor_manager;
		
		$ret = array();				

		// In the Hierarchy Processor, all subcomponent templates are blocks
		foreach ($this->get_modulecomponents($template_id, array('modules', 'extra-blocks')) as $component) {

			$component_processor = $gd_template_processor_manager->get_processor($component);
			$component_atts = $atts[$component];
			$component_settings_id = $component_processor->get_settings_id($component);
			
			$component_ret = $component_processor->get_database_keys($component, $component_atts);

			// Place all block configurations below $block_id
			$ret[$component_settings_id] = $component_ret;
		}

		return $ret;
	}

	//-------------------------------------------------
	// PROTECTED Functions
	//-------------------------------------------------

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	protected function get_atts_hierarchy_initial($template_id) {

		return array();
	}

	protected function get_atts_block_initial($template_id, $subcomponent) {

		/***********************************************************/
		/** Repeated from "parent" class! */
		/***********************************************************/
		global $gd_template_processor_manager, $gd_dataload_manager, $gd_dataquery_manager;

		$block_processor = $gd_template_processor_manager->get_processor($subcomponent);

		/***********************************************************/
		/** Repeated from "parent" class! */
		/***********************************************************/
		// Filter
		$filter = $block_processor->get_filter_template($subcomponent);
		// if ($filter = $block_processor->get_filter_template($subcomponent)) {

		// 	$filter_processor = $gd_template_processor_manager->get_processor($filter);
		// 	$filter_object = $filter_processor->get_filter_object($filter);
		// }

		/***********************************************************/
		/** Repeated from "parent" class! */
		/***********************************************************/
		$block_atts = array(
			
			// Add the hierachy template id to the atts to be used down the line
			'pagesection-template-id' => $template_id,

			// Template Block Id
			'block-template-id' => $subcomponent,

			// Templates use this space to pass back and forth their own configurations
			// (Eg: in homepage we can define the layout for the Carousel)
			'template-conf' => array(),

			// For configuration that does not depend on a given template_id
			'template-generalconf' => array(),

			// Configurations for the formcomponents
			'formcomponent-conf' => array(),

			// Filter
			'filter' => $filter,

			// Filter Object
			// 'filter-object' => $filter_object,
		);

		// All blocks added under the pageSection can have class "pop-outerblock"
		// Check if the subcomponent is in the modules => it doesn't include 'extra-blocks', where blocks included by blockgroups are (which should not be pop-outerblock)
		if (in_array($subcomponent, $this->get_modules($template_id))) {
			$this->append_att($subcomponent, $block_atts, 'class', 'pop-outerblock');
			
			// is-mainblock: used to allow the block/blockgroup to reload if user logs in, or destroy if pagetab is closed (with user's data)
			// $this->append_att($subcomponent, $block_atts, 'is-mainblock', true);
		}

		// Allow to add more stuff. Eg: submenu.
		$block_atts = apply_filters('GD_Template_Processor_PageSectionsBase:get_atts_block_initial', $block_atts, $template_id, $subcomponent, $this);
		return $block_atts;
	}	

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	protected function get_blockunits($template_id) {

		global $gd_template_processor_manager;

		$blocks = $this->get_blocks($template_id);
		
		// Comment Leo 05/11/2015: Commented because we're not using INDEPENDENT anymore, and I believe
		// this logic would not work anyway. Eg:
		// - it is getting only get_blockgroup_blocks and not get_blockgroup_blockgroups
		// - each blockgroup knows how to display its own contained blocks, no need to add them into independent
		// - beause of the above, if drawing .independent blockunits in a .tmpl, they might be repeated
		// Add the blocks from the blockgroups
		// $blockgroups = $this->get_all_blockgroups($template_id);
		// if ($blockgroups) {
		// 	foreach ($blockgroups as $blockgroup) {
		// 		$blocks[GD_TEMPLATEBLOCKSETTINGS_INDEPENDENT] = array_unique(
		// 			array_merge(
		// 				$blocks[GD_TEMPLATEBLOCKSETTINGS_INDEPENDENT],
		// 				$gd_template_processor_manager->get_processor($blockgroup)->get_blockgroup_blocks($blockgroup)
		// 			)
		// 		);
		// 	}
		// }

		// When fetching JSON for the Block, we only need the main block and nothing else
		if ($this->load_only_main_block($template_id)) {

			return $blocks;
		}

		// Merge with all extra_blocks
		$ret = array_merge_recursive(
			$blocks,
			$this->get_extra_blocks($template_id)
		);
		foreach ($ret as $block_key => $blocks) {
			$ret[$block_key] = array_unique($ret[$block_key]);
		}

		return $ret;
	}

	function get_frontend_mergeid($template_id, $atts) {

		return $this->get_frontend_id($template_id, $atts);
	}

	protected function get_initjs_blockbranches($template_id, $atts) {

		return array();
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	protected function get_iohandler($template_id) {

		return GD_DATALOAD_IOHANDLER_PAGESECTION;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	protected function add_blocks(&$ret, $blocks, $block_key) {

		// Remove the unique blocks, they will only be printed under GD_TEMPLATE_PAGESECTION_OPERATIONAL
		$extra_blocks = array_diff(
			$blocks, 
			GD_TemplateManager_Utils::get_unique_blocks()
		);

		parent::add_blocks($ret, $extra_blocks, $block_key);
	}
	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	protected function add_blockgroups(&$ret, $blockgroups, $block_key) {

		// Remove the unique blocks, they will only be printed under GD_TEMPLATE_PAGESECTION_OPERATIONAL
		$extra_blocks = array_diff(
			$blockgroups, 
			GD_TemplateManager_Utils::get_unique_blockgroups()
		);

		parent::add_blockgroups($ret, $extra_blocks, $block_key);
	}
	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	protected function get_blocks($template_id) {

		return array(
			GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP => array(),
			GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUPINDEPENDENT => array(),
			GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUPREPLICABLE => array(),
			// GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUPFRAME => array(),
			GD_TEMPLATEBLOCKSETTINGS_MAIN => array(),
			GD_TEMPLATEBLOCKSETTINGS_INDEPENDENT => array(),
			GD_TEMPLATEBLOCKSETTINGS_REPLICABLE => array(),
			GD_TEMPLATEBLOCKSETTINGS_FRAME => array(),
			GD_TEMPLATEBLOCKSETTINGS_FRAMEREPLICABLE => array(),
		);
	}
	

	//-------------------------------------------------
	// PRIVATE Functions
	//-------------------------------------------------

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	private function set_blockgroup_atts(&$atts, $template_id, $blockgroups) {
	
		global $gd_template_processor_manager;

		// Allow the BlockGroups to first set the atts for their contained Blocks
		if ($blockgroups) {
			foreach ($blockgroups as $blockgroup) {

				// For each blockgroup, make them initialize its own blocks/blockgroups
				$blockgroup_processor = $gd_template_processor_manager->get_processor($blockgroup);
				$blockgroup_blockgroups = $blockgroup_processor->get_blockgroup_blockgroups($blockgroup);
				foreach ($blockgroup_blockgroups as $blockgroup_blockgroup) {

					// Initialize block atts
					$blockgroup_processor->init_atts_blockgroup_blockgroup($blockgroup, $blockgroup_blockgroup, $atts[$blockgroup_blockgroup], $atts[$blockgroup]);

					// If this template is replicable, then all subcomponents also are
					if ($atts[$blockgroup]['replicable']) {
						$atts[$blockgroup_blockgroup]['replicable'] = true;
					}
				}

				$blockgroup_blocks = $blockgroup_processor->get_blockgroup_blocks($blockgroup);
				foreach ($blockgroup_blocks as $blockgroup_block) {

					// Initialize block atts
					$blockgroup_processor->init_atts_blockgroup_block($blockgroup, $blockgroup_block, $atts[$blockgroup_block], $atts[$blockgroup]);

					// If this template is replicable, then all subcomponents also are
					if ($atts[$blockgroup]['replicable']) {
						$atts[$blockgroup_block]['replicable'] = true;
					}
				}
			}
		}
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	private function add_module_atts($template_id, &$atts, $component) {
	
		global $gd_template_processor_manager;

		$block_atts = $atts[$component];
		$component_processor = $gd_template_processor_manager->get_processor($component);

		// // Only after getting the first block_atts, we can get the block-settings-id
		// $component_settings_id = $component_processor->get_settings_id($component);
		// $block_atts['block-settings-id'] = $component_settings_id;
		// $block_atts['block-id'] = $component_processor->get_frontend_id($component, $block_atts);
		
		// Store the block results under $component and not under $component_settings_id, because
		// $component_settings_id needs the $atts['is-main-section'] to calculate its value, so we need $atts
		// to get $component_settings_id and $component_settings_id to get $atts
		// Using $component, we don't have this problem, since this value is already known
		$atts[$component] = $component_processor->init_atts($component, $block_atts);
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	private function get_all_blockgroups($template_id) {

		$blocks = $this->get_blocks($template_id);

		return array_merge(
			$blocks[GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP],
			$blocks[GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUPINDEPENDENT],
			$blocks[GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUPREPLICABLE]
		);
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	private function is_replicable($template_id, $component) {

		$blocks = $this->get_blocks($template_id);

		return in_array(
			$component,
			array_merge(
				$blocks[GD_TEMPLATEBLOCKSETTINGS_REPLICABLE],
				$blocks[GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUPREPLICABLE]
			)
		);
	}
}

