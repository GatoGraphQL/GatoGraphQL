<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_PUBLISHED', PoP_TemplateIDUtils::get_template_definition('layout-published'));
define ('GD_TEMPLATE_LAYOUT_WIDGETPUBLISHED', PoP_TemplateIDUtils::get_template_definition('layout-widgetpublished'));

class GD_Template_Processor_PublishedLayouts extends GD_Template_Processor_PostStatusDateLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_PUBLISHED,
			GD_TEMPLATE_LAYOUT_WIDGETPUBLISHED,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_PublishedLayouts();