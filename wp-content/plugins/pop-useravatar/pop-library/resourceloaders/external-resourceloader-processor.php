<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_EXTERNAL_IFRAMETRANSPORT', PoP_TemplateIDUtils::get_template_definition('external-iframe-transport'));
define ('POP_RESOURCELOADER_EXTERNAL_FILEUPLOAD', PoP_TemplateIDUtils::get_template_definition('external-fileupload'));
define ('POP_RESOURCELOADER_EXTERNAL_FILEUPLOADUI', PoP_TemplateIDUtils::get_template_definition('external-fileupload-ui'));
define ('POP_RESOURCELOADER_EXTERNAL_FILEUPLOADPROCESS', PoP_TemplateIDUtils::get_template_definition('external-fileupload-process'));
define ('POP_RESOURCELOADER_EXTERNAL_FILEUPLOADVALIDATE', PoP_TemplateIDUtils::get_template_definition('external-fileupload-validate'));

class PoP_UserAvatar_JSExternalResourceLoaderProcessor extends PoP_ExternalJSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_EXTERNAL_IFRAMETRANSPORT,
			POP_RESOURCELOADER_EXTERNAL_FILEUPLOAD,
			POP_RESOURCELOADER_EXTERNAL_FILEUPLOADUI,
			POP_RESOURCELOADER_EXTERNAL_FILEUPLOADPROCESS,
			POP_RESOURCELOADER_EXTERNAL_FILEUPLOADVALIDATE,
		);
	}
	
	function get_filename($resource) {
	
		$version = (!PoP_Frontend_ServerUtils::access_externalcdn_resources() ? '.9.5.7' : '');
		$filenames = array(
			POP_RESOURCELOADER_EXTERNAL_IFRAMETRANSPORT => 'jquery.iframe-transport'.$version,
			POP_RESOURCELOADER_EXTERNAL_FILEUPLOAD => 'jquery.fileupload'.$version,
			POP_RESOURCELOADER_EXTERNAL_FILEUPLOADUI => 'jquery.fileupload-ui'.$version,
			POP_RESOURCELOADER_EXTERNAL_FILEUPLOADPROCESS => 'jquery.fileupload-process'.$version,
			POP_RESOURCELOADER_EXTERNAL_FILEUPLOADVALIDATE => 'jquery.fileupload-validate'.$version,
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_dir($resource) {
	
		return POP_USERAVATAR_DIR.'/js/includes/cdn';
	}
	
	function get_asset_path($resource) {
	
		$version = '.9.5.7';
		$filenames = array(
			POP_RESOURCELOADER_EXTERNAL_IFRAMETRANSPORT => 'jquery.iframe-transport'.$version,
			POP_RESOURCELOADER_EXTERNAL_FILEUPLOAD => 'jquery.fileupload'.$version,
			POP_RESOURCELOADER_EXTERNAL_FILEUPLOADUI => 'jquery.fileupload-ui'.$version,
			POP_RESOURCELOADER_EXTERNAL_FILEUPLOADPROCESS => 'jquery.fileupload-process'.$version,
			POP_RESOURCELOADER_EXTERNAL_FILEUPLOADVALIDATE => 'jquery.fileupload-validate'.$version,
		);
		if ($filename = $filenames[$resource]) {
			return $this->get_dir($resource).'/'.$filename.'.min.js';
		}

		return parent::get_asset_path($resource);
	}
	
	function get_suffix($resource) {

		switch ($resource) {
			
			case POP_RESOURCELOADER_EXTERNAL_IFRAMETRANSPORT:
			case POP_RESOURCELOADER_EXTERNAL_FILEUPLOAD:
			case POP_RESOURCELOADER_EXTERNAL_FILEUPLOADUI:
			case POP_RESOURCELOADER_EXTERNAL_FILEUPLOADPROCESS:
			case POP_RESOURCELOADER_EXTERNAL_FILEUPLOADVALIDATE:

				return '.min.js';
		}

		return parent::get_suffix($resource);
	}
	
	function get_path($resource) {

		if (PoP_Frontend_ServerUtils::access_externalcdn_resources()) {

			switch ($resource) {

				case POP_RESOURCELOADER_EXTERNAL_IFRAMETRANSPORT:
				case POP_RESOURCELOADER_EXTERNAL_FILEUPLOAD:
				case POP_RESOURCELOADER_EXTERNAL_FILEUPLOADUI:
				case POP_RESOURCELOADER_EXTERNAL_FILEUPLOADPROCESS:
				case POP_RESOURCELOADER_EXTERNAL_FILEUPLOADVALIDATE:

					return 'https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7';
			}
		}

		return POP_USERAVATAR_URI.'/js/includes/cdn';
	}
	
	function get_dependencies($resource) {

		$dependencies = parent::get_dependencies($resource);
	
		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_FILEUPLOAD:

				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_IFRAMETRANSPORT;
				break;

			// Make sure the UI is loaded first, or otherwise we get a JS error
			case POP_RESOURCELOADER_EXTERNAL_FILEUPLOADPROCESS:
			case POP_RESOURCELOADER_EXTERNAL_FILEUPLOADVALIDATE:

				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_FILEUPLOADUI;
				break;
		}

		return $dependencies;
	}
	
	function async_load_in_order($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_IFRAMETRANSPORT:
			case POP_RESOURCELOADER_EXTERNAL_FILEUPLOAD:
			case POP_RESOURCELOADER_EXTERNAL_FILEUPLOADUI:
			case POP_RESOURCELOADER_EXTERNAL_FILEUPLOADPROCESS:
			case POP_RESOURCELOADER_EXTERNAL_FILEUPLOADVALIDATE:

				return true;
		}

		return parent::async_load_in_order($resource);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_UserAvatar_JSExternalResourceLoaderProcessor();
