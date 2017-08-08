<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPFrontend_Template_Processor_TopLevelsBase extends GD_Template_ProcessorBase {

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_redirect_url($template_id) {
	
		return null;
	}	

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_query_url($template_id, $atts) {
			
		global $gd_template_processor_manager;

		$ret = array();
		foreach ($this->get_modules($template_id) as $module) {
			
			$module_atts = $atts[$module];
			$module_settings_id = $gd_template_processor_manager->get_processor($module)->get_settings_id($module);
			$ret[$module_settings_id] = $gd_template_processor_manager->get_processor($module)->get_query_url($module, $module_atts);
		}
		
		return $ret;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_query_multidomain_urls($template_id, $atts) {
			
		global $gd_template_processor_manager;

		$ret = array();
		foreach ($this->get_modules($template_id) as $module) {
			
			$module_atts = $atts[$module];
			$module_settings_id = $gd_template_processor_manager->get_processor($module)->get_settings_id($module);
			$ret[$module_settings_id] = $gd_template_processor_manager->get_processor($module)->get_query_multidomain_urls($module, $module_atts);
		}
		
		return $ret;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_query_domains($template_id, $atts) {
			
		global $gd_template_processor_manager;

		// Return a list of domains
		$ret = array();
		foreach ($this->get_modules($template_id) as $module) {
			
			$module_atts = $atts[$module];
			$ret = array_merge(
				$ret,
				$gd_template_processor_manager->get_processor($module)->get_query_domains($module, $module_atts)
			);
		}
		
		return array_unique($ret);
	}

	//-------------------------------------------------
	// PUBLIC Overriding Functions
	//-------------------------------------------------

	function get_template_source($template_id, $atts) {

		return null;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_settings_id($template_id) {

		return GD_TEMPLATEID_TOPLEVEL_SETTINGSID;
	}

	function get_template_cb($template_id, $atts) {
	
		return $template_id;
	}
	
	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function init_atts($template_id, &$atts) {
	
		global $gd_template_processor_manager;

		$ret = array();
		foreach ($this->get_modules($template_id) as $module) {
			$initial_atts = array();
			$ret[$module] = $gd_template_processor_manager->get_processor($module)->init_atts($module, $initial_atts);
		}

		return $ret;
	}

	function get_id($template_id, $atts) {
	
		return $this->get_settings_id($template_id);
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_template_configurations($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = array();
		foreach ($this->get_modules($template_id) as $module) {
			
			$ret = array_merge(
				$ret,
				$gd_template_processor_manager->get_processor($module)->get_template_configurations($module, $atts[$module])
			);
		}

		return $ret;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_template_runtimeconfigurations($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = array();
		foreach ($this->get_modules($template_id) as $module) {
			
			$module_atts = $atts[$module];
			$module_settings_id = $gd_template_processor_manager->get_processor($module)->get_settings_id($module);
			$ret[$module_settings_id] = $gd_template_processor_manager->get_processor($module)->get_template_runtimeconfigurations($module, $module_atts);
		}
		
		return $ret;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_template_crawlableitems($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = array();
		foreach ($this->get_modules($template_id) as $module) {
			
			$ret = array_merge(
				$ret,
				$gd_template_processor_manager->get_processor($module)->get_template_crawlableitems($module, $atts[$module])
			);
		}

		return $ret;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_template_runtimecrawlableitems($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = array();
		foreach ($this->get_modules($template_id) as $module) {
			
			$ret = array_merge(
				$ret,
				$gd_template_processor_manager->get_processor($module)->get_template_runtimecrawlableitems($module, $atts[$module])
			);
		}

		return $ret;
	}
	
	function get_templates_sources($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = array();
		foreach ($this->get_modules($template_id) as $module) {
			
			$module_atts = $atts[$module];
			$ret = array_merge(
				$ret,
				$gd_template_processor_manager->get_processor($module)->get_templates_sources($module, $module_atts)
			);
		}
		
		return $ret;
	}

	function get_templates_cbs($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = array();
		foreach ($this->get_modules($template_id) as $module) {
			
			$module_atts = $atts[$module];
			$module_settings_id = $gd_template_processor_manager->get_processor($module)->get_settings_id($module);
			$ret[$module_settings_id] = $gd_template_processor_manager->get_processor($module)->get_templates_cbs($module, $module_atts);
		}
		
		return $ret;
	}

	function get_templates_paths($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = array();
		foreach ($this->get_modules($template_id) as $module) {
			
			$module_atts = $atts[$module];
			$module_settings_id = $gd_template_processor_manager->get_processor($module)->get_settings_id($module);
			$ret[$module_settings_id] = $gd_template_processor_manager->get_processor($module)->get_templates_paths($module, $module_atts);
		}
		
		return $ret;
	}

	function get_js_settings($template_id, $atts) {
			
		global $gd_template_processor_manager;

		$ret = array();
		foreach ($this->get_modules($template_id) as $module) {
			
			$module_atts = $atts[$module];
			$module_settings_id = $gd_template_processor_manager->get_processor($module)->get_settings_id($module);
			$ret[$module_settings_id] = $gd_template_processor_manager->get_processor($module)->get_js_settings($module, $module_atts);
		}
		
		return $ret;
	}

	function get_js_runtimesettings($template_id, $atts) {
			
		global $gd_template_processor_manager;

		$ret = array();
		foreach ($this->get_modules($template_id) as $module) {
			
			$module_atts = $atts[$module];
			$module_settings_id = $gd_template_processor_manager->get_processor($module)->get_settings_id($module);
			$ret[$module_settings_id] = $gd_template_processor_manager->get_processor($module)->get_js_runtimesettings($module, $module_atts);
		}
		
		return $ret;
	}

	function get_pagesection_jsmethods($template_id, $atts) {
			
		global $gd_template_processor_manager;

		$ret = array();
		foreach ($this->get_modules($template_id) as $module) {
			
			$module_atts = $atts[$module];
			$module_settings_id = $gd_template_processor_manager->get_processor($module)->get_settings_id($module);
			$ret[$module_settings_id] = $gd_template_processor_manager->get_processor($module)->get_pagesection_jsmethods($module, $module_atts);
		}
		
		return $ret;
	}
	function get_block_jsmethods($template_id, $atts) {
			
		global $gd_template_processor_manager;

		$ret = array();
		foreach ($this->get_modules($template_id) as $module) {
			
			$module_atts = $atts[$module];
			$module_settings_id = $gd_template_processor_manager->get_processor($module)->get_settings_id($module);
			$ret[$module_settings_id] = $gd_template_processor_manager->get_processor($module)->get_block_jsmethods($module, $module_atts);
		}
		
		return $ret;
	}
	
	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	protected function get_toplevel_iohandler() {

		$vars = GD_TemplateManager_Utils::get_vars();
		$iohandler = $vars['fetching-json-data'] ? GD_DATALOAD_IOHANDLER_CHECKPOINT : GD_DATALOAD_IOHANDLER_TOPLEVEL;			
		return $iohandler;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_data_setting($template_id, $atts) {

		$vars = GD_TemplateManager_Utils::get_vars();
		$iohandler = $vars['fetching-json-data'] ? GD_DATALOAD_IOHANDLER_CHECKPOINT : GD_DATALOAD_IOHANDLER_TOPLEVEL;			
		return array(
			'iohandler' => $iohandler,
			'iohandler-atts' => array(
				GD_DATALOAD_CHECKPOINTS => $this->get_checkpoints($template_id, $atts),
			)
		);
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_data_settings($template_id, $atts) {
			
		global $gd_template_processor_manager;

		$ret = array(
			$template_id => $this->get_data_setting($template_id, $atts)
		);
		foreach ($this->get_modules($template_id) as $module) {
			
			$module_atts = $atts[$module];
			$ret[$module] = $gd_template_processor_manager->get_processor($module)->get_data_settings($module, $module_atts);
		}
		
		return $ret;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_runtime_datasettings($template_id, $atts) {
			
		global $gd_template_processor_manager;

		$ret = array(
			$template_id => $this->get_runtime_datasetting($template_id, $atts)
		);
		foreach ($this->get_modules($template_id) as $module) {
			
			$module_atts = $atts[$module];
			$ret[$module] = $gd_template_processor_manager->get_processor($module)->get_runtime_datasettings($module, $module_atts);
		}
		
		return $ret;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_database_keys($template_id, $atts) {
			
		global $gd_template_processor_manager;

		$ret = array();
		foreach ($this->get_modules($template_id) as $module) {
			
			$module_atts = $atts[$module];
			$module_settings_id = $gd_template_processor_manager->get_processor($module)->get_settings_id($module);
			$ret[$module_settings_id] = $gd_template_processor_manager->get_processor($module)->get_database_keys($module, $module_atts);
		}
		
		return $ret;
	}

	//-------------------------------------------------
	// PROTECTED Functions
	//-------------------------------------------------

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	protected function get_checkpoints($template_id, $atts) {
		
		return GD_TemplateManager_Utils::get_checkpoints();
	}
}

