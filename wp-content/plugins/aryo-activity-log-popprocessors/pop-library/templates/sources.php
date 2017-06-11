<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Sources
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATESOURCE_LAYOUT_PREVIEWNOTIFICATION', 'aal-layout-previewnotification');
define ('GD_TEMPLATESOURCE_LAYOUT_NOTIFICATIONTIME', 'aal-layout-notificationtime');
define ('GD_TEMPLATESOURCE_LAYOUT_NOTIFICATIONICON', 'aal-layout-notificationicon');

$pop_serverside_rendering = PoP_ServerSideRendering_Factory::get_instance(); 
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_LAYOUT_PREVIEWNOTIFICATION, AAL_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_LAYOUT_NOTIFICATIONTIME, AAL_POPPROCESSORS_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_LAYOUT_NOTIFICATIONICON, AAL_POPPROCESSORS_PHPTEMPLATES_DIR);