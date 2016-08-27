<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CreateProfileBlocksBase extends GD_Template_Processor_CreateUserBlocksBase {

	protected function get_messagefeedback($template_id) {
	
		return GD_TEMPLATE_MESSAGEFEEDBACK_CREATEPROFILE;
	}
}
