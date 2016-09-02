<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Functions dealing with Avatars
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// define('GD_AVATAR_SIZE_ORIGINAL', 'bporiginal');


// Redefine the path where pics are uploaded under uploads/custom_impl_folder/avatars
// Priority 5: before "user_avatar_core_set_avatar_constants"
add_action('init', 'gd_useravatar_init', 5);
function gd_useravatar_init() {
	define( 'USER_AVATAR_UPLOAD_PATH', gd_get_avatar_upload_path());
	define( 'USER_AVATAR_URL', gd_get_avatar_upload_url());
}

function gd_get_avatar_upload_path() { 

	// $avatar_folder = ABSPATH.UPLOADS."/avatars/";
	$avatar_folder = ABSPATH.UPLOADS."/userphoto/";

	if( !file_exists($avatar_folder) ) {
		@mkdir($avatar_folder, 0777 ,true);
	}
	
	return $avatar_folder;
}
function gd_get_avatar_upload_url() { 
	
	$upload_dir = wp_upload_dir(); 
	// return $upload_dir['baseurl'] . '/avatars/'; 
	return $upload_dir['baseurl'] . '/userphoto/'; 
}

// The users cannot see the user avatar form upload from the plugin, so it is removed
if (!is_admin()) {

	remove_action('edit_user_profile', 'user_avatar_form');
}


add_filter('gd_useravatar_avatar_sizes', 'pop_useravatar_avatar_sizes');
function pop_useravatar_avatar_sizes($sizes) {

	return array_merge(
		$sizes,
		GD_Avatars_Manager_Factory::get_instance()->get_sizes()
	);
}


/**---------------------------------------------------------------------------------------------------------------
 * Allow to retrieve Original Size "Avatars" (these original ones need not be squared)
 * ---------------------------------------------------------------------------------------------------------------*/

// Comment Leo 02/05/2016: Commented since changing the structure for compatibility among AWS and non-AWS websites
// function gd_user_avatar_original_file( $avatar, $user_id, $new_width) {
    	
// 	return gd_avatar_replace_and_extract_url($avatar, $user_id, $new_width, '-'.GD_AVATAR_SIZE_ORIGINAL, false, true);
// }
function gd_get_useravatar_photoinfo($user_id, $use_default = true) {

	// Change PoP: If the function has a filter, then execute this one instead
	// This way we allow the AWS logic to take over
	if (has_filter('gd_get_useravatar_photoinfo:override')) {
		return apply_filters('gd_get_useravatar_photoinfo:override', array(), $user_id);
	}

	// If the user has no avatar/photo, use the default one
	$avatar_data = user_avatar_avatar_exists($user_id, array('source' => 'photo'));
	if (!$avatar_data && $use_default) {

		$avatar_data = user_avatar_avatar_exists(POP_WPAPI_AVATAR_GENERICUSER, array('source' => 'photo'));
	}

	if ($avatar_data) {

		$size = @getimagesize($avatar_data['file']);
		return array(
			'src' => $avatar_data['url'],
			'width' => $size[0],
			'height' => $size[1],
		);
	}
	
	return array();
}

// function gd_user_avatar_metadata($id, $type = "-bpfull"){
	
// 	$avatar_folder_dir = USER_AVATAR_UPLOAD_PATH."{$id}/";
// 	$return = false;

// 	if ( is_dir( $avatar_folder_dir ) && $av_dir = opendir( $avatar_folder_dir ) ) {
			
// 		// Stash files in an array once to check for one that matches
// 		$avatar_files = array();
// 		while ( false !== ( $avatar_file = readdir($av_dir) ) ) {
// 			// Only add files to the array (skip directories)
// 			if ( 2 < strlen( $avatar_file ) )
// 				$avatar_files[] = $avatar_file;
// 		}
		
// 		// Check for array
// 		if ( 0 < count( $avatar_files ) ) {
// 			// Check for current avatar
// 			if( is_array($avatar_files) ):
// 				foreach( $avatar_files as $key => $value ) {
// 					if(strpos($value, $type)):
// 						// $return =  $value;
// 						$size = getimagesize($avatar_folder_dir.$value);
// 						return array(
// 							'w' => $size[0],
// 							'h' => $size[1],
// 						);
// 					endif;
// 				}
// 			endif;
			
// 		}

// 		// Close the avatar directory
// 		closedir( $av_dir );
// 	}
	
// 	return false;
// }

// function gd_avatar_replace_and_extract_url( $avatar, $user_id, $new_size, $new_type_or_size = null, $fix_size = false, $add_metadata = false) {
    	
