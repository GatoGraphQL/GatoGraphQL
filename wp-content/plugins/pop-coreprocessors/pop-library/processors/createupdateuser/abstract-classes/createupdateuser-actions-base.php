<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// class GD_Template_Processor_CreateUpdateUserActionsBase extends GD_Template_Processor_BlocksBase {
class GD_Template_Processor_CreateUpdateUserActionsBase extends GD_Template_Processor_ActionsBase {

	protected function get_iohandler($template_id) {
	
		return GD_DATALOAD_IOHANDLER_FORM;
	}
}