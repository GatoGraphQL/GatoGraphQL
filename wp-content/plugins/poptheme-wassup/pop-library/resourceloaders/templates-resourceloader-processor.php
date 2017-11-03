<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_TEMPLATE_LAYOUT_LINK_ACCESS', PoP_TemplateIDUtils::get_template_definition(GD_TEMPLATESOURCE_LAYOUT_LINK_ACCESS));
define ('POP_RESOURCELOADER_TEMPLATE_LAYOUT_VOLUNTEERTAG', PoP_TemplateIDUtils::get_template_definition(GD_TEMPLATESOURCE_LAYOUT_VOLUNTEERTAG));
define ('POP_RESOURCELOADER_TEMPLATE_PAGESECTION_BACKGROUND', PoP_TemplateIDUtils::get_template_definition(GD_TEMPLATESOURCE_PAGESECTION_BACKGROUND));
define ('POP_RESOURCELOADER_TEMPLATE_PAGESECTION_TOPSIMPLE', PoP_TemplateIDUtils::get_template_definition(GD_TEMPLATESOURCE_PAGESECTION_TOPSIMPLE));
define ('POP_RESOURCELOADER_TEMPLATE_PAGESECTION_SIDE', PoP_TemplateIDUtils::get_template_definition(GD_TEMPLATESOURCE_PAGESECTION_SIDE));
define ('POP_RESOURCELOADER_TEMPLATE_PAGESECTION_TOP', PoP_TemplateIDUtils::get_template_definition(GD_TEMPLATESOURCE_PAGESECTION_TOP));
define ('POP_RESOURCELOADER_TEMPLATE_SPEECHBUBBLE', PoP_TemplateIDUtils::get_template_definition(GD_TEMPLATESOURCE_SPEECHBUBBLE));

class PoPTheme_Wassup_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_TEMPLATE_LAYOUT_LINK_ACCESS,
			POP_RESOURCELOADER_TEMPLATE_LAYOUT_VOLUNTEERTAG,
			POP_RESOURCELOADER_TEMPLATE_PAGESECTION_BACKGROUND,
			POP_RESOURCELOADER_TEMPLATE_PAGESECTION_TOPSIMPLE,
			POP_RESOURCELOADER_TEMPLATE_PAGESECTION_SIDE,
			POP_RESOURCELOADER_TEMPLATE_PAGESECTION_TOP,
			POP_RESOURCELOADER_TEMPLATE_SPEECHBUBBLE,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_TEMPLATE_LAYOUT_LINK_ACCESS => GD_TEMPLATESOURCE_LAYOUT_LINK_ACCESS,
			POP_RESOURCELOADER_TEMPLATE_LAYOUT_VOLUNTEERTAG => GD_TEMPLATESOURCE_LAYOUT_VOLUNTEERTAG,
			POP_RESOURCELOADER_TEMPLATE_PAGESECTION_BACKGROUND => GD_TEMPLATESOURCE_PAGESECTION_BACKGROUND,
			POP_RESOURCELOADER_TEMPLATE_PAGESECTION_TOPSIMPLE => GD_TEMPLATESOURCE_PAGESECTION_TOPSIMPLE,
			POP_RESOURCELOADER_TEMPLATE_PAGESECTION_SIDE => GD_TEMPLATESOURCE_PAGESECTION_SIDE,
			POP_RESOURCELOADER_TEMPLATE_PAGESECTION_TOP => GD_TEMPLATESOURCE_PAGESECTION_TOP,
			POP_RESOURCELOADER_TEMPLATE_SPEECHBUBBLE => GD_TEMPLATESOURCE_SPEECHBUBBLE,
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
	
		return POPTHEME_WASSUP_URI.'/js/dist/templates';
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_TemplateResourceLoaderProcessor();
