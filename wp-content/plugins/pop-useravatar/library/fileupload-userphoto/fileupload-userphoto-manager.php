<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Avatars
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FileUpload_UserPhoto_Manager {

	// Instance is unique.
	var $instance;

	function add($instance) {

		// // If there has already been added an instance, then compare their prorities
		// // This way we can add the AWS instance to override the normal one
		// if ($this->instance) {

		// 	if ($instance->get_priority() > $this->instance->get_priority()) {

		// 		$this->instance = $instance;				
		// 	}
		// }
		// else {

		// 	$this->instance = $instance;
		// }
		$this->instance = $instance;
	}

	function get_instance() {

		return $this->instance;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_fileupload_userphoto_manager;
$gd_fileupload_userphoto_manager = new GD_FileUpload_UserPhoto_Manager();

