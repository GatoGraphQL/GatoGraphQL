<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Core (reusable) functions of the website
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_Frontend_ConversionManager {

	public $class_to_styles, $initialized;

	function __construct() {

		$this->class_to_styles = array();
		$this->initialized = false;
	}

	function init() {

		// Allows lazy init
		if (!$this->initialized) {

			$this->initialized = true;

			// Get the inner variable from the cache, if it exists
			global $pop_frontend_filejsonstorage, $pop_frontend_conversiongenerator;
			$this->class_to_styles = $pop_frontend_filejsonstorage->get($pop_frontend_conversiongenerator->get_filepath());
		}
	}

	public function get_styles_from_classes($classes) {

		// Lazy init
		$this->init();
		
		// Add a dot to all classes, to convert them into a CSS selector
		// $classes = array_map(array('PoP_Frontend_ConversionManager', 'get_class_selector'), $classes);
		$classes = array_map(array($this, 'get_class_selector'), $classes);
	
		// Obtain the styles
		// $intersected = array_intersect(PoP_Frontend_ConversionUtils::get_classes(), $classes);
		$intersected = array_intersect($this->get_classes(), $classes);
		
		// return array_map(array('PoP_Frontend_ConversionUtils', 'convert'), $intersected);
		return array_map(array($this, 'convert'), $intersected);
	}

	public function get_classes() {

		return array_keys($this->class_to_styles);
	}

	public function convert($class) {

		return $this->class_to_styles[$class];
	}

	public function get_class_selector($classname) {

		return '.'.$classname;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_frontend_conversionmanager;
$pop_frontend_conversionmanager = new PoP_Frontend_ConversionManager();