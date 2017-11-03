<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_EM_TEMPLATE_MAP_POST', PoP_TemplateIDUtils::get_template_definition('em-map-post'));
define ('GD_EM_TEMPLATE_MAP_USER', PoP_TemplateIDUtils::get_template_definition('em-map-user'));

class GD_EM_Template_Processor_Maps extends GD_EM_Template_Processor_MapsBase {

	function get_templates_to_process() {
	
		return array(
			GD_EM_TEMPLATE_MAP_POST,
			GD_EM_TEMPLATE_MAP_USER,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_EM_TEMPLATE_MAP_POST => GD_EM_TEMPLATE_MAPINNER_POST,
			GD_EM_TEMPLATE_MAP_USER => GD_EM_TEMPLATE_MAPINNER_USER,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}
		
		return parent::get_inner_template($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_Maps();