<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_EVENT_CREATE', PoP_ServerUtils::get_template_definition('block-emptysideinfo-event-create'));
define ('GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_EVENTLINK_CREATE', PoP_ServerUtils::get_template_definition('block-emptysideinfo-eventlink-create'));

class GD_EM_Template_Processor_CustomSideInfoBlocks extends GD_Template_Processor_CustomSideInfoBlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_EVENT_CREATE,
			GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_EVENTLINK_CREATE,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CustomSideInfoBlocks();