<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_EXTERNAL_CSS_DATERANGEPICKER', PoP_TemplateIDUtils::get_template_definition('css-external-daterangepicker'));

class PoP_CoreProcessors_CSSExternalResourceLoaderProcessor extends PoP_ExternalCSSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_EXTERNAL_CSS_DATERANGEPICKER,
		);
	}
	
	function get_filename($resource) {
	
		$use_cdn = PoP_Frontend_ServerUtils::access_externalcdn_resources();
		$filenames = array(
			POP_RESOURCELOADER_EXTERNAL_CSS_DATERANGEPICKER => 'daterangepicker'.(!$use_cdn ? '.2.1.24' : ''),
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_dir($resource) {
	
		return POP_COREPROCESSORS_DIR.'/css/includes/cdn';
	}
	
	function get_asset_path($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_EXTERNAL_CSS_DATERANGEPICKER => 'daterangepicker.2.1.24',
		);
		if ($filename = $filenames[$resource]) {
			return $this->get_dir($resource).'/'.$filename.$this->get_suffix($resource);
		}

		return parent::get_asset_path($resource);
	}
	
	function get_suffix($resource) {
	
		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_CSS_DATERANGEPICKER:

				return '.min.css';
		}

		return parent::get_suffix($resource);
	}
	
	function get_path($resource) {

		if (PoP_Frontend_ServerUtils::access_externalcdn_resources()) {

			$paths = array(
				POP_RESOURCELOADER_EXTERNAL_CSS_DATERANGEPICKER => 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.24',
			);
			if ($path = $paths[$resource]) {
				return $path;
			}	
		}

		return POP_COREPROCESSORS_URI.'/css/includes/cdn';
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CoreProcessors_CSSExternalResourceLoaderProcessor();
