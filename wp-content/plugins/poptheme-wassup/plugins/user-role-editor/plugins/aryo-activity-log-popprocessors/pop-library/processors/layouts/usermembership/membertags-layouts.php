<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_AAL_TEMPLATE_LAYOUTUSER_MEMBERTAGS', PoP_ServerUtils::get_template_definition('ure-aal-layoutuser-membertags-desc'));

class Wassup_URE_AAL_Template_Processor_MemberTagsLayouts extends GD_URE_Template_Processor_MemberTagsLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_AAL_TEMPLATE_LAYOUTUSER_MEMBERTAGS,
		);
	}

	function get_description($template_id, $atts) {

		switch ($template_id) {

			case GD_URE_AAL_TEMPLATE_LAYOUTUSER_MEMBERTAGS:
				
				return sprintf(
					'<em>%s</em>',
					__('Tags:', 'poptheme-wassup')
				);
		}
	
		return parent::get_description($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_URE_AAL_Template_Processor_MemberTagsLayouts();