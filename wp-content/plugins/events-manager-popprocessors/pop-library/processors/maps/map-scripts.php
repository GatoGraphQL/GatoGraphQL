<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MAP_SCRIPT', PoP_ServerUtils::get_template_definition('em-map-script'));
define ('GD_TEMPLATE_MAP_SCRIPT_POST', PoP_ServerUtils::get_template_definition('em-map-script-post'));
define ('GD_TEMPLATE_MAP_SCRIPT_USER', PoP_ServerUtils::get_template_definition('em-map-script-user'));

class GD_Template_Processor_MapScripts extends GD_Template_Processor_MapScriptsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MAP_SCRIPT,
			GD_TEMPLATE_MAP_SCRIPT_POST,
			GD_TEMPLATE_MAP_SCRIPT_USER,
		);
	}

	function get_customization_template($template_id) {
	
		$customizations = array(
			GD_TEMPLATE_MAP_SCRIPT_POST => GD_TEMPLATE_MAP_SCRIPTCUSTOMIZATION_POST,
			GD_TEMPLATE_MAP_SCRIPT_USER => GD_TEMPLATE_MAP_SCRIPTCUSTOMIZATION_USER,
		);

		if ($customization = $customizations[$template_id]) {

			return $customization;
		}

		return parent::get_customization_template($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_MapScripts();