<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_CDN', PoP_TemplateIDUtils::get_template_definition('cdn'));
// define ('POP_RESOURCELOADER_CDNTHUMBPRINTS', PoP_TemplateIDUtils::get_template_definition('cdn-thumbprints'));

class PoP_CDNCore_JSResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_CDN,
			// POP_RESOURCELOADER_CDNTHUMBPRINTS,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_CDN => 'cdn',
			// POP_RESOURCELOADER_CDNTHUMBPRINTS => 'cdn-thumbprints',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {
	
		return POP_CDNCORE_VERSION;
	}
	
	function get_dir($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POP_CDNCORE_DIR.'/js/'.$subpath.'libraries';
	}
	
	function get_asset_path($resource) {

		return POP_CDNCORE_DIR.'/js/libraries/'.$this->get_filename($resource).'.js';
	}
	
	function get_path($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POP_CDNCORE_URL.'/js/'.$subpath.'libraries';
	}

	function get_jsobjects($resource) {

		$objects = array(
			POP_RESOURCELOADER_CDN => array(
				'popCDN',
			),
			// POP_RESOURCELOADER_CDNTHUMBPRINTS => array(
			// 	'popCDNThumbprints',
			// ),
		);
		if ($object = $objects[$resource]) {

			return $object;
		}

		return parent::get_jsobjects($resource);
	}
	
	function get_dependencies($resource) {

		$dependencies = parent::get_dependencies($resource);
	
		switch ($resource) {

			case POP_RESOURCELOADER_CDN:

				// All templates depend on the handlebars runtime. Allow plugins to add their own dependencies
				if ($cdn_dependencies = apply_filters(
					'PoP_CDNCore_ResourceLoaderProcessor:dependencies',
					array(
						// POP_RESOURCELOADER_CDNTHUMBPRINTS,
					)
				)) {
					$dependencies = array_merge(
						$dependencies,
						$cdn_dependencies
					);
				}
				break;
		}

		return $dependencies;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CDNCore_JSResourceLoaderProcessor();
