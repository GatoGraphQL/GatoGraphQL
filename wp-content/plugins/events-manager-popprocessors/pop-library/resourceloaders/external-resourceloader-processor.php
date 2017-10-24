<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_EXTERNAL_FULLCALENDAR', PoP_ServerUtils::get_template_definition('external-fullcalendar'));

class EM_PoPProcessors_ExternalResourceLoaderProcessor extends PoP_ResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_EXTERNAL_FULLCALENDAR,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_EXTERNAL_FULLCALENDAR => 'fullcalendar'.(!PoP_Frontend_ServerUtils::use_cdn_resources() ? '.2.9.1' : ''),
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_suffix($resource) {
	
		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_FULLCALENDAR:

				return '.min.js';
		}

		return parent::get_suffix($resource);
	}
	
	function get_path($resource) {

		if (PoP_Frontend_ServerUtils::use_cdn_resources()) {

			switch ($resource) {

				case POP_RESOURCELOADER_EXTERNAL_FULLCALENDAR:

					return 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.9.1';
			}
		}

		return EM_POPPROCESSORS_URI.'/js/includes/cdn';
	}
	
	function get_dependencies($resource) {

		$dependencies = array();

		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_FULLCALENDAR:

				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_MOMENT;
				break;
		}

		return $dependencies;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new EM_PoPProcessors_ExternalResourceLoaderProcessor();
