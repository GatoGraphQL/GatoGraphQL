<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_CSS_SECTIONLAYOUT', PoP_TemplateIDUtils::get_template_definition('css-sectionlayout'));

class PoPTheme_Wassup_SectionProcessors_CSSResourceLoaderProcessor extends PoP_CSSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_CSS_SECTIONLAYOUT,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_CSS_SECTIONLAYOUT => 'section-layout',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {
	
		return POPTHEME_WASSUP_SECTIONPROCESSORS_VERSION;
	}
	
	function get_dir($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POPTHEME_WASSUP_SECTIONPROCESSORS_DIR.'/css/'.$subpath.'libraries';
	}
	
	// function get_asset_path($resource) {

	// 	return POPTHEME_WASSUP_SECTIONPROCESSORS_DIR.'/css/libraries/'.$this->get_filename($resource).'.css';
	// }
	
	function get_path($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POPTHEME_WASSUP_SECTIONPROCESSORS_URL.'/css/'.$subpath.'libraries';
	}
	
	function get_decorated_resources($resource) {

		$decorated = parent::get_decorated_resources($resource);
	
		switch ($resource) {

			case POP_RESOURCELOADER_CSS_SECTIONLAYOUT:

				$decorated[] = POP_RESOURCELOADER_CSS_LAYOUT;
				break;
		}

		return $decorated;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_SectionProcessors_CSSResourceLoaderProcessor();
