<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUTUSER_MENTION_COMPONENT', PoP_TemplateIDUtils::get_template_definition('layoutuser-mention-component'));

class GD_Template_Processor_UserMentionComponentLayouts extends GD_Template_Processor_UserMentionComponentLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUTUSER_MENTION_COMPONENT,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_UserMentionComponentLayouts();