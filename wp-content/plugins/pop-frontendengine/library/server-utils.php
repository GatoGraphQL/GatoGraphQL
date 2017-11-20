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

	public static function access_externalcdn_resources() {

		// Allow to override the configuration
		$override = PoP_ServerUtils::get_override_configuration('externalcdn');
		if (!is_null($override)) {
			return $override;
		}

		if (defined('POP_SERVER_ACCESSEXTERNALCDNRESOURCES')) {
			return POP_SERVER_ACCESSEXTERNALCDNRESOURCES;
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

	public static function remove_database_from_output() {

		// We only remove the code in the server-side rendering, when first loading the website. If this is not the case,
		// then there is no need for this functionality
		if (!GD_TemplateManager_Utils::loading_frame() || !PoP_Frontend_ServerUtils::use_serverside_rendering()) {

			return false;
		}

		// Allow to override the configuration
		$override = PoP_ServerUtils::get_override_configuration('remove-db');
		if (!is_null($override)) {
			return $override;
		}

		// Allow specific pages to set this value to false
		// Eg: the Events Calendar, which requires the DB data to add events to the calendar all on runtime
		// (until we can produce the HTML for the calendar also on the server-side. Check: https://github.com/leoloso/PoP/issues/59)
		if (apply_filters('keep_database_in_output', false)) {

			return false;
		}

		if (defined('POP_SERVER_REMOVEDATABASEFROMOUTPUT')) {
			return POP_SERVER_REMOVEDATABASEFROMOUTPUT;
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

	public static function skip_bundle_pageswithparams() {

		if (defined('POP_SERVER_SKIPBUNDLEPAGESWITHPARAMS')) {
			return POP_SERVER_SKIPBUNDLEPAGESWITHPARAMS;
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
		return !self::access_externalcdn_resources();
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

		// There are requests that can only work with a specific type
		// Eg: the AppShell, it must always use 'resource', or otherwise it will need to load extra bundle(group) files, 
		// making the initial SW pre-fetch heavy, and not allowing to easily create the AppShell for the different thememodes (embed, print)
		if ($enqueuefile_type = apply_filters('get_enqueuefile_type', '')) {

			return $enqueuefile_type;
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

	public static function scripts_after_html() {

		if (!self::use_serverside_rendering()) {

			return false;
		}

		// Allow to override the configuration
		$override = PoP_ServerUtils::get_override_configuration('scripts-end');
		if (!is_null($override)) {
			return $override;
		}

		if (defined('POP_SERVER_SCRIPTSAFTERHTML')) {
			return POP_SERVER_SCRIPTSAFTERHTML;
		}

		return true;
	}

	public static function use_appshell() {

		// Allow to override the configuration
		$override = PoP_ServerUtils::get_override_configuration('appshell');
		if (!is_null($override)) {
			return $override;
		}

		if (defined('POP_SERVER_USEAPPSHELL')) {
			return POP_SERVER_USEAPPSHELL;
		}

		return false;
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

		// Even if set as true, there are requests that cannot generate resources on runtime
		// Eg: the AppShell, or otherwise we must also cache the corresponding /sitemapping/ and /settings/ .js files,
		// which we can't obtain when generating the service-worker.js file
		if (apply_filters('generate_resources_on_runtime', false)) {

			return false;
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

	public static function use_progressive_booting() {

		// Only valid when doing code-splitting
		if (!self::use_code_splitting()) {

			return false;
		}

		// Allow to override the configuration
		$override = PoP_ServerUtils::get_override_configuration('progressive-booting');
		if (!is_null($override)) {
			return $override;
		}

		if (defined('POP_SERVER_USEPROGRESSIVEBOOTING')) {
			return POP_SERVER_USEPROGRESSIVEBOOTING;
		}

		return true;
	}
}