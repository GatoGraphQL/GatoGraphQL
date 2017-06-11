<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Sources
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATESOURCE_FORMCOMPONENT_FILEUPLOAD_PICTURE', 'formcomponent-fileupload-picture');
define ('GD_TEMPLATESOURCE_FILEUPLOAD_PICTURE_UPLOAD', 'fileupload-picture-upload');
define ('GD_TEMPLATESOURCE_FILEUPLOAD_PICTURE_DOWNLOAD', 'fileupload-picture-download');

$pop_serverside_rendering = PoP_ServerSideRendering_Factory::get_instance(); 
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_FORMCOMPONENT_FILEUPLOAD_PICTURE, POP_USERAVATAR_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_FILEUPLOAD_PICTURE_UPLOAD, POP_USERAVATAR_PHPTEMPLATES_DIR);
$pop_serverside_rendering->add_templatesource_path(GD_TEMPLATESOURCE_FILEUPLOAD_PICTURE_DOWNLOAD, POP_USERAVATAR_PHPTEMPLATES_DIR);