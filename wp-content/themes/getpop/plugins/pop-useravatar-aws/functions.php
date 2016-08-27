<?php

// Override as to provide the credentials
add_filter('GD_FileUpload_UserPhoto_AWS:action_url', 'getpop_fileupload_userphoto_aws_actionurl', 100, 2);
function getpop_fileupload_userphoto_aws_actionurl($url, $upload_path) {

	// This theme can serve 2 websites: GetPoP, and GetPoP Demo. Check if it is one or the other through its version definitions
	if (GetPoP_Utils::is_demo()) {

		// GetPoP Demo website
		return GETPOP_URI_PLUGINS.'/pop-useravatar-aws/library/fileupload-userphoto/server-demo/index.php?upload_path='.$upload_path;
	}

	// GetPoP website
	return GETPOP_URI_PLUGINS.'/pop-useravatar-aws/library/fileupload-userphoto/server/index.php?upload_path='.$upload_path;
}