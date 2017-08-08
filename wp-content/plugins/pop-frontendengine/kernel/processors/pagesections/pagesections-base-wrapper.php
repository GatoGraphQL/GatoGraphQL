<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_PageSectionsBaseWrapper extends GD_Template_ProcessorBaseWrapper {

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
$gd_template_processorwrapper_manager->add('GD_Template_Processor_PageSectionsBase', 'GD_Template_Processor_PageSectionsBaseWrapper');