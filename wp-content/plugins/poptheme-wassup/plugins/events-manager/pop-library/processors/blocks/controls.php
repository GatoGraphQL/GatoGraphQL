<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_PAGECONTROL_EVENT_CREATE', PoP_ServerUtils::get_template_definition('block-pagecontrol-event-create'));
define ('GD_TEMPLATE_BLOCK_PAGECONTROL_EVENTLINK_CREATE', PoP_ServerUtils::get_template_definition('block-pagecontrol-eventlink-create'));

class GD_EM_Template_Processor_CustomControlBlocks extends GD_Template_Processor_ControlBlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_PAGECONTROL_EVENT_CREATE,
			GD_TEMPLATE_BLOCK_PAGECONTROL_EVENTLINK_CREATE,
		);
	}

	protected function get_controlgroup_top($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_PAGECONTROL_EVENT_CREATE:
			case GD_TEMPLATE_BLOCK_PAGECONTROL_EVENTLINK_CREATE:

				return GD_TEMPLATE_CONTROLGROUP_PAGEOPTIONS;
		}

		return parent::get_controlgroup_top($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CustomControlBlocks();