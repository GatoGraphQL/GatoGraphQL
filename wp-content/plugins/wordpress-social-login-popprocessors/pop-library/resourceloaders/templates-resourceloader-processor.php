<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_TEMPLATE_WSL_NETWORKLINKS', PoP_TemplateIDUtils::get_template_definition(GD_TEMPLATESOURCE_WSL_NETWORKLINKS));

class WSL_PoPProcessors_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_TEMPLATE_WSL_NETWORKLINKS,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_TEMPLATE_WSL_NETWORKLINKS => GD_TEMPLATESOURCE_WSL_NETWORKLINKS,
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {
	
		return WSL_POPPROCESSORS_VERSION;
	}
	
	function get_path($resource) {
	
		return WSL_POPPROCESSORS_URL.'/js/dist/templates';
	}
	
	function get_dir($resource) {
	
		return WSL_POPPROCESSORS_DIR.'/js/dist/templates';
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new WSL_PoPProcessors_TemplateResourceLoaderProcessor();
