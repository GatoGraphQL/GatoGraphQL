<?php

// Override the /library settings
add_action('init', 'gd_custom_dataloadersettings_init');
function gd_custom_dataloadersettings_init() {

	global $gd_dataloader_fieldprocessor_manager;

	$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_AUTHOR, GD_CUSTOM_DATALOAD_FIELDPROCESSOR_USERS);
	$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_USERLIST, GD_CUSTOM_DATALOAD_FIELDPROCESSOR_USERS);
	$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_SECONDUSERLIST, GD_CUSTOM_DATALOAD_FIELDPROCESSOR_USERS);
	$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_NOUSERS, GD_CUSTOM_DATALOAD_FIELDPROCESSOR_USERS);
	// $gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_PROFILELIST, GD_CUSTOM_DATALOAD_FIELDPROCESSOR_USERS);
	// $gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_SECONDPROFILELIST, GD_CUSTOM_DATALOAD_FIELDPROCESSOR_USERS);
	$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_FIXEDUSERLIST, GD_CUSTOM_DATALOAD_FIELDPROCESSOR_USERS);
	$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_USERSINGLEEDIT, GD_CUSTOM_DATALOAD_FIELDPROCESSOR_USERS);
	$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_USERFOLLOWSUSERS, GD_CUSTOM_DATALOAD_FIELDPROCESSOR_USERS);
}
