<?php

global $gd_dataloader_fieldprocessor_manager;

$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_EVENTLIST, GD_DATALOAD_FIELDPROCESSOR_EVENTS);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_PASTEVENTLIST, GD_DATALOAD_FIELDPROCESSOR_EVENTS);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_LOCATIONLIST, GD_DATALOAD_FIELDPROCESSOR_LOCATIONS);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_EDITLOCATION, GD_DATALOAD_FIELDPROCESSOR_LOCATIONS);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_EVENTSINGLE, GD_DATALOAD_FIELDPROCESSOR_EVENTS);
$gd_dataloader_fieldprocessor_manager->add(GD_DATALOADER_EDITEVENT, GD_DATALOAD_FIELDPROCESSOR_EVENTS);

/** -------------------------------------------------------------
 * FieldProcessor Posts Delegator
 ------------------------------------------------------------- */

// Add the Events FieldProcessor to the PostsDelegator FieldProcessor for post type 'event'
add_filter('gd_dataload:'.GD_DATALOAD_FIELDPROCESSOR_POSTS_DELEGATOR.':fieldprocessors', 'gd_em_dataloadersettings_addfieldprocessor');
function gd_em_dataloadersettings_addfieldprocessor($fieldprocessors) {

	$fieldprocessors[EM_POST_TYPE_EVENT] = GD_DATALOAD_FIELDPROCESSOR_EVENTS;
	return $fieldprocessors;
}