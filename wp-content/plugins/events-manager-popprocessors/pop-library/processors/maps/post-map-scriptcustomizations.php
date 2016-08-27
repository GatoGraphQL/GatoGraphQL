<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MAP_SCRIPTCUSTOMIZATION_POST', PoP_ServerUtils::get_template_definition('em-map-scriptcustomization-post'));

class GD_Template_Processor_PostMapScriptCustomizations extends GD_Template_Processor_PostMapScriptCustomizationsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MAP_SCRIPTCUSTOMIZATION_POST,
		);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_PostMapScriptCustomizations();