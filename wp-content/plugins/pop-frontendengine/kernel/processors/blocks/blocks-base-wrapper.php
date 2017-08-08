<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPFrontend_Processor_BlocksBaseWrapper extends GD_Template_ProcessorBaseWrapper {

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function integrate_execution_bag($template_id, $atts, &$data_settings, $execution_bag) {	

		return $this->processor->integrate_execution_bag($template_id, $atts, $data_settings, $execution_bag);
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_filter_template($template_id) {

		return $this->processor->get_filter_template($template_id);
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
	function is_blockgroup($template_id) {

		return $this->processor->is_blockgroup($template_id);
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	// function get_dataloadsource_domain($template_id, $atts) {

	// 	return $this->process($template_id, $atts, __FUNCTION__);
	// }

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_dataload_source($template_id, $atts) {

		return $this->process($template_id, $atts, __FUNCTION__);
	}
	
	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_dataload_multidomain_sources($template_id, $atts) {

		return $this->process($template_id, $atts, __FUNCTION__);
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_dataloader($template_id) {

		return $this->processor->get_dataloader($template_id);
	}

	function get_title($template_id) {		
		
		return $this->processor->get_title($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Settings Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_processorwrapper_manager;
$gd_template_processorwrapper_manager->add('PoPFrontend_Processor_BlocksBase', 'PoPFrontend_Processor_BlocksBaseWrapper');
