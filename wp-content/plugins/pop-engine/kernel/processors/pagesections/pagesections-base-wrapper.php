<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_Processor_PageSectionsBaseWrapper extends PoP_ProcessorBaseWrapper {

	protected function get_pagesection_cache_key($template_id, $atts) {

		return $template_id;
	}

	protected function get_module_cache_key($template_id, $atts) {

		return $template_id;
	}

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------

	function get_query_url($template_id, $atts) {
			
		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function get_query_domains($template_id, $atts) {
			
		return $this->process($template_id, $atts, __FUNCTION__);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Settings Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_processorwrapper_manager;
$gd_template_processorwrapper_manager->add('PoP_Processor_PageSectionsBase', 'PoP_Processor_PageSectionsBaseWrapper');