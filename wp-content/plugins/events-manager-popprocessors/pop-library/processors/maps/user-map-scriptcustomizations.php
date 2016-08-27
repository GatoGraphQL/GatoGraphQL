<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MAP_SCRIPTCUSTOMIZATION_USER', PoP_ServerUtils::get_template_definition('em-map-scriptcustomization-user'));

class GD_Template_Processor_UserMapScriptCustomizations extends GD_Template_Processor_UserMapScriptCustomizationsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MAP_SCRIPTCUSTOMIZATION_USER,
		);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_UserMapScriptCustomizations();