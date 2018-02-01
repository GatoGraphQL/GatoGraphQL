<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_EXTERNAL_CSS_PHOTOSWIPE', PoP_TemplateIDUtils::get_template_definition('css-external-photoswipe'));
define ('POP_RESOURCELOADER_EXTERNAL_CSS_PHOTOSWIPESKIN', PoP_TemplateIDUtils::get_template_definition('css-external-photoswipeskin'));

class PhotoSwipe_PoP_VendorCSSResourceLoaderProcessor extends PoP_VendorCSSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_EXTERNAL_CSS_PHOTOSWIPE,
			POP_RESOURCELOADER_EXTERNAL_CSS_PHOTOSWIPESKIN,
		);
	}
	
	function get_filename($resource) {
	
		$use_cdn = PoP_Frontend_ServerUtils::access_externalcdn_resources();
		$filenames = array(
			POP_RESOURCELOADER_EXTERNAL_CSS_PHOTOSWIPE => 'photoswipe',
			POP_RESOURCELOADER_EXTERNAL_CSS_PHOTOSWIPESKIN => 'default-skin',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {

		return PHOTOSWIPEPOP_VENDORRESOURCESVERSION;
	}
	
	function get_dir($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_CSS_PHOTOSWIPE:

				return PHOTOSWIPEPOP_DIR.'/css/includes/cdn/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION;

			case POP_RESOURCELOADER_EXTERNAL_CSS_PHOTOSWIPESKIN:

				return PHOTOSWIPEPOP_DIR.'/css/includes/cdn/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION.'/default-skin';
		}

		return parent::get_dir($resource);
	}
	
	function get_asset_path($resource) {

		if (PoP_Frontend_ServerUtils::access_externalcdn_resources()) {
			
			switch ($resource) {

				case POP_RESOURCELOADER_EXTERNAL_CSS_PHOTOSWIPE:
				case POP_RESOURCELOADER_EXTERNAL_CSS_PHOTOSWIPESKIN:

					return $this->get_dir($resource).'/'.$this->get_filename($resource).$this->get_suffix($resource);
			}
		}

		return parent::get_asset_path($resource);
	}
	
	function get_path($resource) {

		if (PoP_Frontend_ServerUtils::access_externalcdn_resources()) {

			$paths = array(
				POP_RESOURCELOADER_EXTERNAL_CSS_PHOTOSWIPE => 'https://cdnjs.cloudflare.com/ajax/libs/photoswipe/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION,
				POP_RESOURCELOADER_EXTERNAL_CSS_PHOTOSWIPESKIN => 'https://cdnjs.cloudflare.com/ajax/libs/photoswipe/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION.'/default-skin/',
			);
			if ($path = $paths[$resource]) {
				return $path;
			}	
		}

		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_CSS_PHOTOSWIPE:

				return PHOTOSWIPEPOP_URL.'/css/includes/cdn/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION;

			case POP_RESOURCELOADER_EXTERNAL_CSS_PHOTOSWIPESKIN:

				return PHOTOSWIPEPOP_URL.'/css/includes/cdn/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION.'/default-skin';
		}

		return parent::get_path($resource);
	}
	
	function get_decorated_resources($resource) {

		$decorated = parent::get_decorated_resources($resource);
	
		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_CSS_PHOTOSWIPESKIN:

				$decorated[] = POP_RESOURCELOADER_EXTERNAL_CSS_PHOTOSWIPE;
				break;
		}

		return $decorated;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PhotoSwipe_PoP_VendorCSSResourceLoaderProcessor();
