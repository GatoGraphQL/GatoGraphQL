<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_TEMPLATE_LAYOUT_PROFILEINDIVIDUAL_DETAILS', PoP_TemplateIDUtils::get_template_definition(GD_TEMPLATESOURCE_LAYOUT_PROFILEINDIVIDUAL_DETAILS));
define ('POP_RESOURCELOADER_TEMPLATE_LAYOUT_PROFILEORGANIZATION_DETAILS', PoP_TemplateIDUtils::get_template_definition(GD_TEMPLATESOURCE_LAYOUT_PROFILEORGANIZATION_DETAILS));

class PoPTheme_Wassup_URE_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_TEMPLATE_LAYOUT_PROFILEINDIVIDUAL_DETAILS,
			POP_RESOURCELOADER_TEMPLATE_LAYOUT_PROFILEORGANIZATION_DETAILS,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_TEMPLATE_LAYOUT_PROFILEINDIVIDUAL_DETAILS => GD_TEMPLATESOURCE_LAYOUT_PROFILEINDIVIDUAL_DETAILS,
			POP_RESOURCELOADER_TEMPLATE_LAYOUT_PROFILEORGANIZATION_DETAILS => GD_TEMPLATESOURCE_LAYOUT_PROFILEORGANIZATION_DETAILS,
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {
	
		return POPTHEME_WASSUP_VERSION;
	}
	
	function get_path($resource) {
	
		return POPTHEME_WASSUP_URL.'/js/dist/templates';
	}
	
	function get_dir($resource) {
	
		return POPTHEME_WASSUP_DIR.'/js/dist/templates';
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_URE_TemplateResourceLoaderProcessor();
