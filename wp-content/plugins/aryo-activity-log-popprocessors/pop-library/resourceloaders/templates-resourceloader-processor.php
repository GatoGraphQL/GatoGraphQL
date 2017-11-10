<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION', PoP_TemplateIDUtils::get_template_definition(GD_TEMPLATESOURCE_LAYOUT_PREVIEWNOTIFICATION));
define ('POP_RESOURCELOADER_TEMPLATE_LAYOUT_NOTIFICATIONTIME', PoP_TemplateIDUtils::get_template_definition(GD_TEMPLATESOURCE_LAYOUT_NOTIFICATIONTIME));
define ('POP_RESOURCELOADER_TEMPLATE_LAYOUT_NOTIFICATIONICON', PoP_TemplateIDUtils::get_template_definition(GD_TEMPLATESOURCE_LAYOUT_NOTIFICATIONICON));

class PoP_AAL_Processors_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION,
			POP_RESOURCELOADER_TEMPLATE_LAYOUT_NOTIFICATIONTIME,
			POP_RESOURCELOADER_TEMPLATE_LAYOUT_NOTIFICATIONICON,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION => GD_TEMPLATESOURCE_LAYOUT_PREVIEWNOTIFICATION,
			POP_RESOURCELOADER_TEMPLATE_LAYOUT_NOTIFICATIONTIME => GD_TEMPLATESOURCE_LAYOUT_NOTIFICATIONTIME,
			POP_RESOURCELOADER_TEMPLATE_LAYOUT_NOTIFICATIONICON => GD_TEMPLATESOURCE_LAYOUT_NOTIFICATIONICON,
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {
	
		return AAL_POPPROCESSORS_VERSION;
	}
	
	function get_path($resource) {
	
		return AAL_POPPROCESSORS_URI.'/js/dist/templates';
	}
	
	function get_dir($resource) {
	
		return AAL_POPPROCESSORS_DIR.'/js/dist/templates';
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_AAL_Processors_TemplateResourceLoaderProcessor();
