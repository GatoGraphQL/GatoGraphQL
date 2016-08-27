<?php

global $gd_dataloader_fieldprocessor_manager;

$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_AUTHOR, GD_DATALOAD_FIELDPROCESSOR_USERS);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_MENU, GD_DATALOAD_FIELDPROCESSOR_MENU);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_POSTLIST, GD_DATALOAD_FIELDPROCESSOR_POSTS);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_FIXEDPOSTLIST, GD_DATALOAD_FIELDPROCESSOR_POSTS);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_SECONDPOSTLIST, GD_DATALOAD_FIELDPROCESSOR_POSTS);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_CONVERTIBLEPOSTLIST, GD_DATALOAD_FIELDPROCESSOR_POSTS_DELEGATOR);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_SECONDCONVERTIBLEPOSTLIST, GD_DATALOAD_FIELDPROCESSOR_POSTS_DELEGATOR);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_SINGLE, GD_DATALOAD_FIELDPROCESSOR_POSTS);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_EDITPOST, GD_DATALOAD_FIELDPROCESSOR_POSTS);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_NOPOSTS, GD_DATALOAD_FIELDPROCESSOR_POSTS);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_USERLIST, GD_DATALOAD_FIELDPROCESSOR_USERS);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_TAGLIST, GD_DATALOAD_FIELDPROCESSOR_TAGS);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_TRENDINGTAGLIST, GD_DATALOAD_FIELDPROCESSOR_TAGS);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_NOUSERS, GD_DATALOAD_FIELDPROCESSOR_USERS);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_SECONDUSERLIST, GD_DATALOAD_FIELDPROCESSOR_USERS);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_FIXEDUSERLIST, GD_DATALOAD_FIELDPROCESSOR_USERS);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_USERSINGLEEDIT, GD_DATALOAD_FIELDPROCESSOR_USERS);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_EDITUSER, GD_DATALOAD_FIELDPROCESSOR_USERS);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_COMMENTLIST, GD_DATALOAD_FIELDPROCESSOR_COMMENTS);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_COMMENTSINGLE, GD_DATALOAD_FIELDPROCESSOR_COMMENTS);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_EDITCOMMENT, GD_DATALOAD_FIELDPROCESSOR_COMMENTS);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_EDITTAG, GD_DATALOAD_FIELDPROCESSOR_TAGS);

/** -------------------------------------------------------------
 * FieldProcessor Posts Delegator
 ------------------------------------------------------------- */

// Add the Posts FieldProcessor to the PostsDelegator FieldProcessor for post type 'post'
add_filter('gd_dataload:'.GD_DATALOAD_FIELDPROCESSOR_POSTS_DELEGATOR.':fieldprocessors', 'gd_dataloadersettings_addfieldprocessor');
function gd_dataloadersettings_addfieldprocessor($fieldprocessors) {

	$fieldprocessors['post'] = GD_DATALOAD_FIELDPROCESSOR_POSTS;
	return $fieldprocessors;
}