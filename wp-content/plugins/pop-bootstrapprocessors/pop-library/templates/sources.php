<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Sources
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATESOURCE_BLOCKGROUP_CAROUSEL', 'blockgroup-carousel');
define ('GD_TEMPLATESOURCE_BLOCKGROUP_COLLAPSEPANELGROUP', 'blockgroup-collapsepanelgroup');
define ('GD_TEMPLATESOURCE_BLOCKGROUP_TABPANEL', 'blockgroup-tabpanel');
define ('GD_TEMPLATESOURCE_BLOCKGROUP_VIEWCOMPONENT', 'blockgroup-viewcomponent');
define ('GD_TEMPLATESOURCE_PAGESECTION_MODAL', 'pagesection-modal');
define ('GD_TEMPLATESOURCE_PAGESECTION_PAGETAB', 'pagesection-pagetab');
define ('GD_TEMPLATESOURCE_PAGESECTION_TABPANE', 'pagesection-tabpane');

$pop_serverside_rendering = PoP_ServerSideRendering_Factory::get_instance(); 
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_BLOCKGROUP_CAROUSEL, POP_BOOTSTRAPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_BLOCKGROUP_COLLAPSEPANELGROUP, POP_BOOTSTRAPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_BLOCKGROUP_TABPANEL, POP_BOOTSTRAPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_BLOCKGROUP_VIEWCOMPONENT, POP_BOOTSTRAPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_PAGESECTION_MODAL, POP_BOOTSTRAPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_PAGESECTION_PAGETAB, POP_BOOTSTRAPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_PAGESECTION_TABPANE, POP_BOOTSTRAPPROCESSORS_PHPTEMPLATES_DIR);
