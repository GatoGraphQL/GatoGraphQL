<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_AAL_TEMPLATE_LAYOUTUSER_MEMBERPRIVILEGES', PoP_TemplateIDUtils::get_template_definition('ure-aal-layoutuser-memberprivileges-desc'));

class Wassup_URE_AAL_Template_Processor_MemberPrivilegesLayouts extends GD_URE_Template_Processor_MemberPrivilegesLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_AAL_TEMPLATE_LAYOUTUSER_MEMBERPRIVILEGES,
		);
	}

	function get_description($template_id, $atts) {

		switch ($template_id) {

			case GD_URE_AAL_TEMPLATE_LAYOUTUSER_MEMBERPRIVILEGES:
				
				return sprintf(
					'<em>%s</em>',
					__('Privileges:', 'poptheme-wassup')
				);
		}

		return parent::get_description($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_URE_AAL_Template_Processor_MemberPrivilegesLayouts();