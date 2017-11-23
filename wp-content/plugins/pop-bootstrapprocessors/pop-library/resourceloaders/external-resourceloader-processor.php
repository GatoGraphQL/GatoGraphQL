<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_EXTERNAL_BOOTSTRAP', PoP_TemplateIDUtils::get_template_definition('external-bootstrap'));

class PoP_BootstrapProcessors_JSExternalResourceLoaderProcessor extends PoP_ExternalJSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_EXTERNAL_BOOTSTRAP,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_EXTERNAL_BOOTSTRAP => 'bootstrap'.(!PoP_Frontend_ServerUtils::access_externalcdn_resources() ? '.3.3.7' : ''),
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_dir($resource) {
	
		return POP_BOOTSTRAPPROCESSORS_DIR.'/js/includes/cdn';
	}
	
	function get_asset_path($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_EXTERNAL_BOOTSTRAP => 'bootstrap.3.3.7',
		);
		if ($filename = $filenames[$resource]) {
			return $this->get_dir($resource).'/'.$filename.$this->get_suffix($resource);
		}

		return parent::get_asset_path($resource);
	}
	
	function get_suffix($resource) {
	
		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_BOOTSTRAP:

				return '.min.js';
		}

		return parent::get_suffix($resource);
	}
	
	function get_path($resource) {

		if (PoP_Frontend_ServerUtils::access_externalcdn_resources()) {

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
new PoP_BootstrapProcessors_JSExternalResourceLoaderProcessor();
