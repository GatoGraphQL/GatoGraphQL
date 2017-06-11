<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATESOURCE_LAYOUT_PROFILEINDIVIDUAL_DETAILS', 'ure-layoutuser-profileindividual-details');
define ('GD_TEMPLATESOURCE_LAYOUT_PROFILEORGANIZATION_DETAILS', 'ure-layoutuser-profileorganization-details');

$pop_serverside_rendering = PoP_ServerSideRendering_Factory::get_instance(); 
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_LAYOUT_PROFILEINDIVIDUAL_DETAILS, POPTHEME_WASSUP_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_LAYOUT_PROFILEORGANIZATION_DETAILS, POPTHEME_WASSUP_PHPTEMPLATES_DIR);
