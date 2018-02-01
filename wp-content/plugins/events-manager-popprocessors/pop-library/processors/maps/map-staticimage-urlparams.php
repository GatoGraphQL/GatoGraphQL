<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MAP_STATICIMAGE_URLPARAM', PoP_TemplateIDUtils::get_template_definition('em-map-staticimage-urlparam'));

class GD_Template_Processor_MapStaticImageURLParams extends GD_Template_Processor_MapStaticImageURLParamsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MAP_STATICIMAGE_URLPARAM,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_MapStaticImageURLParams();