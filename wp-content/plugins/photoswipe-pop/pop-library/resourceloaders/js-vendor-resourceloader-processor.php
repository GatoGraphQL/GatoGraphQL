<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_EXTERNAL_PHOTOSWIPE', PoP_TemplateIDUtils::get_template_definition('external-photoswipe'));
define ('POP_RESOURCELOADER_EXTERNAL_PHOTOSWIPESKIN', PoP_TemplateIDUtils::get_template_definition('external-photoswipe-skin'));

class PhotoSwipe_PoP_VendorJSResourceLoaderProcessor extends PoP_VendorJSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_EXTERNAL_PHOTOSWIPE,
			POP_RESOURCELOADER_EXTERNAL_PHOTOSWIPESKIN,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_EXTERNAL_PHOTOSWIPE => 'photoswipe',
			POP_RESOURCELOADER_EXTERNAL_PHOTOSWIPESKIN => 'photoswipe-ui-default',
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
	
		return PHOTOSWIPEPOP_DIR.'/js/includes/cdn/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION;
	}
	
	function get_suffix($resource) {
	
		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_PHOTOSWIPE:
			case POP_RESOURCELOADER_EXTERNAL_PHOTOSWIPESKIN:

				return '.min.js';
		}

		return parent::get_suffix($resource);
	}
	
	function get_path($resource) {

		if (PoP_Frontend_ServerUtils::access_externalcdn_resources()) {

			switch ($resource) {

				case POP_RESOURCELOADER_EXTERNAL_PHOTOSWIPE:
				case POP_RESOURCELOADER_EXTERNAL_PHOTOSWIPESKIN:

					return 'https://cdnjs.cloudflare.com/ajax/libs/photoswipe/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION;
			}
		}

		return PHOTOSWIPEPOP_URL.'/js/includes/cdn/'.PHOTOSWIPEPOP_PHOTOSWIPE_VERSION;
	}
	
	function get_decorated_resources($resource) {

		$decorated = parent::get_decorated_resources($resource);
	
		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_PHOTOSWIPESKIN:

				$decorated[] = POP_RESOURCELOADER_EXTERNAL_PHOTOSWIPE;
				break;
		}

		return $decorated;
	}
	
	function get_dependencies($resource) {

		$dependencies = parent::get_dependencies($resource);
	
		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_PHOTOSWIPE:

				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_CSS_PHOTOSWIPE;
				break;
		}

		return $dependencies;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PhotoSwipe_PoP_VendorJSResourceLoaderProcessor();
