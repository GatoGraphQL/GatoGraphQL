<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

// Register the AWS S3 domain in the Allowed Domains list
add_filter('gd_templatemanager:allowed_domains', 'pop_awss3_allowedurl');
function pop_awss3_allowedurl($allowed_domains) {

	// Comment Leo 29/12/2017: getting the region like below doesn't work, so get it from outside then
	// $region = $as3cf->get_setting('region');
	// if (is_wp_error($region)) {
	// 	$region = '';
	// }
	if ($region = apply_filters('pop_awss3_allowedurl:region', '')) {

		global $as3cf;

		// Copied from plugins/amazon-s3-and-cloudfront/classes/amazon-s3-and-cloudfront.php function get_s3_url_domain
		// Force https
		$scheme = $as3cf->get_s3_url_scheme(true);
		$bucket = $as3cf->get_setting('bucket');

		$domain = $as3cf->get_s3_url_domain($bucket, $region);
		$domain = $scheme . '://' . $domain;

		// We need to do get_domain because the S3 URL Domain may be domain/bucket (if doing POPTHEME_WASSUP_AWS_OFFLOADS3_DOMAIN = 'path')
		$allowed_domains[] = get_domain($domain);
	}

	return $allowed_domains;
}