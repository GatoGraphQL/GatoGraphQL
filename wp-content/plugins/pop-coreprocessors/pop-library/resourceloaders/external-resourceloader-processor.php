<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_EXTERNAL_GOOGLEMAPS', PoP_ServerUtils::get_template_definition('external-googlemaps'));
define ('POP_RESOURCELOADER_EXTERNAL_GMAPS', PoP_ServerUtils::get_template_definition('external-gmaps'));
define ('POP_RESOURCELOADER_EXTERNAL_PERFECTSCROLLBAR', PoP_ServerUtils::get_template_definition('external-perfectscrollbar'));
define ('POP_RESOURCELOADER_EXTERNAL_JQUERYCOOKIE', PoP_ServerUtils::get_template_definition('external-jquerycookie'));
define ('POP_RESOURCELOADER_EXTERNAL_MOMENT', PoP_ServerUtils::get_template_definition('external-moment'));
define ('POP_RESOURCELOADER_EXTERNAL_WAYPOINTS', PoP_ServerUtils::get_template_definition('external-waypoints'));
define ('POP_RESOURCELOADER_EXTERNAL_TYPEAHEAD', PoP_ServerUtils::get_template_definition('external-typeahead'));
define ('POP_RESOURCELOADER_EXTERNAL_DATERANGEPICKER', PoP_ServerUtils::get_template_definition('external-daterangepicker'));
define ('POP_RESOURCELOADER_EXTERNAL_BOOTSTRAPMULTISELECT', PoP_ServerUtils::get_template_definition('external-bootstrapmultiselect'));
define ('POP_RESOURCELOADER_EXTERNAL_FULLSCREEN', PoP_ServerUtils::get_template_definition('external-fullscreen'));
define ('POP_RESOURCELOADER_EXTERNAL_DYNAMICMAXHEIGHT', PoP_ServerUtils::get_template_definition('external-dynamicmaxheight'));