// //	$imgsrc = getHtmlAttribute($avatar, 'img', 'src');
// 	if (!$avatar) {
// 		return array('avatar' => '', 'src' => '');
// 	}

// 	if (is_null($new_type_or_size)) {
// 		$new_type_or_size = $new_size;
// 	}
// 	// Check the avatar comes from our website (could be Gravatar or FB / Google / etc)
// 	if (strpos($avatar, get_site_url()) === false) {

// 		// Make sure to resize it to the size needed
// 		$avatar = str_replace('<img ', '<img style="max-width: '.$new_size.'px;" ', $avatar);
// 		return array('avatar' => $avatar /*, 'src' => $imgsrc */);		
// 	}

// 	// Remove the user-avatar-pic.php and the params, but only if it is there (not with the profile picture)
// 	$imgsrc = getHtmlAttribute($avatar, 'img', 'src');
// 	if (!$imgsrc) {
// 		return array('avatar' => '', 'src' => '');
// 	}
// 	// And only if the size is one of the pre-generated ones
// 	// if (in_array($new_type_or_size, gd_get_avatar_sizes()) && strpos($imgsrc, 'user-avatar-pic.php') !== false) {
// 	if (strpos($imgsrc, 'user-avatar-pic.php') !== false) {
// 		$imgsrc = substr($imgsrc, strpos($imgsrc, 'src=') + 4);
// 		$imgsrc = substr($imgsrc, 0, strpos($imgsrc, '&'));
// 	}
// // Get the current size of the image: the string in the filename, between "-" and ".jpg"
// 	$current_type_or_size = substr($imgsrc, strrpos($imgsrc, '-'));
// 	$current_type_or_size = substr($current_type_or_size, 0, strrpos($current_type_or_size, '.'));

// 	$imgsrc = str_replace($current_type_or_size, $new_type_or_size, $imgsrc);

// 	if ($fix_size) {
// 		$avatar = '<img src="'.$imgsrc.'" width="'.$new_size.'" height="'.$new_size.'">';
// 	}
// 	else {
// 		$avatar = '<img src="'.$imgsrc.'" style="max-width: '.$new_size.'px;">';
// 	}
// 	$ret = array('avatar' => $avatar, 'src' => $imgsrc);

// 	// Width/Height: needed for PhotoSwipe, needed only for the avatar-original
// 	if ($add_metadata) {

// 		$image_size = gd_user_avatar_metadata($user_id, $new_type_or_size);
// 		// If the metadata was not found then the avatar does not exist => get the metadata of the default one
// 		if (!$image_size) {
// 			$image_size = gd_user_avatar_metadata(POP_WPAPI_AVATAR_GENERICUSER, $new_type_or_size);			
// 		}
// 		if ($image_size) {
// 			$ret['width'] = $image_size['w'];
// 			$ret['height'] = $image_size['h'];
// 		}
// 	}

// 	return $ret;
// }

function gd_avatar_extract_url( $avatar ) {
    	
	// Remove the user-avatar-pic.php and the params, but only if it is there (not with the profile picture)
	$imgsrc = getHtmlAttribute($avatar, 'img', 'src');
	if (strpos($imgsrc, 'user-avatar-pic.php') !== false) {
		$imgsrc = substr($imgsrc, strpos($imgsrc, 'src=') + 4);
		$imgsrc = substr($imgsrc, 0, strpos($imgsrc, '&'));
	}

	return $imgsrc;
}

// Priority 100: execute last
// add_filter( 'get_avatar', 'gd_get_avatar', 100, 5 );
// function gd_get_avatar( $avatar, $user, $size, $default, $alt ) {
    	
// 	// If passed an object, assume $user->user_id
// 	if ( is_object( $user ) )
// 		$id = $user->user_id;

// 	// If passed a number, assume it was a $user_id
// 	else if ( is_numeric( $user ) )
// 		$id = $user;

// 	// If passed a string and that string returns a user, get the $id
// 	else if ( is_string( $user ) && ( $user_by_email = get_user_by_email( $user ) ) )
// 		$id = $user_by_email->ID;

// 	// If somehow $id hasn't been assigned, return the result of get_avatar
// 	if ( empty( $id ) )
// 		return !empty( $avatar ) ? $avatar : $default;
		
// 	$avatar = gd_avatar_replace_and_extract_url($avatar, $id, $size, '-'.$size, true);
// 	return $avatar['avatar'];
// }


