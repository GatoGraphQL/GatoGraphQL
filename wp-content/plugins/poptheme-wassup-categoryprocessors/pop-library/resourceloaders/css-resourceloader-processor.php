<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_CSS_CATEGORYLAYOUT', PoP_TemplateIDUtils::get_template_definition('css-categorylayout'));

class PoPTheme_Wassup_CategoryProcessors_CSSResourceLoaderProcessor extends PoP_CSSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_CSS_CATEGORYLAYOUT,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_CSS_CATEGORYLAYOUT => 'category-layout',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {
	
		return POPTHEME_WASSUP_CATEGORYPROCESSORS_VERSION;
	}
	
	function get_dir($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POPTHEME_WASSUP_CATEGORYPROCESSORS_DIR.'/css/'.$subpath.'libraries';
	}
	
	// function get_asset_path($resource) {

	// 	return POPTHEME_WASSUP_CATEGORYPROCESSORS_DIR.'/css/libraries/'.$this->get_filename($resource).'.css';
	// }
	
	function get_path($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POPTHEME_WASSUP_CATEGORYPROCESSORS_URL.'/css/'.$subpath.'libraries';
	}
	
	function get_decorated_resources($resource) {

		$decorated = parent::get_decorated_resources($resource);
	
		switch ($resource) {

			case POP_RESOURCELOADER_CSS_CATEGORYLAYOUT:

				$decorated[] = POP_RESOURCELOADER_CSS_LAYOUT;
				break;
		}

		return $decorated;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_CategoryProcessors_CSSResourceLoaderProcessor();
