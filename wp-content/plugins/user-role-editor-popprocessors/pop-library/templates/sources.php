<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATESOURCE_LAYOUTUSER_MEMBERPRIVILEGES', 'ure-layoutuser-memberprivileges');
define ('GD_TEMPLATESOURCE_LAYOUTUSER_MEMBERSTATUS', 'ure-layoutuser-memberstatus');
define ('GD_TEMPLATESOURCE_LAYOUTUSER_MEMBERTAGS', 'ure-layoutuser-membertags');
define ('GD_TEMPLATESOURCE_LAYOUTUSER_TYPEAHEAD_SELECTED_FILTERBYCOMMUNITY', 'ure-layoutuser-typeahead-selected-filterbycommunity');

$pop_serverside_rendering = PoP_ServerSideRendering_Factory::get_instance(); 
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_LAYOUTUSER_MEMBERPRIVILEGES, URE_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_LAYOUTUSER_MEMBERSTATUS, URE_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_LAYOUTUSER_MEMBERTAGS, URE_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_LAYOUTUSER_TYPEAHEAD_SELECTED_FILTERBYCOMMUNITY, URE_POPPROCESSORS_PHPTEMPLATES_DIR);