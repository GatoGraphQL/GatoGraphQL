<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_LAYOUTUSER_MEMBERSTATUS', PoP_ServerUtils::get_template_definition('ure-layoutuser-memberstatus-nodesc'));

class GD_URE_Template_Processor_MemberStatusLayouts extends GD_URE_Template_Processor_MemberStatusLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_LAYOUTUSER_MEMBERSTATUS,
		);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_MemberStatusLayouts();