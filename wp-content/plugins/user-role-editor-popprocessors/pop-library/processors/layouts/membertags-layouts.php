<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_LAYOUTUSER_MEMBERTAGS', PoP_ServerUtils::get_template_definition('ure-layoutuser-membertags-nodesc'));

class GD_URE_Template_Processor_MemberTagsLayouts extends GD_URE_Template_Processor_MemberTagsLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_LAYOUTUSER_MEMBERTAGS,
		);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_MemberTagsLayouts();