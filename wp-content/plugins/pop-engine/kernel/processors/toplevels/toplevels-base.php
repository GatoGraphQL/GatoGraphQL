<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_TOPLEVEL_HOME', PoP_ServerUtils::get_template_definition('toplevel-home'));
define ('GD_TEMPLATE_TOPLEVEL_TAG', PoP_ServerUtils::get_template_definition('toplevel-tag'));
define ('GD_TEMPLATE_TOPLEVEL_PAGE', PoP_ServerUtils::get_template_definition('toplevel-page'));
define ('GD_TEMPLATE_TOPLEVEL_SINGLE', PoP_ServerUtils::get_template_definition('toplevel-single'));
define ('GD_TEMPLATE_TOPLEVEL_AUTHOR', PoP_ServerUtils::get_template_definition('toplevel-author'));
define ('GD_TEMPLATE_TOPLEVEL_404', PoP_ServerUtils::get_template_definition('toplevel-404'));

class PoP_Processor_TopLevelsBase extends PoP_ProcessorBase {

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------

	function get_redirect_url($template_id) {
	
		return null;
	}	

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

	function get_settings_id($template_id) {

		return GD_TEMPLATEID_TOPLEVEL_SETTINGSID;
	}

	
	function init_atts($template_id, &$atts) {
	
		global $gd_template_processor_manager;

		$ret = array();
		foreach ($this->get_modules($template_id) as $module) {
			$initial_atts = array();
			$ret[$module] = $gd_template_processor_manager->get_processor($module)->init_atts($module, $initial_atts);
		}

		return $ret;
	}

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
	
	protected function get_toplevel_iohandler() {

		$vars = GD_TemplateManager_Utils::get_vars();
		$iohandler = $vars['fetching-json-data'] ? GD_DATALOAD_IOHANDLER_CHECKPOINT : GD_DATALOAD_IOHANDLER_TOPLEVEL;			
		return $iohandler;
	}
	
	function get_data_setting($template_id, $atts) {

		return array(
			'iohandler' => $this->get_toplevel_iohandler(),
			'iohandler-atts' => array(
				GD_DATALOAD_CHECKPOINTS => $this->get_checkpoints($template_id, $atts),
				// GD_DATALOAD_GETUSERINFO => $this->get_user_info($template_id, $atts)
			)
		);
	}


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

	protected function get_checkpoints($template_id, $atts) {
		
		return GD_TemplateManager_Utils::get_checkpoints();
		// global $gd_template_settingsmanager;
		// $checkpoint_settings = $gd_template_settingsmanager->get_page_checkpoints();
		// return $checkpoint_settings['checkpoints'];
	}

	// protected function get_user_info($template_id, $atts) {
			
	// 	// By default, if implementing (user) checkpoints, then retrieve the user info
	// 	return GD_TemplateManager_Utils::page_requires_user_state();
	// 	// $checkpoints = $this->get_checkpoints($template_id, $atts);
	// 	// return !is_null($checkpoints) && !empty($checkpoints);
	// }
}

