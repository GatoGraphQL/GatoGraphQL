<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

// Register the AWS S3 domain in the Allowed Domains list
add_filter('gd_templatemanager:allowed_domains', 'pop_awss3_allowedurl');
function pop_awss3_allowedurl($allowed_domains) {

	global $as3cf;

	// Copied from plugins/amazon-s3-and-cloudfront/classes/amazon-s3-and-cloudfront.php function get_s3_url_domain
	$scheme = $as3cf->get_s3_url_scheme();
	$bucket = $as3cf->get_setting('bucket');
	$region = $as3cf->get_setting('region');
	if (is_wp_error($region)) {
		$region = '';
	}

	$domain = $as3cf->get_s3_url_domain($bucket, $region);
	$domain = $scheme . '://' . $domain;

	$allowed_domains[] = $domain;

	return $allowed_domains;
}