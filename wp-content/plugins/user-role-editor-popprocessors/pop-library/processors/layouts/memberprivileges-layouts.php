<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_LAYOUTUSER_MEMBERPRIVILEGES', PoP_ServerUtils::get_template_definition('ure-layoutuser-memberprivileges-nodesc'));

class GD_URE_Template_Processor_MemberPrivilegesLayouts extends GD_URE_Template_Processor_MemberPrivilegesLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_LAYOUTUSER_MEMBERPRIVILEGES,
		);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_MemberPrivilegesLayouts();