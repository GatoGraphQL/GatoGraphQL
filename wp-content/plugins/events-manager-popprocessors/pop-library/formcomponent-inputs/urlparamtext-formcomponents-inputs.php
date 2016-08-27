<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Important: do NOT change the name of these templates: needed for whenever
// passing params 'lid', etc on the URL
define ('GD_TEMPLATE_FORMCOMPONENT_LOCATIONID', PoP_ServerUtils::get_template_definition('lid', true));

class GD_EM_Template_Processor_UrlParamTextFormComponentInputs extends GD_Template_Processor_UrlParamTextFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_LOCATIONID,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_UrlParamTextFormComponentInputs();