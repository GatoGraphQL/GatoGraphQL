<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATESOURCE_CALENDAR', 'em-calendar');
define ('GD_TEMPLATESOURCE_CALENDAR_INNER', 'em-calendar-inner');
define ('GD_TEMPLATESOURCE_FORMCOMPONENT_TYPEAHEADMAP', 'em-formcomponent-typeaheadmap');
define ('GD_TEMPLATESOURCE_FRAME_CREATELOCATIONMAP', 'em-frame-createlocationmap');
define ('GD_TEMPLATESOURCE_LAYOUT_CAROUSEL_INDICATORS_EVENTDATE', 'em-layout-carousel-indicators-eventdate');
define ('GD_TEMPLATESOURCE_LAYOUT_DATETIME', 'em-layout-datetime');
define ('GD_TEMPLATESOURCE_LAYOUT_LOCATIONADDRESS', 'em-layout-locationaddress');
define ('GD_TEMPLATESOURCE_LAYOUT_LOCATIONNAME', 'em-layout-locationname');
define ('GD_TEMPLATESOURCE_LAYOUT_LOCATIONS', 'em-layout-locations');
define ('GD_TEMPLATESOURCE_LAYOUTCALENDAR_CONTENT_POPOVER', 'em-layoutcalendar-content');
define ('GD_TEMPLATESOURCE_LAYOUTEVENT_TABLECOL', 'em-layoutevent-tablecol');
define ('GD_TEMPLATESOURCE_LAYOUTLOCATION_TYPEAHEAD_COMPONENT', 'em-layoutlocation-typeahead-component');
define ('GD_TEMPLATESOURCE_LAYOUTLOCATION_TYPEAHEAD_SELECTED', 'em-layoutlocation-typeahead-selected');
define ('GD_TEMPLATESOURCE_MAP', 'em-map');
define ('GD_TEMPLATESOURCE_MAP_ADDMARKER', 'em-map-addmarker');
define ('GD_TEMPLATESOURCE_MAP_DIV', 'em-map-div');
define ('GD_TEMPLATESOURCE_MAP_INDIVIDUAL', 'em-map-individual');
define ('GD_TEMPLATESOURCE_MAP_INNER', 'em-map-inner');
define ('GD_TEMPLATESOURCE_MAP_SCRIPT', 'em-map-script');
define ('GD_TEMPLATESOURCE_MAP_SCRIPT_DRAWMARKERS', 'em-map-script-drawmarkers');
define ('GD_TEMPLATESOURCE_MAP_SCRIPT_MARKERS', 'em-map-script-markers');
define ('GD_TEMPLATESOURCE_MAP_SCRIPT_RESETMARKERS', 'em-map-script-resetmarkers');
define ('GD_TEMPLATESOURCE_MAP_SCRIPTCUSTOMIZATION_POST', 'em-post-map-scriptcustomization');
define ('GD_TEMPLATESOURCE_MAP_SCRIPTCUSTOMIZATION_USER', 'em-user-map-scriptcustomization');
define ('GD_TEMPLATESOURCE_SCRIPT_TRIGGERTYPEAHEADSELECT_LOCATION', 'em-script-triggertypeaheadselect-location');
define ('GD_TEMPLATESOURCE_VIEWCOMPONENT_LOCATIONBUTTON', 'em-viewcomponent-locationbutton');
define ('GD_TEMPLATESOURCE_VIEWCOMPONENT_LOCATIONBUTTONINNER', 'em-viewcomponent-locationbuttoninner');

$pop_serverside_rendering = PoP_ServerSideRendering_Factory::get_instance(); 
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_CALENDAR, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_CALENDAR_INNER, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_FORMCOMPONENT_TYPEAHEADMAP, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_FRAME_CREATELOCATIONMAP, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_LAYOUT_CAROUSEL_INDICATORS_EVENTDATE, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_LAYOUT_DATETIME, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_LAYOUT_LOCATIONADDRESS, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_LAYOUT_LOCATIONNAME, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_LAYOUT_LOCATIONS, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_LAYOUTCALENDAR_CONTENT_POPOVER, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_LAYOUTEVENT_TABLECOL, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_LAYOUTLOCATION_TYPEAHEAD_COMPONENT, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_LAYOUTLOCATION_TYPEAHEAD_SELECTED, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_MAP, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_MAP_ADDMARKER, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_MAP_DIV, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_MAP_INDIVIDUAL, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_MAP_INNER, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_MAP_SCRIPT, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_MAP_SCRIPT_DRAWMARKERS, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_MAP_SCRIPT_MARKERS, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_MAP_SCRIPT_RESETMARKERS, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_MAP_SCRIPTCUSTOMIZATION_POST, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_MAP_SCRIPTCUSTOMIZATION_USER, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_SCRIPT_TRIGGERTYPEAHEADSELECT_LOCATION, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_VIEWCOMPONENT_LOCATIONBUTTON, EM_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_VIEWCOMPONENT_LOCATIONBUTTONINNER, EM_POPPROCESSORS_PHPTEMPLATES_DIR);