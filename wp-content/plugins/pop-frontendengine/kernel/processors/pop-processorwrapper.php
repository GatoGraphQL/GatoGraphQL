<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_ProcessorBaseWrapper extends PoP_ProcessorBaseWrapper {

	//-------------------------------------------------
	// PUBLIC Functions: cache
	//-------------------------------------------------

	function get_templates_sources($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function get_templates_extra_sources($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function fixed_id($template_id, $atts) {

		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function is_frontend_id_unique($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function get_frontend_id($template_id, $atts) {

		return $this->process($template_id, $atts, __FUNCTION__);
	}
	
	function get_id($template_id, $atts) {

		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function get_template_source($template_id, $atts) {

		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function get_template_extra_sources($template_id, $atts) {

		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function get_template_cb($template_id, $atts) {

		return $this->process($template_id, $atts, __FUNCTION__);
	}
	function get_template_cb_actions($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function get_template_path($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function get_templates_cbs($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function get_templates_paths($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}
	
	function get_js_setting($template_id, $atts) {

		return $this->process($template_id, $atts, __FUNCTION__);
	}
	
	function get_js_settings($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function get_js_runtimesettings($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function get_pagesection_jsmethods($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}
	
	function get_block_jsmethods($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}
	
	function get_intercept_settings($template_id, $atts) {

		return $this->process($template_id, $atts, __FUNCTION__);
	}
	function get_intercept_urls($template_id, $atts) {

		return $this->process($template_id, $atts, __FUNCTION__);
	}
	function get_extra_intercept_urls($template_id, $atts) {

		return $this->process($template_id, $atts, __FUNCTION__);
	}
	function get_intercept_type($template_id, $atts) {

		return $this->process($template_id, $atts, __FUNCTION__);
	}
	function get_intercept_target($template_id, $atts) {
		
		return $this->process($template_id, $atts, __FUNCTION__);
	}
	function get_intercept_skipstateupdate($template_id, $atts) {
		
		return $this->process($template_id, $atts, __FUNCTION__);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Settings Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_processorwrapper_manager;
$gd_template_processorwrapper_manager->add('GD_Template_ProcessorBase', 'GD_Template_ProcessorBaseWrapper');