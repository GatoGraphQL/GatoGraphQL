<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FETCHMORE', PoP_ServerUtils::get_template_definition('fetchmore'));

class GD_Template_Processor_FetchMore extends GD_Template_Processor_FetchMoreBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FETCHMORE,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_FetchMore();