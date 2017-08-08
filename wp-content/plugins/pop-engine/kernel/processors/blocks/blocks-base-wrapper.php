<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_Processor_BlocksBaseWrapper extends PoP_ProcessorBaseWrapper {

	function integrate_execution_bag($template_id, $atts, &$data_settings, $execution_bag) {	

		return $this->processor->integrate_execution_bag($template_id, $atts, $data_settings, $execution_bag);
	}

	function get_filter_template($template_id) {

		return $this->processor->get_filter_template($template_id);
	}

	function get_query_url($template_id, $atts) {		
		
		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function get_query_multidomain_urls($template_id, $atts) {		
		
		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function is_blockgroup($template_id) {

		return $this->processor->is_blockgroup($template_id);
	}

	// function get_dataloadsource_domain($template_id, $atts) {

	// 	return $this->process($template_id, $atts, __FUNCTION__);
	// }

	function get_dataloader($template_id) {

		return $this->processor->get_dataloader($template_id);
	}

	function get_dataload_source($template_id, $atts) {

		return $this->process($template_id, $atts, __FUNCTION__);
	}
	function get_dataload_multidomain_sources($template_id, $atts) {

		return $this->process($template_id, $atts, __FUNCTION__);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Settings Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_processorwrapper_manager;
$gd_template_processorwrapper_manager->add('PoP_Processor_BlocksBase', 'PoP_Processor_BlocksBaseWrapper');
