<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_TEMPLATEEXTENSION_PAGESECTIONREPLICABLE', PoP_ServerUtils::get_template_definition(GD_TEMPLATEEXTENSION_PAGESECTIONREPLICABLE));
define ('POP_RESOURCELOADER_TEMPLATEEXTENSION_PAGESECTIONFRAME', PoP_ServerUtils::get_template_definition(GD_TEMPLATEEXTENSION_PAGESECTIONFRAME));
define ('POP_RESOURCELOADER_TEMPLATEEXTENSION_APPENDABLECLASS', PoP_ServerUtils::get_template_definition(GD_TEMPLATEEXTENSION_APPENDABLECLASS));

class PoP_FrontEnd_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_TEMPLATEEXTENSION_PAGESECTIONREPLICABLE,
			POP_RESOURCELOADER_TEMPLATEEXTENSION_PAGESECTIONFRAME,
			POP_RESOURCELOADER_TEMPLATEEXTENSION_APPENDABLECLASS,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_TEMPLATEEXTENSION_PAGESECTIONREPLICABLE => GD_TEMPLATEEXTENSION_PAGESECTIONREPLICABLE,
			POP_RESOURCELOADER_TEMPLATEEXTENSION_PAGESECTIONFRAME => GD_TEMPLATEEXTENSION_PAGESECTIONFRAME,
			POP_RESOURCELOADER_TEMPLATEEXTENSION_APPENDABLECLASS => GD_TEMPLATEEXTENSION_APPENDABLECLASS,
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {
	
		return POP_FRONTENDENGINE_VERSION;
	}
	
	function get_path($resource) {
	
		return POP_FRONTENDENGINE_URI.'/js/dist/templates';
	}
	
	// function is_extension($resource) {

	// 	switch ($resource) {

	// 		case POP_RESOURCELOADER_TEMPLATEEXTENSION_PAGESECTIONREPLICABLE:
	// 		case POP_RESOURCELOADER_TEMPLATEEXTENSION_PAGESECTIONFRAME:
	// 		case POP_RESOURCELOADER_TEMPLATEEXTENSION_APPENDABLECLASS:

	// 			return true;
	// 	}
	
	// 	return parent::is_extension($resource);
	// }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_FrontEnd_TemplateResourceLoaderProcessor();
