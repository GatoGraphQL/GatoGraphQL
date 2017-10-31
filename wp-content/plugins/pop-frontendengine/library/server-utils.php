<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Core (reusable) functions of the website
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_Frontend_ServerUtils {

	public static function use_minified_resources() {

		if (defined('POP_SERVER_USEMINIFIEDRESOURCES')) {
			return POP_SERVER_USEMINIFIEDRESOURCES;
		}

		return false;
	}

	public static function use_cdn_resources() {

		if (defined('POP_SERVER_USECDNRESOURCES')) {
			return POP_SERVER_USECDNRESOURCES;
		}

		return false;
	}

	public static function use_local_storage() {

		if (defined('POP_SERVER_USELOCALSTORAGE')) {
			return POP_SERVER_USELOCALSTORAGE;
		}

		return true;
	}

	public static function use_serverside_rendering() {

		if (defined('POP_SERVER_USESERVERSIDERENDERING')) {
			return POP_SERVER_USESERVERSIDERENDERING;
		}

		return true;
	}

	public static function use_code_splitting() {

		if (defined('POP_SERVER_USECODESPLITTING')) {
			return POP_SERVER_USECODESPLITTING;
		}

		return true;
	}

	public static function use_codesplitting_fastboot() {

		if (!self::use_code_splitting()) {

			return false;
		}

		if (defined('POP_SERVER_USECODESPLITTINGFASTBOOT')) {
			return POP_SERVER_USECODESPLITTINGFASTBOOT;
		}

		return true;
	}

	public static function use_bundled_resources() {

		// Code Splitting takes priority: 
		if (self::use_code_splitting()) {

			return false;
		}

		if (defined('POP_SERVER_USEBUNDLEDRESOURCES')) {
			return POP_SERVER_USEBUNDLEDRESOURCES;
		}

		return false;
	}

	public static function generate_resources_on_runtime() {

		if (defined('POP_SERVER_GENERATERESOURCESONRUNTIME')) {
			return POP_SERVER_GENERATERESOURCESONRUNTIME;
		}

		return true;
	}
}