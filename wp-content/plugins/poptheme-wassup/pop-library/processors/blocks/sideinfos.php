<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_EMPTYSIDEINFO', PoP_ServerUtils::get_template_definition('block-emptysideinfo'));
define ('GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_WEBPOST_CREATE', PoP_ServerUtils::get_template_definition('block-emptysideinfo-webpost-create'));
define ('GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_WEBPOSTLINK_CREATE', PoP_ServerUtils::get_template_definition('block-emptysideinfo-webpostlink-create'));

class GD_Template_Processor_CustomSideInfoBlocks extends GD_Template_Processor_CustomSideInfoBlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_EMPTYSIDEINFO,
			GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_WEBPOST_CREATE,
			GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_WEBPOSTLINK_CREATE,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomSideInfoBlocks();