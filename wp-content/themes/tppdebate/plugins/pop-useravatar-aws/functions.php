<?php

// Override as to provide the credentials
add_filter('GD_FileUpload_UserPhoto_AWS:action_url', 'tppdebate_fileupload_userphoto_aws_actionurl', 100, 2);
function tppdebate_fileupload_userphoto_aws_actionurl($url, $upload_path) {

	// This theme can serve 2 websites: TPPDebate AR and MY
	if ($country = TPPDebate_Utils::get_country()) {

		// Country-specific environment
		return TPPDEBATE_URI_PLUGINS.'/pop-useravatar-aws/library/fileupload-userphoto/server-'.$country.'/index.php?upload_path='.$upload_path;
	}

	// No country defined => use 'tppdebate-environment', if it exists
	return TPPDEBATE_URI_PLUGINS.'/pop-useravatar-aws/library/fileupload-userphoto/server/index.php?upload_path='.$upload_path;
}