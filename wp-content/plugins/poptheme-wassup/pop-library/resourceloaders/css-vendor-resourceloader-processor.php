<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_EXTERNAL_CSS_FONTAWESOME', PoP_TemplateIDUtils::get_template_definition('css-external-bootstrapmultiselect'));

class PoPTheme_Wassup_VendorCSSResourceLoaderProcessor extends PoP_VendorCSSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_EXTERNAL_CSS_FONTAWESOME,
		);
	}
	
	function get_filename($resource) {
	
		$use_cdn = PoP_Frontend_ServerUtils::access_externalcdn_resources();
		$filenames = array(
			POP_RESOURCELOADER_EXTERNAL_CSS_FONTAWESOME => 'font-awesome'.(!$use_cdn ? '.4.7.0' : ''),
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {

		return POPTHEME_WASSUP_VENDORRESOURCESVERSION;
	}
	
	function get_dir($resource) {

		return POPTHEME_WASSUP_DIR.'/css/includes/cdn';
	}
	
	function get_referenced_files($resource) {

		$referenced_files = parent::get_referenced_files($resource);

		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_CSS_FONTAWESOME:

				$referenced_files[] = '../fonts/fontawesome-webfont.eot';
				$referenced_files[] = '../fonts/fontawesome-webfont.woff2';
				$referenced_files[] = '../fonts/fontawesome-webfont.woff';
				$referenced_files[] = '../fonts/fontawesome-webfont.ttf';
				$referenced_files[] = '../fonts/fontawesome-webfont.svg';
				break;
		}

		return $referenced_files;
	}
	
	function get_asset_path($resource) {

		if (PoP_Frontend_ServerUtils::access_externalcdn_resources()) {

			$filenames = array(
				POP_RESOURCELOADER_EXTERNAL_CSS_FONTAWESOME => 'font-awesome.4.7.0',
			);
			if ($filename = $filenames[$resource]) {
				return $this->get_dir($resource).'/'.$filename.$this->get_suffix($resource);
			}
		}

		return parent::get_asset_path($resource);
	}
	
	function get_path($resource) {

		if (PoP_Frontend_ServerUtils::access_externalcdn_resources()) {

			$paths = array(
				POP_RESOURCELOADER_EXTERNAL_CSS_FONTAWESOME => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/',
			);
			if ($path = $paths[$resource]) {
				return $path;
			}	
		}

		return POPTHEME_WASSUP_URL.'/css/includes/cdn';
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_VendorCSSResourceLoaderProcessor();
