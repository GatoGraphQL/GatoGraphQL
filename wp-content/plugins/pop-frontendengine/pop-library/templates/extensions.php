<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Extensions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATEEXTENSION_PAGESECTIONREPLICABLE', 'pagesectionextension-replicable');
define ('GD_TEMPLATEEXTENSION_PAGESECTIONFRAME', 'pagesectionextension-frame');
define ('GD_TEMPLATEEXTENSION_APPENDABLECLASS', 'extension-appendableclass');

$pop_serverside_rendering = PoP_ServerSideRendering_Factory::get_instance(); 
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATEEXTENSION_PAGESECTIONREPLICABLE, POP_FRONTENDENGINE_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATEEXTENSION_PAGESECTIONFRAME, POP_FRONTENDENGINE_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATEEXTENSION_APPENDABLECLASS, POP_FRONTENDENGINE_PHPTEMPLATES_DIR);