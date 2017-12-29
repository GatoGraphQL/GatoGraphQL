<?php

//-------------------------------------------------------------------------------------
// Allow to hardcode the settings, so no need to input them as options in the DB
//-------------------------------------------------------------------------------------
add_filter('aws_get_setting', 'wassup_aws_get_setting', 10, 2);
function wassup_aws_get_setting($setting, $key) {

	// What bucket (ripess, ripess-dev)
	if ($key == 'bucket' && defined('POPTHEME_WASSUP_AWS_OFFLOADS3_BUCKET')) {

		return POPTHEME_WASSUP_AWS_OFFLOADS3_BUCKET;
	}

	// URL Configuration: CloudFront or Custom Domain
	elseif ($key == 'domain' && defined('POPTHEME_WASSUP_AWS_OFFLOADS3_DOMAIN')) {

		return POPTHEME_WASSUP_AWS_OFFLOADS3_DOMAIN;
	}

	// For Cloudfront URL, which one is it
	elseif ($key == 'cloudfront' && defined('POPTHEME_WASSUP_AWS_OFFLOADS3_CLOUDFRONT')) {

		return POPTHEME_WASSUP_AWS_OFFLOADS3_CLOUDFRONT;
	}

	// The Object Prefix is the same same one as the uploads folder
	elseif ($key == 'object-prefix') {

		// Code taken from plugins/amazon-s3-and-cloudfront/classes/amazon-s3-and-cloudfront.php function get_default_object_prefix()
		$uploads = wp_upload_dir();
		$parts   = parse_url( $uploads['baseurl'] );
		$path    = ltrim( $parts['path'], '/' );
		return trailingslashit( $path );

		// return UPLOADS.'/';
	}

	return $setting;
}

