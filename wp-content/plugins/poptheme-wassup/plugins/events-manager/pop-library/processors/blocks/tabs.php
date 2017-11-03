<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_PAGETABS_EVENT_CREATE', PoP_TemplateIDUtils::get_template_definition('block-pagetabs-event-create'));
define ('GD_TEMPLATE_BLOCK_PAGETABS_EVENTLINK_CREATE', PoP_TemplateIDUtils::get_template_definition('block-pagetabs-eventlink-create'));

class GD_EM_Template_Processor_CustomTabBlocks extends GD_Template_Processor_TabBlocksBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_PAGETABS_EVENT_CREATE,
			GD_TEMPLATE_BLOCK_PAGETABS_EVENTLINK_CREATE,
		);
	}

	function get_title($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_PAGETABS_EVENT_CREATE:
			case GD_TEMPLATE_BLOCK_PAGETABS_EVENTLINK_CREATE:

				$pages = array(
					GD_TEMPLATE_BLOCK_PAGETABS_EVENT_CREATE => POPTHEME_WASSUP_EM_PAGE_ADDEVENT,
					GD_TEMPLATE_BLOCK_PAGETABS_EVENTLINK_CREATE => POPTHEME_WASSUP_EM_PAGE_ADDEVENTLINK,
				);
				return get_the_title($pages[$template_id]);
		}
		
		return parent::get_title($template_id);
	}

	protected function get_iohandler($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_PAGETABS_EVENT_CREATE:
			case GD_TEMPLATE_BLOCK_PAGETABS_EVENTLINK_CREATE:

				$iohandlers = array(
					GD_TEMPLATE_BLOCK_PAGETABS_EVENT_CREATE => GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDEVENT,
					GD_TEMPLATE_BLOCK_PAGETABS_EVENTLINK_CREATE => GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDEVENTLINK,
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
new GD_EM_Template_Processor_CustomTabBlocks();