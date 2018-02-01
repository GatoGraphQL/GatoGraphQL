<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_EXTERNAL_CSS_FULLCALENDAR', PoP_TemplateIDUtils::get_template_definition('css-external-fullcalendar'));

class EM_PoPProcessors_VendorCSSResourceLoaderProcessor extends PoP_VendorCSSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_EXTERNAL_CSS_FULLCALENDAR,
		);
	}
	
	function get_filename($resource) {
	
		$use_cdn = PoP_Frontend_ServerUtils::access_externalcdn_resources();
		$filenames = array(
			POP_RESOURCELOADER_EXTERNAL_CSS_FULLCALENDAR => 'fullcalendar'.(!$use_cdn ? '.2.9.1' : ''),
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {

		return EM_POPPROCESSORS_VENDORRESOURCESVERSION;
	}
	
	function get_dir($resource) {

		return EM_POPPROCESSORS_DIR.'/css/includes/cdn';
	}
	
	function get_asset_path($resource) {

		if (PoP_Frontend_ServerUtils::access_externalcdn_resources()) {
			
			$filenames = array(
				POP_RESOURCELOADER_EXTERNAL_CSS_FULLCALENDAR => 'fullcalendar.2.9.1',
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
				POP_RESOURCELOADER_EXTERNAL_CSS_FULLCALENDAR => 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.9.1',
			);
			if ($path = $paths[$resource]) {
				return $path;
			}	
		}

		return EM_POPPROCESSORS_URL.'/css/includes/cdn';
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new EM_PoPProcessors_VendorCSSResourceLoaderProcessor();
