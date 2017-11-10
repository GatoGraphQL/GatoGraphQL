<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Core (reusable) functions of the website
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_Frontend_ServerUtils {

	public static function use_minified_resources() {

		// // Allow to override the configuration
		// $override = PoP_ServerUtils::get_override_configuration('minified');
		// if (!is_null($override)) {
		// 	return $override;
		// }

		if (defined('POP_SERVER_USEMINIFIEDRESOURCES')) {
			return POP_SERVER_USEMINIFIEDRESOURCES;
		}

		return false;
	}

	public static function use_cdn_resources() {

		// Allow to override the configuration
		$override = PoP_ServerUtils::get_override_configuration('cdn');
		if (!is_null($override)) {
			return $override;
		}

		if (defined('POP_SERVER_USECDNRESOURCES')) {
			return POP_SERVER_USECDNRESOURCES;
		}

		return false;
	}

	public static function use_local_storage() {

		// Allow to override the configuration
		$override = PoP_ServerUtils::get_override_configuration('localstorage');
		if (!is_null($override)) {
			return $override;
		}

		if (defined('POP_SERVER_USELOCALSTORAGE')) {
			return POP_SERVER_USELOCALSTORAGE;
		}

		return true;
	}

	public static function use_serverside_rendering() {

		// Allow to override the configuration
		$override = PoP_ServerUtils::get_override_configuration('serverside-rendering');
		if (!is_null($override)) {
			return $override;
		}

		if (defined('POP_SERVER_USESERVERSIDERENDERING')) {
			return POP_SERVER_USESERVERSIDERENDERING;
		}

		return true;
	}

	public static function use_code_splitting() {

		// Allow to override the configuration
		$override = PoP_ServerUtils::get_override_configuration('code-splitting');
		if (!is_null($override)) {
			return $override;
		}

		if (defined('POP_SERVER_USECODESPLITTING')) {
			return POP_SERVER_USECODESPLITTING;
		}

		return true;
	}

	public static function generate_bundle_files() {

		// Only valid when doing code-splitting
		if (!self::use_code_splitting()) {

			return false;
		}

		if (defined('POP_SERVER_GENERATEBUNDLEFILES')) {
			return POP_SERVER_GENERATEBUNDLEFILES;
		}

		return false;
	}

	public static function generate_bundlegroup_files() {

		// Only valid when doing code-splitting
		if (!self::use_code_splitting()) {

			return false;
		}

		if (defined('POP_SERVER_GENERATEBUNDLEGROUPFILES')) {
			return POP_SERVER_GENERATEBUNDLEGROUPFILES;
		}

		return false;
	}

	public static function get_bundles_chunk_size() {

		if (defined('POP_SERVER_BUNDLECHUNKSIZE')) {
			return POP_SERVER_BUNDLECHUNKSIZE;
		}

		return 4;
	}

	public static function bundle_external_files() {

		// Only valid when doing code-splitting
		if (!self::use_code_splitting()) {

			return false;
		}

		// If using CDN resources, then do not pack them inside
		return !self::use_cdn_resources();
	}

	public static function get_enqueuefile_type() {

		// Allow to override the configuration
		if (PoP_ServerUtils::get_override_configuration('load-bundlegroups') === true) {
			return 'bundlegroup';
		}
		elseif (PoP_ServerUtils::get_override_configuration('load-bundles') === true) {
			return 'bundle';
		}
		elseif (PoP_ServerUtils::get_override_configuration('load-resources') === true) {
			return 'resource';
		}

		if (defined('POP_SERVER_ENQUEUEFILESTYPE')) {

			// Make sure the defined value is valid
			$values = array(
				'bundlegroup',
				'bundle',
				'resource',
			);
			if (in_array(POP_SERVER_ENQUEUEFILESTYPE, $values)) {

				return POP_SERVER_ENQUEUEFILESTYPE;
			}
		}

		// Default value
		return 'resource';
	}

	public static function use_fastboot() {

		if (!self::use_serverside_rendering()) {

			return false;
		}

		// Allow to override the configuration
		$override = PoP_ServerUtils::get_override_configuration('fastboot');
		if (!is_null($override)) {
			return $override;
		}

		if (defined('POP_SERVER_USEFASTBOOT')) {
			return POP_SERVER_USEFASTBOOT;
		}

		return true;
	}

	public static function use_bundled_resources() {

		// Code Splitting takes priority: 
		if (self::use_code_splitting()) {

			return false;
		}

		// Allow to override the configuration
		$override = PoP_ServerUtils::get_override_configuration('app-bundle');
		if (!is_null($override)) {
			return $override;
		}

		if (defined('POP_SERVER_USEBUNDLEDRESOURCES')) {
			return POP_SERVER_USEBUNDLEDRESOURCES;
		}

		return false;
	}

	public static function generate_resources_on_runtime() {

		// Allow to override the configuration
		$override = PoP_ServerUtils::get_override_configuration('runtime-js');

		if (!is_null($override)) {
			return $override;
		}

		if (defined('POP_SERVER_GENERATERESOURCESONRUNTIME')) {
			return POP_SERVER_GENERATERESOURCESONRUNTIME;
		}

		return true;
	}

	public static function use_serviceworkers() {

		// Allow to override the configuration
		$override = PoP_ServerUtils::get_override_configuration('sw');
		if (!is_null($override)) {
			return $override;
		}

		if (defined('POP_SERVER_USESERVICEWORKERS')) {
			return POP_SERVER_USESERVICEWORKERS;
		}

		return true;
	}
}