class PoP_CoreProcessors_ExternalResourceLoaderProcessor extends PoP_ResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_EXTERNAL_GOOGLEMAPS,
			POP_RESOURCELOADER_EXTERNAL_GMAPS,
			POP_RESOURCELOADER_EXTERNAL_PERFECTSCROLLBAR,
			POP_RESOURCELOADER_EXTERNAL_JQUERYCOOKIE,
			POP_RESOURCELOADER_EXTERNAL_MOMENT,
			POP_RESOURCELOADER_EXTERNAL_WAYPOINTS,
			POP_RESOURCELOADER_EXTERNAL_TYPEAHEAD,
			POP_RESOURCELOADER_EXTERNAL_DATERANGEPICKER,
			POP_RESOURCELOADER_EXTERNAL_BOOTSTRAPMULTISELECT,
			POP_RESOURCELOADER_EXTERNAL_FULLSCREEN,
			POP_RESOURCELOADER_EXTERNAL_DYNAMICMAXHEIGHT,
		);
	}
	
	function get_file_url($resource) {
	
		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_GOOGLEMAPS:

				return get_googlemaps_url();
		}

		return parent::get_file_url($resource);
	}
	
	function get_filename($resource) {
	
		$use_cdn = PoP_Frontend_ServerUtils::use_cdn_resources();
		$filenames = array(
			POP_RESOURCELOADER_EXTERNAL_GMAPS => 'gmaps'.(!$use_cdn ? '.0.4.24' : ''),
			POP_RESOURCELOADER_EXTERNAL_PERFECTSCROLLBAR => 'perfect-scrollbar.jquery'.(!$use_cdn ? '.0.6.11' : ''),
			POP_RESOURCELOADER_EXTERNAL_JQUERYCOOKIE => 'jquery.cookie'.(!$use_cdn ? '.1.4.1' : ''),
			POP_RESOURCELOADER_EXTERNAL_MOMENT => 'moment'.(!$use_cdn ? '.2.15.1' : ''),
			POP_RESOURCELOADER_EXTERNAL_WAYPOINTS => 'jquery.waypoints'.(!$use_cdn ? '.4.0.1' : ''),
			POP_RESOURCELOADER_EXTERNAL_TYPEAHEAD => 'typeahead.bundle'.(!$use_cdn ? '.0.11.1' : ''),
			POP_RESOURCELOADER_EXTERNAL_DATERANGEPICKER => 'daterangepicker'.(!$use_cdn ? '.2.1.24' : ''),
			POP_RESOURCELOADER_EXTERNAL_BOOTSTRAPMULTISELECT => 'bootstrap-multiselect'.(!$use_cdn ? '.0.9.13' : ''),
			POP_RESOURCELOADER_EXTERNAL_FULLSCREEN => 'jquery.fullscreen',
			POP_RESOURCELOADER_EXTERNAL_DYNAMICMAXHEIGHT => 'jquery.dynamicmaxheight',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_suffix($resource) {
	
		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_GMAPS:
			case POP_RESOURCELOADER_EXTERNAL_PERFECTSCROLLBAR:
			case POP_RESOURCELOADER_EXTERNAL_JQUERYCOOKIE:
			case POP_RESOURCELOADER_EXTERNAL_MOMENT:
			case POP_RESOURCELOADER_EXTERNAL_WAYPOINTS:
			case POP_RESOURCELOADER_EXTERNAL_TYPEAHEAD:
			case POP_RESOURCELOADER_EXTERNAL_DATERANGEPICKER:
			case POP_RESOURCELOADER_EXTERNAL_BOOTSTRAPMULTISELECT:
			case POP_RESOURCELOADER_EXTERNAL_DYNAMICMAXHEIGHT:

				return '.min.js';

			case POP_RESOURCELOADER_EXTERNAL_FULLSCREEN:

				return '-min.js';
		}

		return parent::get_suffix($resource);
	}
	
	function get_path($resource) {

		if (PoP_Frontend_ServerUtils::use_cdn_resources()) {

			$paths = array(
				POP_RESOURCELOADER_EXTERNAL_GMAPS => 'https://cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.24',
				POP_RESOURCELOADER_EXTERNAL_PERFECTSCROLLBAR => 'https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.6.11/js/min',
				POP_RESOURCELOADER_EXTERNAL_JQUERYCOOKIE => 'https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1',
				POP_RESOURCELOADER_EXTERNAL_MOMENT => 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1',
				POP_RESOURCELOADER_EXTERNAL_WAYPOINTS => 'https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1',
				POP_RESOURCELOADER_EXTERNAL_TYPEAHEAD => 'https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1',
				POP_RESOURCELOADER_EXTERNAL_DATERANGEPICKER => 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.24',
				POP_RESOURCELOADER_EXTERNAL_BOOTSTRAPMULTISELECT => 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js',
				POP_RESOURCELOADER_EXTERNAL_FULLSCREEN => 'https://cdnjs.cloudflare.com/ajax/libs/jquery-fullscreen-plugin/1.1.4',
			);
			if ($path = $paths[$resource]) {
				return $path;
			}	
		}

		// Scripts not under a CDN
		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_DYNAMICMAXHEIGHT:
				
				return POP_COREPROCESSORS_URI.'/js/includes';
		}

		return POP_COREPROCESSORS_URI.'/js/includes/cdn';
	}
	
	function get_dependencies($resource) {

		$dependencies = array();

		switch ($resource) {

			case POP_RESOURCELOADER_EXTERNAL_GMAPS:

				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_GOOGLEMAPS;
				break;

			case POP_RESOURCELOADER_EXTERNAL_BOOTSTRAPMULTISELECT:
			case POP_RESOURCELOADER_EXTERNAL_DATERANGEPICKER:

				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_BOOTSTRAP;
				break;
		}

		return $dependencies;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CoreProcessors_ExternalResourceLoaderProcessor();
