<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_PostUserMentionsLayoutsBase extends GD_Template_Processor_SubcomponentLayoutsBase {

	function get_subcomponent_field($template_id) {
	
		return 'taggedusers';
	}

	function get_dataloader($template_id) {

		return GD_DATALOADER_USERLIST;
	}	
}