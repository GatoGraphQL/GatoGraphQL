<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_CSS_NOTIFICATIONLAYOUT', PoP_TemplateIDUtils::get_template_definition('css-notification-layout'));

class PopThemeWassup_AAL_CSSResourceLoaderProcessor extends PoP_CSSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_CSS_NOTIFICATIONLAYOUT,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_CSS_NOTIFICATIONLAYOUT => 'notification-layout',
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
		return POPTHEME_WASSUP_DIR.'/css/'.$subpath.'templates/plugins/aryo-activity-log-pop';
	}
	
	function get_asset_path($resource) {

		return POPTHEME_WASSUP_DIR.'/css/templates/plugins/aryo-activity-log-pop/'.$this->get_filename($resource).'.css';
	}
	
	function get_path($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POPTHEME_WASSUP_URI.'/css/'.$subpath.'templates/plugins/aryo-activity-log-pop';
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PopThemeWassup_AAL_CSSResourceLoaderProcessor();
