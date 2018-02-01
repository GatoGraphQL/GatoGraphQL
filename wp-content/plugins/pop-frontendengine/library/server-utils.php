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

		// If disabling the JS, then we can only do server-side rendering
		if (self::disable_js()) {

			return true;
		}

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

		// // Allow to override the configuration
		// $override = PoP_ServerUtils::get_override_configuration('remove-db');
		// if (!is_null($override)) {
		// 	return $override;
		// }

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

	public static function include_resources_in_header() {

		return self::get_templateresources_include_type() == 'header';
	}

	public static function include_resources_in_body() {

		$bodies = array(
			'body',
			'body-inline',
		);
		return in_array(self::get_templateresources_include_type(), $bodies);
	}

	public static function get_templateresources_include_type() {

		// Include in the body is only valid when doing server-side rendering, code-splitting, and the enqueue type is 'resource'
		if (!self::use_serverside_rendering() || !self::use_code_splitting() || self::get_enqueuefile_type() != 'resource') {

			return 'header';
		}

		// Allow to override the configuration
		if (PoP_ServerUtils::get_override_configuration('resources-header') === true) {
			
			return 'header';
		}
		elseif (PoP_ServerUtils::get_override_configuration('resources-body') === true) {
			
			return 'body';
		}
		elseif (PoP_ServerUtils::get_override_configuration('resources-body-inline') === true) {
			
			return 'body-inline';
		}

		// Allow specific pages to set this value to false
		// Eg: when generating the Service Workers, we need to register all of the CSS files to output them in the precache list
		if ($include_type = apply_filters('get_templateresources_include_type', '')) {

			return $include_type;
		}

		if (defined('POP_SERVER_TEMPLATERESOURCESINCLUDETYPE')) {

			// Make sure the defined value is valid
			$values = array(
				'header',
				'body',
				'body-inline',
			);
			if (in_array(POP_SERVER_TEMPLATERESOURCESINCLUDETYPE, $values)) {

				return POP_SERVER_TEMPLATERESOURCESINCLUDETYPE;
			}
		}

		// Default value
		return 'header';
	}

	public static function generate_loadingframe_resource_mapping() {

		// Only valid when doing code-splitting
		if (!self::use_code_splitting()) {

			return false;
		}

		// If generating either bundles or bundlegroups, then it can be done with no much processing needed, so just do it
		if (self::generate_bundle_files() || self::generate_bundlegroup_files()) {

			return true;
		}

		if (defined('POP_SERVER_GENERATELOADINGFRAMERESOURCEMAPPING')) {

			return POP_SERVER_GENERATELOADINGFRAMERESOURCEMAPPING;
		}

		return false;
	}

	public static function generate_bundlefiles_on_runtime() {

		// Only valid when doing code-splitting
		if (!self::use_code_splitting()) {

			return false;
		}

		// If generating either bundles or bundlegroups, and the enqueue type is the corresponding one, then the bundle(group) files will be generated statically, so no need
		$enqueuefile_type = self::get_enqueuefile_type();
		if (
			$enqueuefile_type == 'resource' || 
			($enqueuefile_type == 'bundle' && self::generate_bundle_files()) || 
			($enqueuefile_type == 'bundlegroup' && self::generate_bundlegroup_files())) {

			return false;
		}

		if (defined('POP_SERVER_GENERATEBUNDLEFILESONRUNTIME')) {
			
			return POP_SERVER_GENERATEBUNDLEFILESONRUNTIME;
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

	// public static function skip_bundle_pageswithparams() {

	// 	if (defined('POP_SERVER_SKIPBUNDLEPAGESWITHPARAMS')) {
			
	// 		return POP_SERVER_SKIPBUNDLEPAGESWITHPARAMS;
	// 	}

	// 	return false;
	// }

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

	public static function get_enqueuefile_type($disable_hooks = false) {

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

		if (!$disable_hooks) {
			
			// There are requests that can only work with a specific type
			// Eg: the AppShell, it must always use 'resource', or otherwise it will need to load extra bundle(group) files, 
			// making the initial SW pre-fetch heavy, and not allowing to easily create the AppShell for the different thememodes (embed, print)
			if ($enqueuefile_type = apply_filters('get_enqueuefile_type', '')) {

				return $enqueuefile_type;
			}
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

	public static function loading_bundlefile($disable_hooks = false) {

		$enqueuefile_type = self::get_enqueuefile_type($disable_hooks);
		return $enqueuefile_type == 'bundlegroup' || $enqueuefile_type == 'bundle';
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

		if (self::disable_js()) {

			return false;
		}

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

		if (self::disable_js()) {

			return false;
		}

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

		if (self::disable_js()) {

			return false;
		}

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

	public static function disable_js() {

		// Allow to override the configuration
		$override = PoP_ServerUtils::get_override_configuration('disable-js');
		if (!is_null($override)) {
			
			return $override;
		}

		if (defined('POP_SERVER_DISABLEJS')) {
			
			return POP_SERVER_DISABLEJS;
		}

		return false;
	}

	public static function use_generatetheme_output_files() {

		// This constant indicates that we have run /generate-theme/ in this environment, and have created the output files
		// (or, for PROD, we have copied the files from STAGING)
		// These files are:
		// 1- resourceloader-bundle-mapping.json
		// 2- resourceloader-generatedfiles.json
		// 3- All pop-memory/ files
		// All files under pop-memory/ are way many, and it is tempting to not include them in the release, but still include resourceloader-generatedfiles.json
		// These files must ALL be copied! We can't copy just `resourceloader-generatedfiles.json` but not the pop-memory/ files, 
		// or the application will not work in function `is_defer()` for the resourceLoaders, which depend on pop-memory/ to find out if the resource must be set as "defer" or not
		// The problem is here: if accessing the cached content from file resourceloader-generatedfiles.json, then pop-memory/ files 
		// will not get regenerated on runtime, when doing:
		// protected function register_scripts_or_styles($type) {
		// 	...
		// 	if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
		// 		$resources = $pop_resourceloader_generatedfilesmanager->get_js_resources($vars_hash_id);
		// 	}
		// 	elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
		// 		$resources = $pop_resourceloader_generatedfilesmanager->get_css_resources($vars_hash_id);	
		// 	}
		// 	...
		// Then, we need consistency: we either use resourceloader-generatedfiles.json and copy the pop-memory/ files from STAGING to PROD, 
		// or none and we generate these on runtime
		if (defined('POP_SERVER_USEGENERATETHEMEOUTPUTFILES')) {
			
			return POP_SERVER_USEGENERATETHEMEOUTPUTFILES;
		}

		return false;
	}

	public static function skip_loadingframe_resources() {

		// Allow to override the configuration
		$override = PoP_ServerUtils::get_override_configuration('skip-loadingframe-resources');
		if (!is_null($override)) {
			
			return $override;
		}

		if (defined('POP_SERVER_SKIPLOADINGFRAMERESOURCES')) {
			
			return POP_SERVER_SKIPLOADINGFRAMERESOURCES;
		}

		return true;
	}
}