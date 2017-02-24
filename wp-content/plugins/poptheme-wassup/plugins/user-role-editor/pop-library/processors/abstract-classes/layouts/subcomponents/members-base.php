<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_URE_Template_Processor_MembersLayoutsBase extends GD_Template_Processor_SubcomponentLayoutsBase {

	function get_subcomponent_field($template_id) {
	
		return 'members';
	}

	function get_dataloader($template_id) {

		return GD_DATALOADER_SECONDUSERLIST;
	}
}