<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_TEMPLATE_BLOCK', PoP_TemplateIDUtils::get_template_definition(GD_TEMPLATESOURCE_BLOCK));

class PoP_BaseProcessors_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_TEMPLATE_BLOCK,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_TEMPLATE_BLOCK => GD_TEMPLATESOURCE_BLOCK,
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {
	
		return POP_BASEPROCESSORS_VERSION;
	}
	
	function get_path($resource) {
	
		return POP_BASEPROCESSORS_URI.'/js/dist/templates';
	}
	
	function get_dir($resource) {
	
		return POP_BASEPROCESSORS_DIR.'/js/dist/templates';
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_BaseProcessors_TemplateResourceLoaderProcessor();
