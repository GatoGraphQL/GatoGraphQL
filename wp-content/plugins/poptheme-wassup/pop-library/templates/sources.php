<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATESOURCE_LAYOUT_LINK_ACCESS', 'layout-link-access');
define ('GD_TEMPLATESOURCE_LAYOUT_VOLUNTEERTAG', 'layout-volunteertag');
define ('GD_TEMPLATESOURCE_PAGESECTION_BACKGROUND', 'pagesection-background');
define ('GD_TEMPLATESOURCE_PAGESECTION_TOPSIMPLE', 'pagesection-topsimple');
define ('GD_TEMPLATESOURCE_PAGESECTION_SIDE', 'pagesection-side');
define ('GD_TEMPLATESOURCE_PAGESECTION_TOP', 'pagesection-top');
define ('GD_TEMPLATESOURCE_SPEECHBUBBLE', 'speechbubble');

$pop_serverside_rendering = PoP_ServerSideRendering_Factory::get_instance(); 
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_LAYOUT_LINK_ACCESS, POPTHEME_WASSUP_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_LAYOUT_VOLUNTEERTAG, POPTHEME_WASSUP_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_PAGESECTION_BACKGROUND, POPTHEME_WASSUP_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_PAGESECTION_TOPSIMPLE, POPTHEME_WASSUP_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_PAGESECTION_SIDE, POPTHEME_WASSUP_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_PAGESECTION_TOP, POPTHEME_WASSUP_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_SPEECHBUBBLE, POPTHEME_WASSUP_PHPTEMPLATES_DIR);