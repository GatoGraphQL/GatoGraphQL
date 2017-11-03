<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_AUTHOR_USERPHOTO', PoP_TemplateIDUtils::get_template_definition('layout-author-userphoto'));

class GD_Template_Processor_UserPhotoLayouts extends GD_Template_Processor_UserPhotoLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_AUTHOR_USERPHOTO,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_UserPhotoLayouts();