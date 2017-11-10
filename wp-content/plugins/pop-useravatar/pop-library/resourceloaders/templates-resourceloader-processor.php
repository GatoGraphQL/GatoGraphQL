<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_TEMPLATE_FORMCOMPONENT_FILEUPLOAD_PICTURE', PoP_TemplateIDUtils::get_template_definition(GD_TEMPLATESOURCE_FORMCOMPONENT_FILEUPLOAD_PICTURE));
define ('POP_RESOURCELOADER_TEMPLATE_FILEUPLOAD_PICTURE_UPLOAD', PoP_TemplateIDUtils::get_template_definition(GD_TEMPLATESOURCE_FILEUPLOAD_PICTURE_UPLOAD));
define ('POP_RESOURCELOADER_TEMPLATE_FILEUPLOAD_PICTURE_DOWNLOAD', PoP_TemplateIDUtils::get_template_definition(GD_TEMPLATESOURCE_FILEUPLOAD_PICTURE_DOWNLOAD));

class PoP_UserAvatar_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_TEMPLATE_FORMCOMPONENT_FILEUPLOAD_PICTURE,
			POP_RESOURCELOADER_TEMPLATE_FILEUPLOAD_PICTURE_UPLOAD,
			POP_RESOURCELOADER_TEMPLATE_FILEUPLOAD_PICTURE_DOWNLOAD,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_TEMPLATE_FORMCOMPONENT_FILEUPLOAD_PICTURE => GD_TEMPLATESOURCE_FORMCOMPONENT_FILEUPLOAD_PICTURE,
			POP_RESOURCELOADER_TEMPLATE_FILEUPLOAD_PICTURE_UPLOAD => GD_TEMPLATESOURCE_FILEUPLOAD_PICTURE_UPLOAD,
			POP_RESOURCELOADER_TEMPLATE_FILEUPLOAD_PICTURE_DOWNLOAD => GD_TEMPLATESOURCE_FILEUPLOAD_PICTURE_DOWNLOAD,
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {
	
		return POP_USERAVATAR_VERSION;
	}
	
	function get_path($resource) {
	
		return POP_USERAVATAR_URI.'/js/dist/templates';
	}
	
	function get_dir($resource) {
	
		return POP_USERAVATAR_DIR.'/js/dist/templates';
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_UserAvatar_TemplateResourceLoaderProcessor();
