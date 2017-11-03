<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Core (reusable) functions of the website
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ServerUtils {

	public static function is_not_mangled() {

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

		if (defined('POP_SERVER_USECACHE')) {
			return POP_SERVER_USECACHE;
		}

		return false;
	}
}