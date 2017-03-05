<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

require_once 'pop-bootstrapprocessors/load.php';
require_once 'pop-coreprocessors/load.php';

if (class_exists('acf'))	
	require_once 'advanced-custom-fields/load.php';

if (class_exists('coauthors_plus'))	
	require_once 'co-authors-plus/load.php';
	
if (class_exists('EM_Event'))	
	require_once 'events-manager/load.php';

if (class_exists('EM_Pro'))		
	require_once 'events-manager-pro/load.php';

if (class_exists("RGForms"))	
	require_once 'gravityforms/load.php';

if (class_exists("JWP6_Plugin"))	
	require_once 'jw-player-plugin-for-wordpress/load.php';

if (class_exists('MLAData'))		
	require_once 'media-library-assistant/load.php';

if (class_exists("DS_Public_Post_Preview"))	
	require_once 'public-post-preview/load.php';

if (defined('QTX_VERSION'))
	require_once 'qtranslate-x/load.php';
	
// if (function_exists('user_avatar_init'))
// 	require_once 'user-avatar/load.php';

if (class_exists('User_Role_Editor'))
	require_once 'user-role-editor/load.php';

// if (function_exists('wsl_activate'))
// 	require_once 'wordpress-social-login/load.php';	

if (function_exists('wpsupercache_site_admin') && defined('WP_CACHE') && WP_CACHE)
	require_once 'wp-super-cache/load.php';	

if (defined('POP_SYSTEM_VERSION')) {
	require_once 'pop-system/load.php';		
}

if (defined('POP_EMAILSENDER_INITIALIZED')) {
	require_once 'pop-emailsender/load.php';		
}

if (defined('POP_MAILER_AWS_VERSION')) {
	require_once 'pop-mailer-aws/load.php';		
}

if (defined('POP_USERAVATAR_AWS_INITIALIZED')) {
	require_once 'pop-useravatar-aws/load.php';		
}

if (defined('POP_USERAVATAR_VERSION')) {
	require_once 'pop-useravatar/load.php';		
}

if (defined('POP_SERVICEWORKERS_INITIALIZED')) {
	require_once 'pop-serviceworkers/load.php';		
}

if (defined('AAL_POP_VERSION')) {
	require_once 'aryo-activity-log-pop/load.php';		
}
if (defined('AAL_POPPROCESSORS_VERSION')) {
	require_once 'aryo-activity-log-popprocessors/load.php';		
}

// Execute after function as3cf_init in amazon-s3-and-cloudfront/wordpress-s3.php
add_action( 'aws_init', 'pop_as3cf_init', 100 );
function pop_as3cf_init() {

	if (class_exists('Amazon_S3_And_CloudFront')) {
		require_once 'amazon-s3-and-cloudfront/load.php';
	}
}