<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_EXTERNAL_CSS_FILEUPLOAD', PoP_TemplateIDUtils::get_template_definition('css-external-fileupload'));

class PoP_UserAvatar_VendorCSSResourceLoaderProcessor extends PoP_VendorCSSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_EXTERNAL_CSS_FILEUPLOAD,
		);
	}
	
	function get_filename($resource) {
	
		$use_cdn = PoP_Frontend_ServerUtils::access_externalcdn_resources();
		$filenames = array(
			POP_RESOURCELOADER_EXTERNAL_CSS_FILEUPLOAD => 'jquery.fileupload'.(!$use_cdn ? '.9.5.7' : ''),
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {

		return POP_USERAVATAR_VENDORRESOURCESVERSION;
	}
	
	function get_dir($resource) {

		return POP_USERAVATAR_DIR.'/css/includes/cdn';
	}
	
	function get_asset_path($resource) {

		if (PoP_Frontend_ServerUtils::access_externalcdn_resources()) {
			
			$filenames = array(
				POP_RESOURCELOADER_EXTERNAL_CSS_FILEUPLOAD => 'jquery.fileupload.9.5.7',
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
				POP_RESOURCELOADER_EXTERNAL_CSS_FILEUPLOAD => 'https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/css',
			);
			if ($path = $paths[$resource]) {
				return $path;
			}	
		}

		return POP_USERAVATAR_URL.'/css/includes/cdn';
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_UserAvatar_VendorCSSResourceLoaderProcessor();
