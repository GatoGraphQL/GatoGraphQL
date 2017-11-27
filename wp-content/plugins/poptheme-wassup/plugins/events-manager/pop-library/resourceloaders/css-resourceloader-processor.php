<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_CSS_CALENDAR', PoP_TemplateIDUtils::get_template_definition('css-em-calendar'));
define ('POP_RESOURCELOADER_CSS_MAP', PoP_TemplateIDUtils::get_template_definition('css-em-map'));

// class PoPTheme_Wassup_EM_CSSResourceLoaderProcessor extends PoP_CSSComponentResourceLoaderProcessor {
class PoPTheme_Wassup_EM_CSSResourceLoaderProcessor extends PoP_CSSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_CSS_CALENDAR,
			POP_RESOURCELOADER_CSS_MAP,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_CSS_CALENDAR => 'calendar',
			POP_RESOURCELOADER_CSS_MAP => 'map',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {
	
		return POPTHEME_WASSUP_VERSION;
	}
	
	function get_dir($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POPTHEME_WASSUP_DIR.'/css/'.$subpath.'templates/plugins/events-manager';
	}
	
	function get_asset_path($resource) {

		return POPTHEME_WASSUP_DIR.'/css/templates/plugins/events-manager/'.$this->get_filename($resource).'.css';
	}
	
	function get_path($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POPTHEME_WASSUP_URI.'/css/'.$subpath.'templates/plugins/events-manager';
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_EM_CSSResourceLoaderProcessor();
