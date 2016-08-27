<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_FARM_CREATE', PoP_ServerUtils::get_template_definition('block-emptysideinfo-farm-create'));
define ('GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_FARMLINK_CREATE', PoP_ServerUtils::get_template_definition('block-emptysideinfo-farmlink-create'));

class OP_Template_Processor_CustomSideInfoBlocks extends GD_Template_Processor_CustomSideInfoBlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_FARM_CREATE,
			GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_FARMLINK_CREATE,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_CustomSideInfoBlocks();