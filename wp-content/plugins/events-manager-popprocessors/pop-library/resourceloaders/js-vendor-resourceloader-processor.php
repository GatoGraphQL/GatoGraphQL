<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_EXTERNAL_FULLCALENDAR', PoP_TemplateIDUtils::get_template_definition('external-fullcalendar'));
define ('POP_RESOURCELOADER_EXTERNAL_FULLCALENDARLOCALE', PoP_TemplateIDUtils::get_template_definition('external-fullcalendar-locale'));

class EM_PoPProcessors_VendorJSResourceLoaderProcessor extends PoP_VendorJSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_EXTERNAL_FULLCALENDAR,
			POP_RESOURCELOADER_EXTERNAL_FULLCALENDARLOCALE,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_EXTERNAL_FULLCALENDAR => 'fullcalendar'.(!PoP_Frontend_ServerUtils::access_externalcdn_resources() ? '.3.8.2' : ''),
			POP_RESOURCELOADER_EXTERNAL_FULLCALENDARLOCALE => get_em_qtransx_fullcalendar_locale_filename(),
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

		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_FULLCALENDARLOCALE:
				
				return EM_POPPROCESSORS_DIR.'/js/includes/cdn/fullcalendar.3.8.2-lang';
		}
	
		return EM_POPPROCESSORS_DIR.'/js/includes/cdn';
	}
	
	function get_asset_path($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_EXTERNAL_FULLCALENDAR => 'fullcalendar.3.8.2',
			POP_RESOURCELOADER_EXTERNAL_FULLCALENDARLOCALE => get_em_qtransx_fullcalendar_locale_filename(),
		);
		if ($filename = $filenames[$resource]) {
			return $this->get_dir($resource).'/'.$filename.$this->get_suffix($resource);
		}

		return parent::get_asset_path($resource);
	}
	
	function get_suffix($resource) {
	
		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_FULLCALENDAR:

				return '.min.js';
		
			case POP_RESOURCELOADER_EXTERNAL_FULLCALENDARLOCALE:
		
				return '.js';
		}

		return parent::get_suffix($resource);
	}
	
	function get_path($resource) {

		if (PoP_Frontend_ServerUtils::access_externalcdn_resources()) {

			switch ($resource) {

				case POP_RESOURCELOADER_EXTERNAL_FULLCALENDAR:

					return 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.2';
			
				case POP_RESOURCELOADER_EXTERNAL_FULLCALENDARLOCALE:

					return 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.2/lang';;
			}
		}

		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_FULLCALENDARLOCALE:
				
				return EM_POPPROCESSORS_URL.'/js/includes/cdn/fullcalendar.3.8.2-lang';
		}

		return EM_POPPROCESSORS_URL.'/js/includes/cdn';
	}
	
	// function can_bundle($resource) {

	// 	switch ($resource) {
			
	// 		case POP_RESOURCELOADER_EXTERNAL_FULLCALENDARLOCALE:

	// 			return false;
	// 	}

	// 	return parent::can_bundle($resource);
	// }
	
	function get_dependencies($resource) {

		$dependencies = parent::get_dependencies($resource);

		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_FULLCALENDAR:

				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_MOMENT;
				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_CSS_FULLCALENDAR;
				break;
		}

		return $dependencies;
	}
	
	function get_decorated_resources($resource) {

		$decorated = parent::get_decorated_resources($resource);
	
		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_FULLCALENDARLOCALE:

				// // Add Locale file, if applicable
				// if (get_em_qtransx_fullcalendar_locale_filename()) {

				$decorated[] = POP_RESOURCELOADER_EXTERNAL_FULLCALENDAR;
				// }
				break;
		}

		return $decorated;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new EM_PoPProcessors_VendorJSResourceLoaderProcessor();
