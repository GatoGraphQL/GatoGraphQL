<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Core (reusable) functions of the website
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ServerUtils {

	protected static $override_configuration;

	public static function init() {

		// // Allow to override the configuration with values passed in the query string:
		// // "config-on": comma-separated string with all fields with value "true"
		// // "config-off": comma-separated string with all fields with value "false"
		// $on = $off = array();
		// if ($configuration_on = $_REQUEST['config-on']) {
		// 	$on = explode(',', $configuration_on);
		// }
		// if ($configuration_off = $_REQUEST['config-off']) {
		// 	$off = explode(',', $configuration_off);
		// }
		// self::$override_configuration = array(
		// 	'on' => $on,
		// 	'off' => $off,
		// );

		// Allow to override the configuration with values passed in the query string:
		// "config": comma-separated string with all fields with value "true"
		// Whatever fields are not there, will be considered "false"
		self::$override_configuration = $_REQUEST[POP_URLPARAM_CONFIG] ? explode(',', $_REQUEST[POP_URLPARAM_CONFIG]) : array();
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

	// public static function get_override_configuration($key) {

	// 	if (self::$override_configuration['on'][$key]) {

	// 		return true;
	// 	}
	// 	elseif (self::$override_configuration['off'][$key]) {

	// 		return false;
	// 	}

	// 	// No value defined, then do nothing
	// 	return null;
	// }

	public static function is_loading_frame($fetching_json, $target) {

		// Load the frame when not doing JSON (first time loading website) or when loading the settings and there's no specific target defined
		return !$fetching_json || !$target;
	}

	public static function get_request_target() {

		$target = $_REQUEST[GD_URLPARAM_TARGET];
		$targets = apply_filters(
			'GD_TemplateManager_Utils:targets',
			array(
				GD_URLPARAM_TARGET_MAIN,
			)
		);

		// We allow an empty target if none provided, so that we can generate the settings cache when no target is provided
		// (ie initial load) and when target is provided (ie loading pageSection)
		if (!$target || ($target && !in_array($target, $targets))) {
			$target = apply_filters('GD_TemplateManager_Utils:default_target', GD_URLPARAM_TARGET_MAIN);
		}

		return $target;
	}

	public static function is_request_loading_frame() {

		$output = $_REQUEST[GD_URLPARAM_OUTPUT];		
		$fetching_json = ($output == GD_URLPARAM_OUTPUT_JSON);
		$target = self::get_request_target();

		return self::is_loading_frame($fetching_json, $target);
	}

	public static function is_not_mangled() {

		// Comment Leo 09/11/2017: the "not-mangled" attribute can only be used when doing "fetching-json"
		// When doing "loading-frame" it doesn't work, since the application already uses the configuration save in /generate-theme/ stage
		if (PoP_ServerUtils::is_request_loading_frame()) {
			return false;
		}

		// By default, it is mangled, if not mangled then param "mangled" must have value "none"
		// Coment Leo 13/01/2017: get_vars() can't function properly since it references objects which have not been initialized yet,
		// when called at the very beginning. So then access the request directly
		return isset($_REQUEST[GD_URLPARAM_MANGLED]) && $_REQUEST[GD_URLPARAM_MANGLED] == GD_URLPARAM_MANGLED_NONE;
	}

	public static function is_mangled() {

		return !self::is_not_mangled();
	}

	/**
	 * Use namespaces for the plugins, to avoid conflicts with the decentralized approach when 2 websites have different plugins installed
	 */
	public static function use_templatedefinition_constantovertime() {

		if (defined('POP_SERVER_TEMPLATEDEFINITION_CONSTANTOVERTIME')) {
			return POP_SERVER_TEMPLATEDEFINITION_CONSTANTOVERTIME;
		}

		return true;
	}

	/**
	 * Use namespaces for the plugins, to avoid conflicts with the decentralized approach when 2 websites have different plugins installed
	 */
	public static function use_templatedefinition_namespaces() {

		if (defined('POP_SERVER_TEMPLATEDEFINITION_USENAMESPACES')) {
			return POP_SERVER_TEMPLATEDEFINITION_USENAMESPACES;
		}

		// If we are using the "Constant over time" option, then the potential conflict from 2 websites not having the same name for the same templates
		// can be handled properly by sharing the same database of names across all websites. Then, there is no need for the namespace
		return !self::use_templatedefinition_constantovertime();
	}

	/**
	 * Types:
	 * 0: Use $template_id as the definition
	 * 1: Use both base36 counter and $template_id as the definition
	 * 2: Use base36 counter as the definition
	 */
	public static function get_templatedefinition_type() {

		if (defined('POP_SERVER_TEMPLATEDEFINITION_TYPE')) {
			return POP_SERVER_TEMPLATEDEFINITION_TYPE;
		}

		return 0;
	}

	/**
	 * Use 'modules' or 'm' in the JS context. Used to compress the file size in PROD
	 */
	public static function compact_js_keys() {

		// Do not compact if not mangled
		if (self::is_not_mangled()) {

			return false;
		}

		if (defined('POP_SERVER_COMPACTJSKEYS')) {
			return POP_SERVER_COMPACTJSKEYS;
		}

		return false;
	}

	public static function use_cache() {

		// // Allow to override the configuration
		// $override = PoP_ServerUtils::get_override_configuration('config-cache');
		// if (!is_null($override)) {
		// 	return $override;
		// }

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
