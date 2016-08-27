<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Important: do NOT change the name of these templates: needed for whenever
// passing params 'pid', etc on the URL
define ('GD_TEMPLATE_FORMCOMPONENT_POSTID', PoP_ServerUtils::get_template_definition('pid', true));
define ('GD_TEMPLATE_FORMCOMPONENT_USERID', PoP_ServerUtils::get_template_definition('uid', true));
define ('GD_TEMPLATE_FORMCOMPONENT_COMMENTID', PoP_ServerUtils::get_template_definition('cid', true));

class GD_Template_Processor_UrlParamTextFormComponentInputs extends GD_Template_Processor_UrlParamTextFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_POSTID,
			GD_TEMPLATE_FORMCOMPONENT_USERID,
			GD_TEMPLATE_FORMCOMPONENT_COMMENTID,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_UrlParamTextFormComponentInputs();