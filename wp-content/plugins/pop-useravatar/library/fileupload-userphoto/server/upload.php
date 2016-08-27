<?php
/*
 * jQuery File Upload Plugin PHP Example 5.7
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

class Upload {

	// function get_avatar_sizes() {
		
	// 	// These are all the sizes needed in the website! (Eg: GD_AVATAR_SIZE_16, etc)
	// 	// They are all defined on filter "gd_avatar_thumb_sizes"
	// 	return array(16, 24, 26, 32, 40, 50, 60, 64, 82, 100, 120, 150);
	// }
	function get_vars() {

		// Recreate the path to /uploads/fileupload-userphoto/
		$upload_path = isset($_REQUEST['upload_path']) ? $_REQUEST['upload_path'] : "generic";

		$upload_dir = $upload_path.DIRECTORY_SEPARATOR;
		$upload_originaldir = $upload_dir.'original'.DIRECTORY_SEPARATOR;
		$upload_thumbdir = $upload_dir.'thumb'.DIRECTORY_SEPARATOR;
		$upload_photodir = $upload_dir.'photo'.DIRECTORY_SEPARATOR;
		$upload_avatarsdir = $upload_dir.'avatars'.DIRECTORY_SEPARATOR;

		$upload_base = DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'uploads/fileupload-userphoto/';

		$user_upload_avatar_path = $upload_base . $upload_dir;
		$user_upload_avatar_full_path = dirname($_SERVER['SCRIPT_FILENAME']) . $user_upload_avatar_path;

		$user_upload_original_path = $upload_base . $upload_originaldir;
		$user_upload_original_fullpath = dirname($_SERVER['SCRIPT_FILENAME']) . $user_upload_original_path;

		$user_upload_thumb_path = $upload_base . $upload_thumbdir;
		$user_upload_thumb_fullpath = dirname($_SERVER['SCRIPT_FILENAME']) . $user_upload_thumb_path;

		$user_upload_photo_path = $upload_base . $upload_photodir;
		$user_upload_photo_fullpath = dirname($_SERVER['SCRIPT_FILENAME']) . $user_upload_photo_path;

		$user_upload_avatars_path = $upload_base . $upload_avatarsdir;
		$user_upload_avatars_fullpath = dirname($_SERVER['SCRIPT_FILENAME']) . $user_upload_avatars_path;

		return array(
			'thumb_size' => 150,
			'photo_maxwidth' => 600,
			'photo_maxheight' => 600,
			// These are all the sizes needed in the website! (Eg: GD_AVATAR_SIZE_16, etc)
			// They are all defined on filter "gd_avatar_thumb_sizes"
			// 'avatar_sizes' => array(16, 24, 26, 32, 40, 50, 60, 64, 82, 100, 120, 150),
			'avatar_sizes' => pop_fileupload_userphoto_avatarsizes(),

			'upload_path' => $upload_path, 
			'upload_dir' => $upload_dir, 
			'upload_originaldir' => $upload_originaldir, 
			'upload_thumbdir' => $upload_thumbdir, 
			'upload_photodir' => $upload_photodir, 
			'upload_avatarsdir' => $upload_avatarsdir, 
			'user_upload_original_path' => $user_upload_original_path, 
			'user_upload_thumb_path' => $user_upload_thumb_path, 
			'user_upload_photo_path' => $user_upload_photo_path, 
			'user_upload_avatars_path' => $user_upload_avatars_path, 
			'user_upload_thumb_fullpath' => $user_upload_thumb_fullpath, 
			'user_upload_photo_fullpath' => $user_upload_photo_fullpath, 
			'user_upload_original_fullpath' => $user_upload_original_fullpath, 
			'user_upload_avatars_fullpath' => $user_upload_avatars_fullpath,
		);
	}

	function __construct() {

		$vars = $this->get_vars();

		// Make sure all these folders exists, create them if not
        if (!file_exists($vars['user_upload_thumb_fullpath'])) {
            @mkdir($vars['user_upload_thumb_fullpath'], 0777 ,true);
        }
        if (!file_exists($vars['user_upload_photo_fullpath'])) {
            @mkdir($vars['user_upload_photo_fullpath'], 0777 ,true);
        }
        if (!file_exists($vars['user_upload_original_fullpath'])) {
            @mkdir($vars['user_upload_original_fullpath'], 0777 ,true);
        }
        if (!file_exists($vars['user_upload_avatars_fullpath'])) {
            @mkdir($vars['user_upload_avatars_fullpath'], 0777 ,true);
        }

		// Initialize the handler
		$this->init_uploadhandler();
	}

	function init_uploadhandler() {

		$vars = $this->get_vars();

		return new UploadHandler($vars);
	}
}

