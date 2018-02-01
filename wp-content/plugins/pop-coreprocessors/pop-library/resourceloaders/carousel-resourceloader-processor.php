<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_BOOTSTRAPCAROUSEL', PoP_TemplateIDUtils::get_template_definition('bootstrap-carousel'));
define ('POP_RESOURCELOADER_BOOTSTRAPCAROUSELSTATIC', PoP_TemplateIDUtils::get_template_definition('bootstrap-carousel-static'));
define ('POP_RESOURCELOADER_BOOTSTRAPCAROUSELAUTOMATIC', PoP_TemplateIDUtils::get_template_definition('bootstrap-carousel-automatic'));

class PoP_CoreProcessors_CarouselResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_BOOTSTRAPCAROUSEL,
			POP_RESOURCELOADER_BOOTSTRAPCAROUSELSTATIC,
			POP_RESOURCELOADER_BOOTSTRAPCAROUSELAUTOMATIC,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_BOOTSTRAPCAROUSEL => 'bootstrap-carousel',
			POP_RESOURCELOADER_BOOTSTRAPCAROUSELSTATIC => 'bootstrap-carousel-static',
			POP_RESOURCELOADER_BOOTSTRAPCAROUSELAUTOMATIC => 'bootstrap-carousel-automatic',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {
	
		return POP_COREPROCESSORS_VERSION;
	}
	
	function get_dir($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POP_COREPROCESSORS_DIR.'/js/'.$subpath.'libraries/carousel';
	}
	
	function get_asset_path($resource) {
	
		return POP_COREPROCESSORS_DIR.'/js/libraries/carousel/'.$this->get_filename($resource).'.js';
	}
	
	function get_path($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POP_COREPROCESSORS_URL.'/js/'.$subpath.'libraries/carousel';
	}

	function get_jsobjects($resource) {

		$objects = array(
			POP_RESOURCELOADER_BOOTSTRAPCAROUSEL => array(
				'popBootstrapCarousel',
				'popBootstrapCarouselControls',
			),
			POP_RESOURCELOADER_BOOTSTRAPCAROUSELSTATIC => array(
				'popBootstrapCarouselStatic',
			),
			POP_RESOURCELOADER_BOOTSTRAPCAROUSELAUTOMATIC => array(
				'popBootstrapCarouselAutomatic',
			),
		);
		if ($object = $objects[$resource]) {

			return $object;
		}

		return parent::get_jsobjects($resource);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CoreProcessors_CarouselResourceLoaderProcessor();
