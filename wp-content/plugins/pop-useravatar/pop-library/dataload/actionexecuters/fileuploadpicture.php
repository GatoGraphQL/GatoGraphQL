<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_FILEUPLOADPICTURE', 'fileuploadpicture');

class GD_DataLoad_ActionExecuter_FileUploadPicture extends GD_DataLoad_ActionExecuter {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_FILEUPLOADPICTURE;
	}

    function execute(&$block_data_settings, $block_atts, &$block_execution_bag) {

		// Copy the images to the fileupload-userphoto upload folder
		$gd_fileupload_userphoto = GD_FileUpload_UserPhoto_Manager_Factory::get_instance()->get_instance();
		$user_id = get_current_user_id();
		$gd_fileupload_userphoto->set_upload_path($user_id);
		$gd_fileupload_userphoto->copy_picture($user_id);

		return parent::execute($block_data_settings, $block_atts, $block_execution_bag);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_FileUploadPicture();