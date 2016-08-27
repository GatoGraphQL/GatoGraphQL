<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUTTAG_MENTION_COMPONENT', PoP_ServerUtils::get_template_definition('layouttag-mention-component'));

class GD_Template_Processor_TagMentionComponentLayouts extends GD_Template_Processor_TagMentionComponentLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUTTAG_MENTION_COMPONENT,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_TagMentionComponentLayouts();