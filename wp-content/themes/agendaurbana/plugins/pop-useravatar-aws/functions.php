<?php

// Override as to provide the credentials
add_filter('GD_FileUpload_UserPhoto_AWS:action_url', 'agendaurbana_fileupload_userphoto_aws_actionurl', 100, 2);
function agendaurbana_fileupload_userphoto_aws_actionurl($url, $upload_path) {

	return AGENDAURBANA_URI_PLUGINS.'/pop-useravatar-aws/library/fileupload-userphoto/server/index.php?upload_path='.$upload_path;
}