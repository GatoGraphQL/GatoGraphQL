<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ProcessorBaseWrapper {

	var $processor;
	function __construct($processor) {

		$this->processor = $processor;
	}

	//-------------------------------------------------
	// INTERNAL Functions
	//-------------------------------------------------

	protected function get_pagesection_cache_key($template_id, $atts) {

		return $atts['pagesection-template-id'];
	}
	protected function get_module_cache_key($template_id, $atts) {

		return $atts['block-template-id'];
	}

	protected function is_cached($template_id, $atts, $method) {

		$pagesection = $this->get_pagesection_cache_key($template_id, $atts);
		$module = $this->get_module_cache_key($template_id, $atts);

		global $gd_template_processor_runtimecache;
		return $gd_template_processor_runtimecache->is_cached($pagesection, $module, $template_id, $method);
	}

	protected function get_cache($template_id, $atts, $method) {

		$pagesection = $this->get_pagesection_cache_key($template_id, $atts);
		$module = $this->get_module_cache_key($template_id, $atts);

		global $gd_template_processor_runtimecache;
		return $gd_template_processor_runtimecache->get_cache($pagesection, $module, $template_id, $method);
	}

	protected function add_cache($template_id, $atts, $method, $cache) {

		$pagesection = $this->get_pagesection_cache_key($template_id, $atts);
		$module = $this->get_module_cache_key($template_id, $atts);

		global $gd_template_processor_runtimecache;
		return $gd_template_processor_runtimecache->add_cache($pagesection, $module, $template_id, $method, $cache);
	}

	protected function process($template_id, $atts, $function) {

		if ($this->is_cached($template_id, $atts, $function)) {

			return $this->get_cache($template_id, $atts, $function);
		}

		$ret = $this->processor->$function($template_id, $atts);
		$this->add_cache($template_id, $atts, $function, $ret);
		return $ret;
	}

	//-------------------------------------------------
	// PUBLIC Functions: cannot cache
	//-------------------------------------------------

	function get_templates_to_process() {

		return $this->processor->get_templates_to_process();
	}
	
	function get_settings_id($template_id) {

		return $this->processor->get_settings_id($template_id);
	}

	function get_extra_blocks($template_id) {

		return $this->processor->get_extra_blocks($template_id);
	}

	function get_modulecomponents($template_id, $components = array()) {

		return $this->processor->get_modulecomponents($template_id, $components);
	}
	
	// function get_decorated_template($template_id) {

	// 	return $this->processor->get_decorated_template($template_id);
	// }

	function get_subcomponent_modules($template_id) {
	
		return $this->processor->get_subcomponent_modules($template_id);
	}

	function get_modules($template_id) {

		return $this->processor->get_modules($template_id);
	}

	// function get_template_modules($template_id) {

	// 	return $this->processor->get_template_modules($template_id);
	// }


	
	//-------------------------------------------------
	// PUBLIC Functions: custom behaviour
	//-------------------------------------------------

	function init_atts($template_id, &$atts) {

		// For atts, if it is cached simply return the same object received, we don't store a separate
		// copy in the cache since this one object must be unique (it will still be edited going down other templates)
		$function = __FUNCTION__;
		if ($this->is_cached($template_id, $atts, $function)) {

			return $atts;
		}

		// Simply cache any value
		$this->add_cache($template_id, $atts, $function, true);
		return $this->processor->init_atts($template_id, $atts);
	}

	//-------------------------------------------------
	// PUBLIC Functions: cache
	//-------------------------------------------------

	function get_data_fields($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function get_runtime_datafields($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function get_dataload_extend($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function get_database_key($template_id, $atts) {

		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function get_database_keys($template_id, $atts) {

		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function get_data_setting($template_id, $atts) {

		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function get_data_settings($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}
	
	function get_runtime_datasetting($template_id, $atts) {

		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function get_runtime_datasettings($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}
	
	function get_component_configuration_type($template_id, $atts) {
			
		return $this->process($template_id, $atts, __FUNCTION__);
	}
	
	function get_template_configurations($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function get_template_configuration($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}	
	
	function get_template_runtimeconfigurations($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function get_template_runtimeconfiguration($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}	
}

/**---------------------------------------------------------------------------------------------------------------
 * Settings Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_processorwrapper_manager;
$gd_template_processorwrapper_manager->add('PoP_ProcessorBase', 'PoP_ProcessorBaseWrapper');