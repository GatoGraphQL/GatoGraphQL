<?php

global $gd_dataloader_fieldprocessor_manager;

global $gd_dataload_fieldprocessor_media;
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_MEDIALIST, GD_DATALOAD_FIELDPROCESSOR_MEDIA);

/** -------------------------------------------------------------
 * FieldProcessor Posts Delegator
 ------------------------------------------------------------- */

// Add the Media FieldProcessor to the PostsDelegator FieldProcessor for post type 'attachment'
add_filter('gd_dataload:'.GD_DATALOAD_FIELDPROCESSOR_POSTS_DELEGATOR.':fieldprocessors', 'gd_mla_dataloadersettings_addfieldprocessor');
function gd_mla_dataloadersettings_addfieldprocessor($fieldprocessors) {

	$fieldprocessors['attachment'] = GD_DATALOAD_FIELDPROCESSOR_MEDIA;
	return $fieldprocessors;
}