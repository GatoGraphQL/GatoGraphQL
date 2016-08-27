<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_URE_Template_Processor_UserCommunityLayoutsBase extends GD_Template_Processor_SubcomponentLayoutsBase {

	function get_subcomponent_field($template_id) {
	
		return 'active-communities';
	}

	function get_dataloader($template_id) {

		return GD_DATALOADER_SECONDUSERLIST;
	}
}