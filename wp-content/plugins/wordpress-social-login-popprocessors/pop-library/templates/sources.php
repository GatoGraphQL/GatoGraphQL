<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATESOURCE_WSL_NETWORKLINKS', 'wsl-networklinks');

$pop_serverside_rendering = PoP_ServerSideRendering_Factory::get_instance(); 
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_WSL_NETWORKLINKS, WSL_POPPROCESSORS_PHPTEMPLATES_DIR);