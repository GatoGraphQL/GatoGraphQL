<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_TEMPLATE_BLOCKGROUP_CAROUSEL', PoP_TemplateIDUtils::get_template_definition(GD_TEMPLATESOURCE_BLOCKGROUP_CAROUSEL));
define ('POP_RESOURCELOADER_TEMPLATE_BLOCKGROUP_COLLAPSEPANELGROUP', PoP_TemplateIDUtils::get_template_definition(GD_TEMPLATESOURCE_BLOCKGROUP_COLLAPSEPANELGROUP));
define ('POP_RESOURCELOADER_TEMPLATE_BLOCKGROUP_TABPANEL', PoP_TemplateIDUtils::get_template_definition(GD_TEMPLATESOURCE_BLOCKGROUP_TABPANEL));
define ('POP_RESOURCELOADER_TEMPLATE_BLOCKGROUP_VIEWCOMPONENT', PoP_TemplateIDUtils::get_template_definition(GD_TEMPLATESOURCE_BLOCKGROUP_VIEWCOMPONENT));
define ('POP_RESOURCELOADER_TEMPLATE_PAGESECTION_MODAL', PoP_TemplateIDUtils::get_template_definition(GD_TEMPLATESOURCE_PAGESECTION_MODAL));
define ('POP_RESOURCELOADER_TEMPLATE_PAGESECTION_PAGETAB', PoP_TemplateIDUtils::get_template_definition(GD_TEMPLATESOURCE_PAGESECTION_PAGETAB));
define ('POP_RESOURCELOADER_TEMPLATE_PAGESECTION_TABPANE', PoP_TemplateIDUtils::get_template_definition(GD_TEMPLATESOURCE_PAGESECTION_TABPANE));

class PoP_BootstrapProcessors_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_TEMPLATE_BLOCKGROUP_CAROUSEL,
			POP_RESOURCELOADER_TEMPLATE_BLOCKGROUP_COLLAPSEPANELGROUP,
			POP_RESOURCELOADER_TEMPLATE_BLOCKGROUP_TABPANEL,
			POP_RESOURCELOADER_TEMPLATE_BLOCKGROUP_VIEWCOMPONENT,
			POP_RESOURCELOADER_TEMPLATE_PAGESECTION_MODAL,
			POP_RESOURCELOADER_TEMPLATE_PAGESECTION_PAGETAB,
			POP_RESOURCELOADER_TEMPLATE_PAGESECTION_TABPANE,
		);
	}
	
	// function is_extension($resource) {

	// 	switch ($resource) {
			
	// 		case POP_RESOURCELOADER_TEMPLATE_BLOCKGROUP_CAROUSEL:
	// 		case POP_RESOURCELOADER_TEMPLATE_BLOCKGROUP_COLLAPSEPANELGROUP:
	// 		case POP_RESOURCELOADER_TEMPLATE_BLOCKGROUP_TABPANEL:
	// 		case POP_RESOURCELOADER_TEMPLATE_BLOCKGROUP_VIEWCOMPONENT:

	// 			return true;
	// 	}
	
	// 	return parent::is_extension($resource);
	// }
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_TEMPLATE_BLOCKGROUP_CAROUSEL => GD_TEMPLATESOURCE_BLOCKGROUP_CAROUSEL,
			POP_RESOURCELOADER_TEMPLATE_BLOCKGROUP_COLLAPSEPANELGROUP => GD_TEMPLATESOURCE_BLOCKGROUP_COLLAPSEPANELGROUP,
			POP_RESOURCELOADER_TEMPLATE_BLOCKGROUP_TABPANEL => GD_TEMPLATESOURCE_BLOCKGROUP_TABPANEL,
			POP_RESOURCELOADER_TEMPLATE_BLOCKGROUP_VIEWCOMPONENT => GD_TEMPLATESOURCE_BLOCKGROUP_VIEWCOMPONENT,
			POP_RESOURCELOADER_TEMPLATE_PAGESECTION_MODAL => GD_TEMPLATESOURCE_PAGESECTION_MODAL,
			POP_RESOURCELOADER_TEMPLATE_PAGESECTION_PAGETAB => GD_TEMPLATESOURCE_PAGESECTION_PAGETAB,
			POP_RESOURCELOADER_TEMPLATE_PAGESECTION_TABPANE => GD_TEMPLATESOURCE_PAGESECTION_TABPANE,
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {
	
		return POP_BOOTSTRAPPROCESSORS_VERSION;
	}
	
	function get_path($resource) {
	
		return POP_BOOTSTRAPPROCESSORS_URL.'/js/dist/templates';
	}
	
	function get_dir($resource) {
	
		return POP_BOOTSTRAPPROCESSORS_DIR.'/js/dist/templates';
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_BootstrapProcessors_TemplateResourceLoaderProcessor();
