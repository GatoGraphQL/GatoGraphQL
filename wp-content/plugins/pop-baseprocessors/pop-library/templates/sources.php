<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Sources
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATESOURCE_BLOCK', 'block');

$pop_serverside_rendering = PoP_ServerSideRendering_Factory::get_instance(); 
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_BLOCK, POP_BASEPROCESSORS_PHPTEMPLATES_DIR);
