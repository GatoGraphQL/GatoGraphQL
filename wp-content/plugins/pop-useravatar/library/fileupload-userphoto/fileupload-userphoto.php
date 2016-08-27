<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * File Upload Avatar
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FileUpload_UserPhoto {

	// $upload_path: Path where to upload the Avatar under the server/uploads/files folder
	var $upload_path;

	function __construct() {	

		GD_FileUpload_UserPhoto_Manager_Factory::get_instance()->add($this);
	
		// New one, create random
		$this->upload_path = "_" . time() . "_" . rand();
	}

	function get_upload_path() {

		return $this->upload_path;
	}

	function set_upload_path($upload_path) {

		$this->upload_path = $upload_path;
	}

	
	/**---------------------------------------------------------------------------------------------------------------
	 * Copy the thumb files to a folder by User Avatar plugin, this plugin will use it later on
	 * ---------------------------------------------------------------------------------------------------------------*/	
	// Comment Leo 02/05/2016: Commented since changing the structure for compatibility among AWS and non-AWS websites
	// function save_picture($user_id) {
			
	// 	// The images are NOT loaded under /uploads/mesym/fileupload-userphoto, but under /uploads/fileupload-userphoto/
	// 	// So recreate the path
	// 	$source_files_path_base = WP_CONTENT_DIR.'/uploads/fileupload-userphoto/';
		
	// 	$source_files_path = $source_files_path_base . $this->upload_path . '/original/';//'-files/';
	// 	$source_thumb_path = $source_files_path_base . $this->upload_path . '/thumb/';//'-thumb/';
		
	// 	$delete_existing = false;

	// 	// Check if the folder exists (if the user ever uploaded a file). If not, no images
	// 	if (file_exists($source_files_path)) {

	// 		// Check if there was a file uploaded or not		
	// 		$array_files = array_diff(scandir($source_files_path, 1), array('..', '.'));
	// 		if (!empty($array_files)) {

	// 			$filename = $array_files[0];
	// 			$ext = substr(($t=strrchr($filename,'.'))!==false?$t:'',0);
				
	// 			$source_file = $source_files_path . $filename;
	// 			$source_thumb = $source_thumb_path . $filename;
				
	// 			// It works together with plugin User Avatar, so the file is created the same way
	// 			$time = time();
	// 			$filepath = USER_AVATAR_UPLOAD_PATH."{$user_id}/{$user_id}_{$time}";
	// 			$dest_photo = $filepath."-bpfull.jpg";
	// 			$dest_thumb = $filepath."-bpthumb.jpg";
	// 			$dest_original = $filepath . '-' . GD_AVATAR_SIZE_ORIGINAL . ".jpg";

	// 			// delete the previous files
	// 			user_avatar_delete_files($user_id);
		
	// 			if(!file_exists(USER_AVATAR_UPLOAD_PATH."{$user_id}/")) {
					
	// 				mkdir(USER_AVATAR_UPLOAD_PATH."{$user_id}/");
	// 			}

	// 			// Also copy the original files, so they can be copied again when editing the Profile
	// 			$files = array();
	// 			if(!file_exists(USER_AVATAR_UPLOAD_PATH."{$user_id}/original-files/")) {					
	// 				mkdir(USER_AVATAR_UPLOAD_PATH."{$user_id}/original-files/");
	// 			}
	// 			else {
	// 				$files = glob(USER_AVATAR_UPLOAD_PATH."{$user_id}/original-files/*");
	// 			}
	// 			if(!file_exists(USER_AVATAR_UPLOAD_PATH."{$user_id}/original-thumb/")) {					
	// 				mkdir(USER_AVATAR_UPLOAD_PATH."{$user_id}/original-thumb/");
	// 			}
	// 			else {
	// 				$files = array_merge(
	// 					$files,
	// 					glob(USER_AVATAR_UPLOAD_PATH."{$user_id}/original-thumb/*")
	// 				);
	// 			}

	// 			// Make sure to delete previous existing original files
	// 			foreach($files as $file) {
	// 				if (is_file($file)) {
	// 					unlink($file);
	// 				}
	// 			}

	// 			$dest_source_file = USER_AVATAR_UPLOAD_PATH."{$user_id}/original-files/".$filename;
	// 			$dest_source_thumb = USER_AVATAR_UPLOAD_PATH."{$user_id}/original-thumb/".$filename;
				
	// 			// Keep originals, override existing files
	// 			copy($source_file, $dest_source_file);
	// 			copy($source_thumb, $dest_source_thumb);

	// 			// Copy photo and thumb
	// 			copy($source_file, $dest_photo);
	// 			copy($source_thumb, $dest_thumb);

	// 			// Copy the original file with extension "-bporiginal"
	// 			copy($source_files_path.$filename, $dest_original);
				
	// 			do_action('gd_fileupload-userphoto', $source_files_path.$filename, $filepath);
				
	// 			// Comment Leo 14/03/2014: since using popManager using AJAX, do not delete the files anymore
	// 			// Unlink the files
	// 			unlink($source_files_path . $filename);
	// 			unlink($source_thumb_path . $filename);			
	// 		}
	// 		else {
	// 			$delete_existing = true;
	// 		}
			
	// 		// Comment Leo 14/03/2014: since using popManager using AJAX, do not delete the files anymore
	// 		// Delete the folders
	// 		rmdir($source_files_path);
	// 		rmdir($source_thumb_path);
	// 	}
	// 	else {
	// 		$delete_existing = true;
	// 	}	

	// 	// Empty => No avatars selected => Delete existing avatars
	// 	if ($delete_existing) {

	// 		$this->unlink_files(USER_AVATAR_UPLOAD_PATH."{$user_id}/*");
	// 		$this->unlink_files(USER_AVATAR_UPLOAD_PATH."{$user_id}/original-files/*");
	// 		$this->unlink_files(USER_AVATAR_UPLOAD_PATH."{$user_id}/original-thumb/*");
	// 	}
	// }
	function save_picture($user_id, $delete_source = false) {
			
		// The images are NOT loaded under /uploads/mesym/fileupload-userphoto, but under /uploads/fileupload-userphoto/
		// So recreate the path
		$source_files_path_base = WP_CONTENT_DIR.'/uploads/fileupload-userphoto/';

		// delete the previous files
		user_avatar_delete_files($user_id);

		// Simply copy the uploaded pictures to the corresponding folder		
		$source_files_path = $source_files_path_base.$this->upload_path.DIRECTORY_SEPARATOR;
		$destination_folder = USER_AVATAR_UPLOAD_PATH."{$user_id}/";
		recurse_copy($source_files_path, $destination_folder);

		// Retrieve the filename of the picture: it is the only file in the folder
		$original_folder = $source_files_path.'original/';
		$original_filename = '';
		$allfiles = scandir($original_folder);
		array_shift($allfiles); // Skip . and .. folders
		array_shift($allfiles);	
		if ($filename = $allfiles[0]) {
			$original_filename = basename($filename);
		}

		// Delete source: needed to delete the images when first creating a user, since the created upload_path folder
        // is something like _43204930_432049320 and won't be used again
        // (In addition, there's a bug: since different users share the same upload_path, for it being saved in the settings cache,
        // then a 2nd user will see a 1st user's pic set by default when registering)
        if ($delete_source) {
			delTree($source_files_path);
		}

		// Save the filename in the user meta
        if ($original_filename) {
            gd_useravatar_save_filename($user_id, $original_filename);
        }
        else {
            // No filename => No avatar uploaded, delete the existing one
            gd_useravatar_delete_filename($user_id);
        }

		// Comment Leo 06/02/2016: do the cropping in UploadHandler.php instead, to keep it consistent
		// between AWS and non-AWS versions
		
		// // Allow to generate all needed avatars
		// $original_file = $source_files_path.'original/'.$original_filename;
		// $thumb_file = $source_files_path.'thumb/'.$original_filename;
		// gd_avatar_save_extra($original_file, $destination_folder, $thumb_file);
		// do_action('gd_fileupload-userphoto', $original_file, $destination_folder, $thumb_file);
	}

	// function unlink_files($files_path) {
					
	// 	if ($files = glob($files_path)) {

	// 		foreach($files as $file) {

	// 			if (is_file($file)) {

	// 				unlink($file);
	// 			}
	// 		}
	// 	}
	// }

	/**---------------------------------------------------------------------------------------------------------------
	 * Copy the Avatar to the thumb files folder
	 * ---------------------------------------------------------------------------------------------------------------*/	
	// Comment Leo 02/05/2016: Commented since changing the structure for compatibility among AWS and non-AWS websites
	// function copy_picture($user_id) {

	// 	// Copy the images from the user avatar folder into the fileupload-userphoto folder in uploads
			
	// 	// The images are NOT loaded under /uploads/mesym/fileupload-userphoto, but under /uploads/fileupload-userphoto/
	// 	// So recreate the path
	// 	$source_files_path_base = WP_CONTENT_DIR.'/uploads/fileupload-userphoto/';
		
	// 	$source_files_path = $source_files_path_base . $this->upload_path . '-files/';
	// 	$source_thumb_path = $source_files_path_base . $this->upload_path . '-thumb/';
		
	// 	// Check if the folder exists, if not create it / Delete existing uploaded (and never saved) images
	// 	$files = array();
	// 	if (!file_exists($source_files_path)) {
	// 		mkdir($source_files_path);
	// 	}
	// 	else {
	// 		$files = glob($source_files_path."*");
	// 	}
	// 	if (!file_exists($source_thumb_path)) {
	// 		mkdir($source_thumb_path);
	// 	}
	// 	else {
	// 		$files = array_merge(
	// 			$files, 
	// 			glob($source_thumb_path."*")
	// 		);
	// 	}
	// 	foreach($files as $file) {
	// 		if (is_file($file)) {
	// 			unlink($file);
	// 		}
	// 	}

	// 	$original_file_folder = USER_AVATAR_UPLOAD_PATH."{$user_id}/original-files/";
	// 	$original_thumb_folder = USER_AVATAR_UPLOAD_PATH."{$user_id}/original-thumb/";

	// 	if (file_exists($original_file_folder)) {

	// 		// Check if there was a file uploaded or not		
	// 		$array_files = array_diff(scandir($original_file_folder, 1), array('..', '.'));
	// 		if (!empty($array_files)) {

	// 			$filename = $array_files[0];
	// 			$ext = substr(($t=strrchr($filename,'.'))!==false?$t:'',0);
				
	// 			$original_file = $original_file_folder . $filename;
	// 			$original_thumb = $original_thumb_folder . $filename;
				
	// 			// Copy the original files to the fileupload-userphoto sources
	// 			copy($original_file, $source_files_path.$filename);
	// 			copy($original_thumb, $source_thumb_path.$filename);			
	// 		}
	// 	}
	// }
	function copy_picture($user_id) {

		// Copy the images from the user avatar folder into the fileupload-userphoto folder in uploads
			
		// The images are NOT loaded under /uploads/mesym/fileupload-userphoto, but under /uploads/fileupload-userphoto/
		// So recreate the path
		$working_dir = WP_CONTENT_DIR.'/uploads/fileupload-userphoto/'.$this->upload_path.'/';
		delTree($working_dir);
		
		$original_file_folder = USER_AVATAR_UPLOAD_PATH."{$user_id}/";
		recurse_copy($original_file_folder, $working_dir);
	}
	
	function get_action_url() {
		
		// Allow to replace this URL, mainly to change the avatar sizes as needed by the specific website
		return apply_filters('GD_FileUpload_UserPhoto:action_url', POP_USERAVATAR_URI_LIB.'/fileupload-userphoto/server/index.php?upload_path=' . $this->upload_path, $this->upload_path);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_FileUpload_UserPhoto();
