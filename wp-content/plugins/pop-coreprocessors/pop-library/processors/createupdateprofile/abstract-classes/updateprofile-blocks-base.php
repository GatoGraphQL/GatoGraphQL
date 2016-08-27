<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_UpdateProfileBlocksBase extends GD_Template_Processor_UpdateUserBlocksBase {

	protected function get_messagefeedback($template_id) {
	
		return GD_TEMPLATE_MESSAGEFEEDBACK_UPDATEPROFILE;
	}
}
