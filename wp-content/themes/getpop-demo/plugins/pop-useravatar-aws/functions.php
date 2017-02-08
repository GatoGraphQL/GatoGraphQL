<?php

// Override as to provide the credentials
add_filter('GD_FileUpload_UserPhoto_AWS:action_url', 'getpopdemo_fileupload_userphoto_aws_actionurl', 100, 2);
function getpopdemo_fileupload_userphoto_aws_actionurl($url, $upload_path) {

	// GetPoP Demo website
	return GETPOPDEMO_ORIGINURI_PLUGINS.'/pop-useravatar-aws/library/fileupload-userphoto/server/index.php?upload_path='.$upload_path;
}