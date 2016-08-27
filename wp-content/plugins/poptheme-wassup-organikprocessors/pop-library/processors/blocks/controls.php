<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_PAGECONTROL_FARM_CREATE', PoP_ServerUtils::get_template_definition('block-pagecontrol-farm-create'));
define ('GD_TEMPLATE_BLOCK_PAGECONTROL_FARMLINK_CREATE', PoP_ServerUtils::get_template_definition('block-pagecontrol-farmlink-create'));

class OP_Custom_Template_Processor_CustomControlBlocks extends GD_Template_Processor_ControlBlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_PAGECONTROL_FARM_CREATE,
			GD_TEMPLATE_BLOCK_PAGECONTROL_FARMLINK_CREATE,
		);
	}

	protected function get_controlgroup_top($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_PAGECONTROL_FARM_CREATE:
			case GD_TEMPLATE_BLOCK_PAGECONTROL_FARMLINK_CREATE:

				return GD_TEMPLATE_CONTROLGROUP_PAGEOPTIONS;
		}

		return parent::get_controlgroup_top($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Custom_Template_Processor_CustomControlBlocks();