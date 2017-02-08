<?php

add_filter('GD_FileUpload_UserPhoto:action_url', 'poptheme_fileupload_userphoto_actionurl', 10, 2);
function poptheme_fileupload_userphoto_actionurl($url, $upload_path) {

	return POPTHEME_WASSUP_ORIGINURI_PLUGINS.'/pop-useravatar/library/fileupload-userphoto/server/index.php?upload_path='.$upload_path;
}