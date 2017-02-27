<?php

// Override the /library settings
add_action('init', 'gd_custom_customdataloadersettings_init');
function gd_custom_customdataloadersettings_init() {

	global $gd_dataloader_fieldprocessor_manager;

	$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_POSTLIST, GD_CUSTOM_DATALOAD_FIELDPROCESSOR_POSTS);
	$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_PAGELIST, GD_CUSTOM_DATALOAD_FIELDPROCESSOR_POSTS);
	$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_FIXEDPOSTLIST, GD_CUSTOM_DATALOAD_FIELDPROCESSOR_POSTS);
	$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_SECONDPOSTLIST, GD_CUSTOM_DATALOAD_FIELDPROCESSOR_POSTS);
	$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_SINGLE, GD_CUSTOM_DATALOAD_FIELDPROCESSOR_POSTS);
	$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_NOPOSTS, GD_CUSTOM_DATALOAD_FIELDPROCESSOR_POSTS);
	$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_EDITPOST, GD_CUSTOM_DATALOAD_FIELDPROCESSOR_POSTS);
	$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_USERRECOMMENDSPOSTS, GD_CUSTOM_DATALOAD_FIELDPROCESSOR_POSTS);
	$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_USERUPVOTESPOSTS, GD_CUSTOM_DATALOAD_FIELDPROCESSOR_POSTS);
	$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_USERDOWNVOTESPOSTS, GD_CUSTOM_DATALOAD_FIELDPROCESSOR_POSTS);
}

/** -------------------------------------------------------------
 * FieldProcessor Posts Delegator
 ------------------------------------------------------------- */

// Add the Posts FieldProcessor to the PostsDelegator FieldProcessor for post type 'post'
// Priority 20: override the one in /library
add_filter('gd_dataload:'.GD_DATALOAD_FIELDPROCESSOR_POSTS_DELEGATOR.':fieldprocessors', 'gd_custom_customdataloadersettings_addfieldprocessor', 20);
function gd_custom_customdataloadersettings_addfieldprocessor($fieldprocessors) {

	$fieldprocessors['post'] = GD_CUSTOM_DATALOAD_FIELDPROCESSOR_POSTS;
	return $fieldprocessors;
}