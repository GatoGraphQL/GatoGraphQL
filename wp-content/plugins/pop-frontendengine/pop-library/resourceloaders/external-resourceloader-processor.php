<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_EXTERNAL_HANDLEBARS', PoP_TemplateIDUtils::get_template_definition('external-handlebars'));

class PoP_FrontEnd_ExternalResourceLoaderProcessor extends PoP_ResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_EXTERNAL_HANDLEBARS,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_EXTERNAL_HANDLEBARS => 'handlebars.runtime'.(!PoP_Frontend_ServerUtils::use_cdn_resources() ? '.4.0.10' : ''),
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_suffix($resource) {
	
		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_HANDLEBARS:

				return '.min.js';
		}

		return parent::get_suffix($resource);
	}
	
	function get_path($resource) {

		if (PoP_Frontend_ServerUtils::use_cdn_resources()) {

			switch ($resource) {

				case POP_RESOURCELOADER_EXTERNAL_HANDLEBARS:

					return 'https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.10';
			}
		}

		return POP_FRONTENDENGINE_URI.'/js/includes/cdn';
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_FrontEnd_ExternalResourceLoaderProcessor();
