<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Core (reusable) functions of the website
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_Frontend_ConversionUtils {

	public static function get_classes() {

		global $pop_frontend_conversionmanager;
		return $pop_frontend_conversionmanager->get_classes();
	}

	public static function convert($class) {

		global $pop_frontend_conversionmanager;
		return $pop_frontend_conversionmanager->convert($class);
	}

	public static function get_class_selector($classname) {

		global $pop_frontend_conversionmanager;
		return $pop_frontend_conversionmanager->get_class_selector($classname);
	}

	public static function get_styles_from_classes($classes) {

		global $pop_frontend_conversionmanager;
		return $pop_frontend_conversionmanager->get_styles_from_classes($classes);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
// PoP_Frontend_ConversionUtils::init();