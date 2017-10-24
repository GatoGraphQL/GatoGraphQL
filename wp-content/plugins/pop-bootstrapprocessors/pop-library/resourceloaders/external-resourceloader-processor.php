<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_EXTERNAL_BOOTSTRAP', PoP_ServerUtils::get_template_definition('external-bootstrap'));

class PoP_BootstrapProcessors_ExternalResourceLoaderProcessor extends PoP_ResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_EXTERNAL_BOOTSTRAP,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_EXTERNAL_BOOTSTRAP => 'bootstrap'.(!PoP_Frontend_ServerUtils::use_cdn_resources() ? '.3.3.7' : ''),
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_suffix($resource) {
	
		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_BOOTSTRAP:

				return '.min.js';
		}

		return parent::get_suffix($resource);
	}
	
	function get_path($resource) {

		if (PoP_Frontend_ServerUtils::use_cdn_resources()) {

			switch ($resource) {

				case POP_RESOURCELOADER_EXTERNAL_BOOTSTRAP:

					return 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/';
			}
		}

		return POP_BOOTSTRAPPROCESSORS_URI.'/js/includes/cdn';
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_BootstrapProcessors_ExternalResourceLoaderProcessor();
