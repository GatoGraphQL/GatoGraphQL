<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPFrontend_Template_Processor_TopLevelsBaseWrapper extends GD_Template_ProcessorBaseWrapper {

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	protected function get_pagesection_cache_key($template_id, $atts) {

		return $template_id;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	protected function get_module_cache_key($template_id, $atts) {

		return $template_id;
	}

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_redirect_url($template_id) {

		// This function cannot be cached, it doesn't come with $atts
		return $this->processor->get_redirect_url($template_id);
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_query_url($template_id, $atts) {
			
		return $this->process($template_id, $atts, __FUNCTION__);
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_query_multidomain_urls($template_id, $atts) {
			
		return $this->process($template_id, $atts, __FUNCTION__);
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_query_domains($template_id, $atts) {
			
		return $this->process($template_id, $atts, __FUNCTION__);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Settings Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_processorwrapper_manager;
$gd_template_processorwrapper_manager->add('PoPFrontend_Template_Processor_TopLevelsBase', 'PoPFrontend_Template_Processor_TopLevelsBaseWrapper');