<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_TEMPLATE_LAYOUTUSER_MEMBERPRIVILEGES', PoP_ServerUtils::get_template_definition(GD_TEMPLATESOURCE_LAYOUTUSER_MEMBERPRIVILEGES));
define ('POP_RESOURCELOADER_TEMPLATE_LAYOUTUSER_MEMBERSTATUS', PoP_ServerUtils::get_template_definition(GD_TEMPLATESOURCE_LAYOUTUSER_MEMBERSTATUS));
define ('POP_RESOURCELOADER_TEMPLATE_LAYOUTUSER_MEMBERTAGS', PoP_ServerUtils::get_template_definition(GD_TEMPLATESOURCE_LAYOUTUSER_MEMBERTAGS));
define ('POP_RESOURCELOADER_TEMPLATE_LAYOUTUSER_TYPEAHEAD_SELECTED_FILTERBYCOMMUNITY', PoP_ServerUtils::get_template_definition(GD_TEMPLATESOURCE_LAYOUTUSER_TYPEAHEAD_SELECTED_FILTERBYCOMMUNITY));

class URE_PoPProcessors_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_TEMPLATE_LAYOUTUSER_MEMBERPRIVILEGES,
			POP_RESOURCELOADER_TEMPLATE_LAYOUTUSER_MEMBERSTATUS,
			POP_RESOURCELOADER_TEMPLATE_LAYOUTUSER_MEMBERTAGS,
			POP_RESOURCELOADER_TEMPLATE_LAYOUTUSER_TYPEAHEAD_SELECTED_FILTERBYCOMMUNITY,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_TEMPLATE_LAYOUTUSER_MEMBERPRIVILEGES => GD_TEMPLATESOURCE_LAYOUTUSER_MEMBERPRIVILEGES,
			POP_RESOURCELOADER_TEMPLATE_LAYOUTUSER_MEMBERSTATUS => GD_TEMPLATESOURCE_LAYOUTUSER_MEMBERSTATUS,
			POP_RESOURCELOADER_TEMPLATE_LAYOUTUSER_MEMBERTAGS => GD_TEMPLATESOURCE_LAYOUTUSER_MEMBERTAGS,
			POP_RESOURCELOADER_TEMPLATE_LAYOUTUSER_TYPEAHEAD_SELECTED_FILTERBYCOMMUNITY => GD_TEMPLATESOURCE_LAYOUTUSER_TYPEAHEAD_SELECTED_FILTERBYCOMMUNITY,
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {
	
		return URE_POPPROCESSORS_VERSION;
	}
	
	function get_path($resource) {
	
		return URE_POPPROCESSORS_URI.'/js/dist/templates';
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new URE_PoPProcessors_TemplateResourceLoaderProcessor();
