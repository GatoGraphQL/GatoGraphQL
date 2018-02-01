<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_EXTERNAL_CSS_BOOTSTRAP', PoP_TemplateIDUtils::get_template_definition('css-external-bootstrap'));

class PoP_BootstrapProcessors_VendorCSSResourceLoaderProcessor extends PoP_VendorCSSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_EXTERNAL_CSS_BOOTSTRAP,
		);
	}
	
	function get_handle($resource) {
	
		// Other resources depend on bootstrap being called "bootstrap"
		$handles = array(
			POP_RESOURCELOADER_EXTERNAL_CSS_BOOTSTRAP => 'bootstrap',
		);
		if ($handle = $handles[$resource]) {
			return $handle;
		}

		return parent::get_handle($resource);
	}
	
	function get_filename($resource) {
	
		$use_cdn = PoP_Frontend_ServerUtils::access_externalcdn_resources();
		$filenames = array(
			POP_RESOURCELOADER_EXTERNAL_CSS_BOOTSTRAP => 'bootstrap'.(!$use_cdn ? '.3.3.7' : ''),
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {

		return POP_BOOTSTRAPPROCESSORS_VENDORRESOURCESVERSION;
	}
	
	function get_dir($resource) {
	
		return POP_BOOTSTRAPPROCESSORS_DIR.'/css/includes/cdn';
	}
	
	function get_referenced_files($resource) {

		$referenced_files = parent::get_referenced_files($resource);

		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_CSS_BOOTSTRAP:

				$referenced_files[] = '../fonts/glyphicons-halflings-regular.eot';
				$referenced_files[] = '../fonts/glyphicons-halflings-regular.woff2';
				$referenced_files[] = '../fonts/glyphicons-halflings-regular.woff';
				$referenced_files[] = '../fonts/glyphicons-halflings-regular.ttf';
				$referenced_files[] = '../fonts/glyphicons-halflings-regular.svg';
				break;
		}

		return $referenced_files;
	}
	
	function get_asset_path($resource) {

		if (PoP_Frontend_ServerUtils::access_externalcdn_resources()) {
			
			$filenames = array(
				POP_RESOURCELOADER_EXTERNAL_CSS_BOOTSTRAP => 'bootstrap.3.3.7',
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
				POP_RESOURCELOADER_EXTERNAL_CSS_BOOTSTRAP => 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css',
			);
			if ($path = $paths[$resource]) {
				return $path;
			}	
		}

		return POP_BOOTSTRAPPROCESSORS_URL.'/css/includes/cdn';
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_BootstrapProcessors_VendorCSSResourceLoaderProcessor();
