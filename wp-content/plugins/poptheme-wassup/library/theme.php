<?php

// Function needed to obtain the stylesheet URI as pointing to the origin (the website) before the URI gets replaced with the CDN in wp-content/plugins/pop-aws/library/cdn.php function pop_aws_cdn_assetsrc_init()
// Copied from wp-includes/theme.php
function get_origin_stylesheet_directory_uri() {
// function get_stylesheet_directory_uri() {
	$stylesheet = str_replace( '%2F', '/', rawurlencode( get_stylesheet() ) );
	$theme_root_uri = get_theme_root_uri( $stylesheet );
	$stylesheet_dir_uri = "$theme_root_uri/$stylesheet";

	return $stylesheet_dir_uri;
}