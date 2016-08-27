<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_UpdateUserBlocksBase extends GD_Template_Processor_CreateUpdateUserBlocksBase {

	function get_dataloader($template_id) {
	
		return GD_DATALOADER_USERSINGLEEDIT;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		
		$this->add_jsmethod($ret, 'destroyPageOnUserLoggedOut');
		$this->add_jsmethod($ret, 'refetchBlockOnUserLoggedIn');

		return $ret;
	}

	// protected function get_actionexecuter($template_id) {
			
	// 	// Copy the FileUpload Picture to the server
	// 	return GD_DATALOAD_ACTIONEXECUTER_FILEUPLOADPICTURE;
	// }
}