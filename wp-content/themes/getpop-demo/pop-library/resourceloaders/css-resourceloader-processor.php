<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_CSS_GETPOPDEMOSTYLES', PoP_TemplateIDUtils::get_template_definition('css-getpopdemostyles'));
define ('POP_RESOURCELOADER_CSS_GETPOPDEMOBOOTSTRAP', PoP_TemplateIDUtils::get_template_definition('css-getpopdemobootstrap'));
define ('POP_RESOURCELOADER_CSS_GETPOPDEMOTYPEAHEADBOOTSTRAP', PoP_TemplateIDUtils::get_template_definition('css-getpopdemotypeaheadbootstrap'));

class GetPoPDemo_CSSResourceLoaderProcessor extends PoP_CSSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_CSS_GETPOPDEMOSTYLES,
			POP_RESOURCELOADER_CSS_GETPOPDEMOBOOTSTRAP,
			POP_RESOURCELOADER_CSS_GETPOPDEMOTYPEAHEADBOOTSTRAP,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_CSS_GETPOPDEMOSTYLES => 'style',
			POP_RESOURCELOADER_CSS_GETPOPDEMOBOOTSTRAP => 'custom.bootstrap',
			POP_RESOURCELOADER_CSS_GETPOPDEMOTYPEAHEADBOOTSTRAP => 'typeahead.js-bootstrap',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {
	
		return GETPOPDEMO_VERSION;
	}
	
	function get_dir($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return GETPOPDEMO_DIR.'/css/'.$subpath.'libraries';
	}
	
	// function get_asset_path($resource) {

	// 	return GETPOPDEMO_DIR.'/css/libraries/'.$this->get_filename($resource).'.css';
	// }
	
	function get_path($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return GETPOPDEMO_URL.'/css/'.$subpath.'libraries';
	}
	
	function get_decorated_resources($resource) {

		$decorated = parent::get_decorated_resources($resource);
	
		switch ($resource) {

			case POP_RESOURCELOADER_CSS_GETPOPDEMOSTYLES:

				$decorated[] = POP_RESOURCELOADER_CSS_THEMEWASSUP;
				break;

			case POP_RESOURCELOADER_CSS_GETPOPDEMOBOOTSTRAP:

				$decorated[] = POP_RESOURCELOADER_CSS_THEMEWASSUPBOOTSTRAP;
				break;

			case POP_RESOURCELOADER_CSS_GETPOPDEMOTYPEAHEADBOOTSTRAP:

				$decorated[] = POP_RESOURCELOADER_CSS_THEMEWASSUPTYPEAHEADBOOTSTRAP;
				break;
		}

		return $decorated;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GetPoPDemo_CSSResourceLoaderProcessor();
