<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_Processor_PageSectionsBase extends PoP_ProcessorBase {

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------

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

	function get_pagesection_extensions($template_id) {

		return array();
	}

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

	function get_template_configuration($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = parent::get_template_configuration($template_id, $atts);	
		
		$ret[GD_JS_SETTINGSID/*'settings-id'*/] = $this->get_settings_id($template_id);

		// Add the extension templates
		$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['extensions'] = $this->get_pagesection_extensions($template_id);		

		return $ret;
	}

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

	function get_data_setting($template_id, $atts) {

		$ret = parent::get_data_setting($template_id, $atts);
		$ret['iohandler'] = $this->get_iohandler($template_id);
		return $ret;
	}


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

	function get_modules($template_id) {
			
		if ($blocks = $this->get_blocks($template_id)) {
			return array_unique(array_flatten(array_values($blocks)));
		}

		return parent::get_modules($template_id);
	}

	function get_extra_modules($template_id) {
			
		if ($blocks = $this->get_extra_blocks($template_id)) {
			return array_unique(array_flatten(array_values($blocks)));
		}

		return parent::get_extra_modules($template_id);
	}

	/**
	 * Return the extra blocks loaded by each block
	 */
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
		$unique_blocks = PoPManager_Utils::get_unique_blocks();
		$unique_blockgroups = PoPManager_Utils::get_unique_blockgroups();
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

	protected function get_atts_hierarchy_initial($template_id) {

		return array();
	}

	/**
	 * Allow to set the initial atts for the block from the Hierarchy level
	 */
	protected function get_atts_block_initial($template_id, $subcomponent) {

		global $gd_template_processor_manager, $gd_dataload_manager, $gd_dataquery_manager;
		$block_processor = $gd_template_processor_manager->get_processor($subcomponent);

		// Filter
		$filter = $block_processor->get_filter_template($subcomponent);
		// if ($filter = $block_processor->get_filter_template($subcomponent)) {

		// 	$filter_processor = $gd_template_processor_manager->get_processor($filter);
		// 	$filter_object = $filter_processor->get_filter_object($filter);
		// }

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
		return $block_atts;
	}	

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

	protected function get_iohandler($template_id) {

		return GD_DATALOAD_IOHANDLER_PAGESECTION;
	}

	// Redefine function add_blocks to exclude unique_blocks
	protected function add_blocks(&$ret, $blocks, $block_key) {

		// Remove the unique blocks, they will only be printed under GD_TEMPLATE_PAGESECTION_OPERATIONAL
		$extra_blocks = array_diff(
			$blocks, 
			PoPManager_Utils::get_unique_blocks()
		);

		parent::add_blocks($ret, $extra_blocks, $block_key);
	}
	protected function add_blockgroups(&$ret, $blockgroups, $block_key) {

		// Remove the unique blocks, they will only be printed under GD_TEMPLATE_PAGESECTION_OPERATIONAL
		$extra_blocks = array_diff(
			$blockgroups, 
			PoPManager_Utils::get_unique_blockgroups()
		);

		parent::add_blockgroups($ret, $extra_blocks, $block_key);
	}

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

	private function get_all_blockgroups($template_id) {

		$blocks = $this->get_blocks($template_id);

		return array_merge(
			$blocks[GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP],
			$blocks[GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUPINDEPENDENT],
			$blocks[GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUPREPLICABLE]
		);
	}

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

