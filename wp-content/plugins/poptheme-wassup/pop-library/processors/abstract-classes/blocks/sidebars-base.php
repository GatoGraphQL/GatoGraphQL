<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CustomSidebarBlocksBase extends GD_Template_Processor_SidebarBlocksBase {

	protected function get_iohandler($template_id) {

		return GD_DATALOAD_IOHANDLER_FORM;
	}
}