<?php

add_filter('GD_FileUpload_UserPhoto_AWS:action_url', 'poptheme_fileupload_userphoto_aws_actionurl', 10, 2);
function poptheme_fileupload_userphoto_aws_actionurl($url, $upload_path) {

	return POPTHEME_WASSUP_ORIGINURI_PLUGINS.'/pop-useravatar-aws/library/fileupload-userphoto/server/index.php?upload_path='.$upload_path;
}