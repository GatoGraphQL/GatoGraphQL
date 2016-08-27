<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_AAL_TEMPLATE_LAYOUTUSER_MEMBERSTATUS', PoP_ServerUtils::get_template_definition('ure-aal-layoutuser-memberstatus-nodesc'));

class Wassup_URE_AAL_Template_Processor_MemberStatusLayouts extends GD_URE_Template_Processor_MemberStatusLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_AAL_TEMPLATE_LAYOUTUSER_MEMBERSTATUS,
		);
	}

	function get_description($template_id, $atts) {

		switch ($template_id) {

			case GD_URE_AAL_TEMPLATE_LAYOUTUSER_MEMBERSTATUS:
				
				return sprintf(
					'<em>%s</em>',
					__('Status:', 'poptheme-wassup')
				);
		}
	
		return parent::get_description($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_URE_AAL_Template_Processor_MemberStatusLayouts();