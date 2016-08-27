<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_EM_TEMPLATE_MAPINNER_POST', PoP_ServerUtils::get_template_definition('em-mapinner-post'));
define ('GD_EM_TEMPLATE_MAPINNER_USER', PoP_ServerUtils::get_template_definition('em-mapinner-user'));

class GD_EM_Template_Processor_MapInners extends GD_EM_Template_Processor_MapInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_EM_TEMPLATE_MAPINNER_POST,
			GD_EM_TEMPLATE_MAPINNER_USER,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_EM_TEMPLATE_MAPINNER_POST:
				
				$ret[] = GD_TEMPLATE_MAP_SCRIPT_POST;
				break;

			case GD_EM_TEMPLATE_MAPINNER_USER:
				
				$ret[] = GD_TEMPLATE_MAP_SCRIPT_USER;
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_MapInners();