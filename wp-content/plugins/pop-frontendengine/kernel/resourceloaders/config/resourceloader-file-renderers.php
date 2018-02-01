<?php

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_configfile_renderer, $pop_resourceloader_resources_configfile_renderer, $pop_resourceloader_initialresources_configfile_renderer, $pop_resourceloader_hierarchyformatcombinationresources_configfile_renderer, $pop_resourceloader_mirrorcode_renderer;
$pop_resourceloader_configfile_renderer = new PoP_Engine_FileRenderer();
$pop_resourceloader_resources_configfile_renderer = new PoP_Engine_FileRenderer();
$pop_resourceloader_initialresources_configfile_renderer = new PoP_Engine_FileRenderer();
$pop_resourceloader_hierarchyformatcombinationresources_configfile_renderer = new PoP_Engine_FileRenderer();
$pop_resourceloader_mirrorcode_renderer = new PoP_Engine_FileRenderer();