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

	public static function get_allowed_urls() {

		$homeurl = get_site_url();
		return array_unique(apply_filters(
			'gd_templatemanager:allowed_urls',
			array(
				$homeurl,
			)
		));
	}
}