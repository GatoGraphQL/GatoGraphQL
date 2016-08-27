<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_PAGETABS_FARM_CREATE', PoP_ServerUtils::get_template_definition('block-pagetabs-farm-create'));
define ('GD_TEMPLATE_BLOCK_PAGETABS_FARMLINK_CREATE', PoP_ServerUtils::get_template_definition('block-pagetabs-farmlink-create'));

class OP_Template_Processor_CustomTabBlocks extends GD_Template_Processor_TabBlocksBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_PAGETABS_FARM_CREATE,
			GD_TEMPLATE_BLOCK_PAGETABS_FARMLINK_CREATE,
		);
	}

	function get_title($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_PAGETABS_FARM_CREATE:
			case GD_TEMPLATE_BLOCK_PAGETABS_FARMLINK_CREATE:

				$pages = array(
					GD_TEMPLATE_BLOCK_PAGETABS_FARM_CREATE => POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARM,
					GD_TEMPLATE_BLOCK_PAGETABS_FARMLINK_CREATE => POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARMLINK,
				);
				return get_the_title($pages[$template_id]);
		}
		
		return parent::get_title($template_id);
	}

	protected function get_iohandler($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_PAGETABS_FARM_CREATE:
			case GD_TEMPLATE_BLOCK_PAGETABS_FARMLINK_CREATE:

				$iohandlers = array(
					GD_TEMPLATE_BLOCK_PAGETABS_FARM_CREATE => GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDFARM,
					GD_TEMPLATE_BLOCK_PAGETABS_FARMLINK_CREATE => GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDFARMLINK,
				);
				if ($iohandler = $iohandlers[$template_id]) {
					return $iohandler;
				}
				break;
		}
		
		return parent::get_iohandler($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_CustomTabBlocks();