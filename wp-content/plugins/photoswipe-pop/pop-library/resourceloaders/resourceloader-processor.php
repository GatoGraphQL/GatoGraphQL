<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_PHOTOSWIPE', PoP_TemplateIDUtils::get_template_definition('photoswipe'));

class PhotoSwipe_PoP_ResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_PHOTOSWIPE,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_PHOTOSWIPE => 'photoswipe-pop',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {
	
		return PHOTOSWIPEPOP_VERSION;
	}
	
	function get_dir($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return PHOTOSWIPEPOP_DIR.'/js/'.$subpath.'libraries';
	}
	
	function get_asset_path($resource) {

		return PHOTOSWIPEPOP_DIR.'/js/libraries/'.$this->get_filename($resource).'.js';
	}
	
	function get_path($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return PHOTOSWIPEPOP_URI.'/js/'.$subpath.'libraries';
	}

	function get_jsobjects($resource) {

		$objects = array(
			POP_RESOURCELOADER_PHOTOSWIPE => array(
				'popPhotoSwipe',
			),
		);
		if ($object = $objects[$resource]) {

			return $object;
		}

		return parent::get_jsobjects($resource);
	}
	
	function get_dependencies($resource) {

		$dependencies = parent::get_dependencies($resource);
	
		switch ($resource) {

			case POP_RESOURCELOADER_PHOTOSWIPE:

				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_PHOTOSWIPE;
				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_PHOTOSWIPESKIN;
				break;
		}

		return $dependencies;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PhotoSwipe_PoP_ResourceLoaderProcessor();