/**---------------------------------------------------------------------------------------------------------------
 * Return the default avatar
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_avatar_default', 'gd_avatar_default', 1, 5);
function gd_avatar_default ($avatar, $id_or_email, $size, $default, $alt) {

	// Return the avatar for the default avatar user
	$avatar = user_avatar_fetch_avatar( array( 'item_id' => POP_WPAPI_AVATAR_GENERICUSER, 'width' => $size, 'height' => $size, 'alt' => $alt ) );

	return $avatar;
}



/**---------------------------------------------------------------------------------------------------------------
 * Save extra size avatars
 * ---------------------------------------------------------------------------------------------------------------*/
// Comment Leo 02/05/2016: commented since generating a compatible structure for both AWS and non-AWS websites
// // Save the original image
// // In User Avatar plugin
// add_action('gd_user_avatar', 'gd_avatar_save_original', 10, 2);
// function gd_avatar_save_original($original_file, $dest_filepath) {
	
// 	// Addition: also copy the original image
// 	$dest = $dest_filepath . '-' . GD_AVATAR_SIZE_ORIGINAL . ".jpg";
// 	copy($original_file, $dest);
// }

// Comment Leo 02/05/2016: commented since generating a compatible structure for both AWS and non-AWS websites
// add_action('gd_user_avatar', 'gd_avatar_save_original_for_fileupload', 10, 5);
// function gd_avatar_save_original_for_fileupload($original_file, $dest_filepath, $cropped_full, $folderpath, $filename) {
	
// 	// Also copy the original files, so they can be copied again when editing the Profile
// 	$dest_original = "{$folderpath}/original-files/";
// 	$dest_thumb = "{$folderpath}/original-thumb/";
	
// 	$files = array();
// 	if(!file_exists($dest_original)) {					
// 		mkdir($dest_original);
// 	}
// 	else {
// 		$files = glob("{$dest_original}*");
// 	}
// 	if(!file_exists($dest_thumb)) {					
// 		mkdir($dest_thumb);
// 	}
// 	else {
// 		$files = array_merge(
// 			$files,
// 			glob("{$dest_thumb}*")
// 		);
// 	}

// 	// Make sure to delete previous existing original files
// 	foreach($files as $file) {
// 		if (is_file($file)) {
// 			unlink($file);
// 		}
// 	}

// 	copy($original_file, "{$dest_original}{$filename}.jpg");
// 	copy($cropped_full, "{$dest_thumb}{$filename}.jpg");

// }

// Also generate the thumb in different sizes, to be re-used without timthumb, so they can be cached by the browser
// add_action('gd_fileupload-userphoto', 'gd_avatar_save_extra', 10, 3);
// add_action('gd_user_avatar', 'gd_avatar_save_extra', 10, 3);
// function gd_avatar_save_extra($original_file, $dest_filepath, $cropped_file = null, $check_exists = false) {
	
// 	// Crop them to make them square
// 	/** WordPress Image Administration API */
// 	require_once(ABSPATH . 'wp-admin/includes/image.php');
	
// 	// /** WordPress Media Administration API */
// 	require_once(ABSPATH . 'wp-admin/includes/media.php');

// 	$filename = basename($original_file);
// 	$dest_filepath = $dest_filepath.'avatars/';
// 	mkdir($dest_filepath);
// 	// if(!file_exists($dest_filepath)) {

// 	// 	mkdir($dest_filepath);
// 	// }

// 	// Use either the original file or the cropped one, depending on if this one exists (it is provided by User Avatar)
// 	$src_file = $cropped_file ? $cropped_file : $original_file;

// 	// Center squared crop area
// 	$file_size = getimagesize($src_file);
// 	$file_size_min = ($file_size[0] < $file_size[1] ? $file_size[0] : $file_size[1]);
// 	$file_x1 = ($file_size[0] - $file_size_min) / 2;
// 	$file_y1 = ($file_size[1] - $file_size_min) / 2;
	
// 	$thumbsizes = GD_Avatars_Manager_Factory::get_instance()->get_sizes();
// 	foreach ($thumbsizes as $thumbsize) {
	
// 		// $cropped_thumbsize = $dest_filepath.'-'.$thumbsize.".jpg";
// 		$avatarpath = $dest_filepath.$thumbsize.'/';
// 		if(!file_exists($avatarpath)) {

// 			mkdir($avatarpath);
// 		}
// 		$cropped_thumbsize = $avatarpath.$filename;
		
// 		if ($check_exists) {
		
// 			if(file_exists($cropped_thumbsize)) continue;
// 		}
		
// 		// Calculate coordinates to crop
// 		$cropped_thumbsize = wp_crop_image( $src_file, $file_x1, $file_y1, $file_size_min, $file_size_min, $thumbsize, $thumbsize, false, $cropped_thumbsize );
// 	}
// }
