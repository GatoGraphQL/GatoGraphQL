<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_EXTERNAL_CSS_DATERANGEPICKER', PoP_TemplateIDUtils::get_template_definition('css-external-daterangepicker'));
define ('POP_RESOURCELOADER_EXTERNAL_CSS_PERFECTSCROLLBAR', PoP_TemplateIDUtils::get_template_definition('css-external-perfectscrollbar'));
define ('POP_RESOURCELOADER_EXTERNAL_CSS_DYNAMICMAXHEIGHT', PoP_TemplateIDUtils::get_template_definition('css-external-dynamicmaxheight'));
define ('POP_RESOURCELOADER_EXTERNAL_CSS_BOOTSTRAPMULTISELECT', PoP_TemplateIDUtils::get_template_definition('css-external-bootstrapmultiselect'));

class PoP_CoreProcessors_VendorCSSResourceLoaderProcessor extends PoP_VendorCSSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_EXTERNAL_CSS_DATERANGEPICKER,
			POP_RESOURCELOADER_EXTERNAL_CSS_PERFECTSCROLLBAR,
			POP_RESOURCELOADER_EXTERNAL_CSS_DYNAMICMAXHEIGHT,
			POP_RESOURCELOADER_EXTERNAL_CSS_BOOTSTRAPMULTISELECT,
		);
	}
	
	function get_filename($resource) {
	
		$use_cdn = PoP_Frontend_ServerUtils::access_externalcdn_resources();
		$filenames = array(
			POP_RESOURCELOADER_EXTERNAL_CSS_DATERANGEPICKER => 'daterangepicker'.(!$use_cdn ? '.2.1.24' : ''),
			POP_RESOURCELOADER_EXTERNAL_CSS_PERFECTSCROLLBAR => 'perfect-scrollbar'.(!$use_cdn ? '.0.6.5' : ''),
			POP_RESOURCELOADER_EXTERNAL_CSS_DYNAMICMAXHEIGHT => 'jquery.dynamicmaxheight',
			POP_RESOURCELOADER_EXTERNAL_CSS_BOOTSTRAPMULTISELECT => 'bootstrap-multiselect.0.9.13',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}

	protected function always_minified($resource) {

		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_CSS_DYNAMICMAXHEIGHT:
			case POP_RESOURCELOADER_EXTERNAL_CSS_BOOTSTRAPMULTISELECT:
				
				return false;
		}
	
		return parent::always_minified($resource);
	}
	
	function get_version($resource) {

		return POP_COREPROCESSORS_VENDORRESOURCESVERSION;
	}
	
	function get_dir($resource) {

		switch ($resource) {
			
			case POP_RESOURCELOADER_EXTERNAL_CSS_DYNAMICMAXHEIGHT:
			case POP_RESOURCELOADER_EXTERNAL_CSS_BOOTSTRAPMULTISELECT:

				$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
				return POP_COREPROCESSORS_DIR.'/css/'.$subpath.'includes';
		}
	
		return POP_COREPROCESSORS_DIR.'/css/includes/cdn';
	}
	
	function get_asset_path($resource) {

		if (PoP_Frontend_ServerUtils::access_externalcdn_resources()) {
			
			switch ($resource) {
				
				case POP_RESOURCELOADER_EXTERNAL_CSS_DYNAMICMAXHEIGHT:
				case POP_RESOURCELOADER_EXTERNAL_CSS_BOOTSTRAPMULTISELECT:
					
					return POP_COREPROCESSORS_DIR.'/css/includes/'.$this->get_filename($resource).'.css';
			}
		
			$filenames = array(
				POP_RESOURCELOADER_EXTERNAL_CSS_DATERANGEPICKER => 'daterangepicker.2.1.24',
				POP_RESOURCELOADER_EXTERNAL_CSS_PERFECTSCROLLBAR => 'perfect-scrollbar.0.6.5',
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
				POP_RESOURCELOADER_EXTERNAL_CSS_DATERANGEPICKER => 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.24',
				POP_RESOURCELOADER_EXTERNAL_CSS_PERFECTSCROLLBAR => 'https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.6.5/css',
			);
			if ($path = $paths[$resource]) {
				return $path;
			}	
		}

		switch ($resource) {
			
			case POP_RESOURCELOADER_EXTERNAL_CSS_DYNAMICMAXHEIGHT:
			case POP_RESOURCELOADER_EXTERNAL_CSS_BOOTSTRAPMULTISELECT:

				$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
				return POP_COREPROCESSORS_URL.'/css/'.$subpath.'includes';
		}

		return POP_COREPROCESSORS_URL.'/css/includes/cdn';
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CoreProcessors_VendorCSSResourceLoaderProcessor();
