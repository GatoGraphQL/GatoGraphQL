<?php

class PoP_ServerUtils {

	protected static $override_configuration;

	public static function init() {

		// Allow to override the configuration with values passed in the query string:
		// "config": comma-separated string with all fields with value "true"
		// Whatever fields are not there, will be considered "false"
		self::$override_configuration = array();
		if (PoP_ServerUtils::enable_config_by_params()) {
			
			self::$override_configuration = $_REQUEST[POP_URLPARAM_CONFIG] ? explode(',', $_REQUEST[POP_URLPARAM_CONFIG]) : array();
		}
	}

	public static function doing_override_configuration() {

		return !empty(self::$override_configuration);
	}

	public static function get_override_configuration($key) {

		// If no values where defined in the configuration, then skip it completely
		if (empty(self::$override_configuration)) {

			return null;
		}

		// Check if the key has been given value "true"
		if (in_array($key, self::$override_configuration)) {

			return true;
		}

		// Otherwise, it has value "false"
		return false;
	}

	public static function is_mangled() {

		// By default, it is mangled, if not mangled then param "mangled" must have value "none"
		// Coment Leo 13/01/2017: get_vars() can't function properly since it references objects which have not been initialized yet,
		// when called at the very beginning. So then access the request directly
		return !$_REQUEST[GD_URLPARAM_MANGLED] || $_REQUEST[GD_URLPARAM_MANGLED] != GD_URLPARAM_MANGLED_NONE;
	}

	public static function enable_config_by_params() {

		if (defined('POP_SERVER_ENABLECONFIGBYPARAMS')) {
			return POP_SERVER_ENABLECONFIGBYPARAMS;
		}

		return false;
	}

	public static function fail_if_modules_defined_twice() {

		if (defined('POP_SERVER_FAILIFMODULESDEFINEDTWICE')) {
			return POP_SERVER_FAILIFMODULESDEFINEDTWICE;
		}

		return false;
	}

	public static function enable_extrauris_by_params() {

		if (defined('POP_SERVER_ENABLEEXTRAURISBYPARAMS')) {
			return POP_SERVER_ENABLEEXTRAURISBYPARAMS;
		}

		return false;
	}

	/**
	 * Use 'modules' or 'm' in the JS context. Used to compress the file size in PROD
	 */
	public static function compact_response_json_keys() {

		// Do not compact if not mangled
		if (!self::is_mangled()) {

			return false;
		}

		if (defined('POP_SERVER_COMPACTRESPONSEJSONKEYS')) {
			return POP_SERVER_COMPACTRESPONSEJSONKEYS;
		}

		return false;
	}

	public static function use_cache() {

		// If we are overriding the configuration, then do NOT use the cache
		// Otherwise, parameters from the config have need to be added to $vars, however they can't,
		// since we want the $vars model_instance_id to not change when testing with the "config" param
		if (self::doing_override_configuration()) {
			
			return false;
		}

		if (defined('POP_SERVER_USECACHE')) {
			return POP_SERVER_USECACHE;
		}

		return false;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
PoP_ServerUtils::init();
