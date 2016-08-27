<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Functions dealing with Avatars
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// // Regenerate all thumb files
// function gd_avatar_regenerate_all() {

// 	// Read all folders under the User Avatar folder
// 	$allfolders = scandir(USER_AVATAR_UPLOAD_PATH);
	
// 	// Skip . and .. folders
// 	array_shift($allfolders);
// 	array_shift($allfolders);	
// 	foreach ($allfolders as $folder) {
	
// 		// Filter all folders
// 		if (is_dir(USER_AVATAR_UPLOAD_PATH.$folder)) $folders[] = USER_AVATAR_UPLOAD_PATH.$folder;
// 	}	

// 	foreach ($folders as $folder) {
	
// 		$cropped_file = null;
// 		$original_file = null;
// 		$dest_filepath = null;
		
// 		// Stash files in an array once to check for one that matches
// 		$avatar_files = array();
// 		$av_dir = opendir( $folder );
// 		while ( false !== ( $avatar_file = readdir($av_dir) ) ) {
// 			// Only add files to the array (skip directories)
// 			if ( 2 < strlen( $avatar_file ) )
// 				$avatar_files[] = $avatar_file;
// 		}
// 		closedir($av_dir);
		
// 		foreach( $avatar_files as $key => $value ) {
// 			if (strpos($value, '-bpfull')) {
// 				$cropped_file = $folder . '/' . $value;
// 				$dest_filepath = substr($cropped_file, 0, strpos($cropped_file, '-bpfull'));
// 			}
// 			// elseif (strpos($value, '-' . GD_AVATAR_SIZE_ORIGINAL)) $original_file = $folder . '/' .$value;
// 			elseif (strpos($value, '-' . 'bporiginal')) $original_file = $folder . '/' .$value;
// 		}				
		
// 		if ($original_file) {
		
// 			gd_avatar_save_extra($original_file, $dest_filepath, $cropped_file, true);
// 		}
// 	}

// }

// // add_action('init', 'gd_admin_regenerate_avatars');
// function gd_admin_regenerate_avatars() {

// 	if ( isset($_REQUEST['gd-regen-avatars']) ) {
// 		// check_admin_referer('arras-admin');
		
// 		gd_avatar_regenerate_all();			
// 	}
// }

function gd_useravatar_migratestructure() {

	$olduploadpath = ABSPATH.UPLOADS."/avatars/";

	// Read all folders under the User Avatar folder: those are the user ids from users with their avatar set
	$userids = scandir($olduploadpath);
	array_shift($userids); // Skip . and .. folders
	array_shift($userids);

	// For each user, get their original photo
	foreach ($userids as $userid) {

		// The original file is the only file in the folder "original-files"
		$userfolder = $olduploadpath.$userid.'/original-files/';
		$originalfiles = scandir($userfolder);
		array_shift($originalfiles);  // Skip . and .. folders
		array_shift($originalfiles);
		if ($file = $originalfiles[0]) {

			$original_file = $userfolder.$file;

			// Migrate the userphoto
			gd_user_avatar_add_photo($userid, $original_file);
		}	
	}

}

add_action('admin_init', 'gd_admin_useravatars_migratestructure');
function gd_admin_useravatars_migratestructure() {

	if ( isset($_REQUEST['gd-useravatars-migratestructure']) ) {
		
		gd_useravatar_migratestructure();			
	}
}