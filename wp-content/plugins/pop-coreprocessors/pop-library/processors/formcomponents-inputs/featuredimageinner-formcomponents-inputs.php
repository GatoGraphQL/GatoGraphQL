<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_FEATUREDIMAGEINNER', PoP_ServerUtils::get_template_definition('formcomponent-featuredimage-inner'));

class GD_Template_Processor_FeaturedImageInnerComponentInputs extends GD_Template_Processor_FeaturedImageInnerFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_FEATUREDIMAGEINNER
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_FeaturedImageInnerComponentInputs();