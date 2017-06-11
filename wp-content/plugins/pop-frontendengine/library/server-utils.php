<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Core (reusable) functions of the website
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_Frontend_ServerUtils {

	public static function use_minified_files() {

		if (defined('POP_SERVER_USEMINIFIEDFILES')) {
			return POP_SERVER_USEMINIFIEDFILES;
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
}