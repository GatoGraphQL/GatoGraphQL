<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Avatars
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FileUpload_UserPhoto_Manager_Factory {

	public static function get_instance() {

		global $gd_fileupload_userphoto_manager;
		return $gd_fileupload_userphoto_manager;
	}
}
