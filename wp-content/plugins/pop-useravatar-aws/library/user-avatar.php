<?php 

add_action('init', 'gd_useravatar_aws_init', 5);
function gd_useravatar_aws_init() {
	define( 'USERAVATAR_AWS_USERPHOTO_PATH', gd_useravatar_aws_upload_path());
}

function gd_useravatar_aws_upload_path() { 

	return UPLOADS."/userphoto/";
}

// Make the AWS function take over the original one
add_filter('user_avatar_avatar_exists:override', 'pop_useravatar_aws_user_avatar_avatar_exists', 10, 3);
function pop_useravatar_aws_user_avatar_avatar_exists($exists, $id, $params) {

	global $useravatar_aws_pop_override;
	return $useravatar_aws_pop_override->avatar_exists($id, $params);
}

add_action('gd_user_avatar', 'pop_useravatar_upload_to_s3', 10, 3);
function pop_useravatar_upload_to_s3($user_id, $folderpath, $filename) {

	global $useravatar_aws_pop_override;
	return $useravatar_aws_pop_override->upload_to_s3($user_id, $folderpath, $filename, $original, $thumb);
}

// add_action('gd_get_useravatar_photoinfo', 'pop_useravatar_openawswrapper');
// function pop_useravatar_openawswrapper() {

// 	global $useravatar_aws_pop_override;
// 	return $useravatar_aws_pop_override->open_aws_wrapper();
// }

// Allow the bucket to be configured, then set it in the object
add_action('init', 'pop_useravatar_setconfiguration', 1000);
function pop_useravatar_setconfiguration() {

	$configuration = array(
		'bucket' => POP_AWS_UPLOADSBUCKET,
		'region' => POP_AWS_REGION,
	);

	global $useravatar_aws_pop_override;
	return $useravatar_aws_pop_override->configure($configuration);
}

// Add the CDN domain for the bucket, for the Uploads bucket, if its CDN URL has been defined
add_filter('PoP_UserAvatar_AWS:bucket_url', 'pop_useravatar_aws_bucket_url', 10, 2);
function pop_useravatar_aws_bucket_url($domain, $bucket) {

	if (defined('POP_AWS_CDN_UPLOADS_URI') && POP_AWS_CDN_UPLOADS_URI && $bucket == POP_AWS_UPLOADSBUCKET) {

		return POP_AWS_CDN_UPLOADS_URI;
	}

	return $domain;
}


// Make the AWS function take over the original one
add_filter('gd_get_useravatar_photoinfo:override', 'pop_useravatar_aws_useravatar_photoinfo', 10, 2);
function pop_useravatar_aws_useravatar_photoinfo($photoinfo, $user_id) {

	global $useravatar_aws_pop_override;
	return $useravatar_aws_pop_override->get_useravatar_photoinfo($user_id);
}

// Register the AWS S3 domain in the Allowed Domains list
add_filter('gd_templatemanager:allowed_urls', 'pop_useravatar_allowedurl');
function pop_useravatar_allowedurl($allowed_urls) {

	global $useravatar_aws_pop_override;
	$allowed_urls[] = untrailingslashit($useravatar_aws_pop_override->get_bucket_url());
	return $allowed_urls;
}