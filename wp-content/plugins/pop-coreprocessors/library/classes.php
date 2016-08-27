<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * All CSS Classes
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_CLASS_LAZYJS', 'pop-lazyjs');

define ('GD_CLASS_FOLLOWUSER', 'pop-followuser');
define ('GD_CLASS_UNFOLLOWUSER', 'pop-unfollowuser');
define ('GD_CLASS_RECOMMENDPOST', 'pop-recommendpost');
define ('GD_CLASS_UNRECOMMENDPOST', 'pop-unrecommendpost');
define ('GD_CLASS_UPVOTEPOST', 'pop-upvotepost');
define ('GD_CLASS_UNDOUPVOTEPOST', 'pop-undoupvotepost');
define ('GD_CLASS_DOWNVOTEPOST', 'pop-downvotepost');
define ('GD_CLASS_UNDODOWNVOTEPOST', 'pop-undodownvotepost');

add_filter('gd_jquery_constants', 'gd_popcore_classes_jquery_constants');
function gd_popcore_classes_jquery_constants($jquery_constants) {

	$jquery_constants['CLASS_LAZYJS'] = GD_CLASS_LAZYJS;	
	return $jquery_constants;
}

function gd_classes_body() {

	if ( function_exists('body_class') ) {
		
		return implode(' ', apply_filters('gd_classes_body', array()));
	}

	return '';
}

/* 
 * Add extra classes to the body: Theme
 */
add_filter("gd_classes_body", 'gd_classes_body_theme_impl');
function gd_classes_body_theme_impl($body_classes) {

	$vars = GD_TemplateManager_Utils::get_vars();
	$body_classes[] = $vars['theme'];
	$body_classes[] = $vars['thememode'];
	$body_classes[] = $vars['themestyle'];
	$body_classes[] = $vars['theme'].'-'.$vars['thememode'];
	$body_classes[] = $vars['theme'].'-'.$vars['themestyle'];
	$body_classes[] = $vars['theme'].'-'.$vars['thememode'].'-'.$vars['themestyle'];
	
	return $body_classes;
}


/* 
 * Add extra classes to the body: Frontend
 * Then it is possible to hide elements in the Media Library in the frontend
 */
add_filter("gd_classes_body", 'gd_classes_body_frontend_impl');
function gd_classes_body_frontend_impl($body_classes) {

	// User role
	if (!is_admin())
		$body_classes[] = "frontend";
	
	return $body_classes;
}