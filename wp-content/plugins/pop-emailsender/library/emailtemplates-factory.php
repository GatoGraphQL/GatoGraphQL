<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_EmailTemplates_Factory {

	protected static $instances = array();

	public static function set_instance($instance) {
		self::$instances[$instance->get_name()] = $instance;
	}

	public static function get_instance($name = null) {
		if (!$name) {
			$name = GD_EMAIL_FRAME_DEFAULT;
		}
		return self::$instances[$name];
	}
}