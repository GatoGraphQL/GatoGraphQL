<?php
namespace PoP\Engine;

class Engine {

	var $data, $helperCalculations, $model_props, $props;
	protected $nocache_fields, $moduledata, $ids_data_fields, $dbdata, $backgroundload_urls, $extra_uris, $cachedsettings, $output_data;

	function __construct() {

		// Set myself as the Engine instance in the Factory
		Engine_Factory::set_instance($this);
	}

	// function is_cached_settings() {

	// 	return $this->cachedsettings;
	// }

	function get_output_data() {

		return $this->output_data;
	}

	function add_background_url($url, $targets) {

		$this->backgroundload_urls[$url] = $targets;
	}

	function get_entry_module() {

		$siteconfiguration = Settings\SiteConfigurationProcessorManager_Factory::get_instance()->get_processor();
		if (!$siteconfiguration) {

			throw new \Exception('There is no Site Configuration. Hence, we can\'t continue.');
		}

		$module = $siteconfiguration->get_entry_module();
		if (!$module) {

			throw new \Exception(sprintf('No entry module for this request (%s)', full_url()));
		}

		return $module;		
	}

	function send_etag_header() {

		// ETag is needed for the Service Workers
		// Also needed to use together with the Control-Cache header, to know when to refetch data from the server: https://developers.google.com/web/fundamentals/performance/optimizing-content-efficiency/http-caching
		if (apply_filters('\PoP\Engine\Engine:output_data:add_etag_header', true)) {

			// The same page will have different hashs only because of those random elements added each time,
			// such as the unique_id and the current_time. So remove these to generate the hash
			$differentiators = array(
				POP_CONSTANT_UNIQUE_ID,
				POP_CONSTANT_CURRENTTIMESTAMP,
				POP_CONSTANT_RAND,
				POP_CONSTANT_TIME,
			);
			$commoncode = str_replace($differentiators, '', json_encode($this->data));

			// Also replace all those tags with content that, even if it's different, should not alter the output
			// Eg: comments-count. Because adding a comment does not delete the cache, then the comments-count is allowed
			// to be shown stale. So if adding a new comment, there's no need for the user to receive the
			// "This page has been updated, click here to refresh it." notification
			// Because we already got the JSON, then remove entries of the type:
			// "userpostactivity-count":1, (if there are more elements after)
			// and
			// "userpostactivity-count":1
			// Comment Leo 22/10/2017: ?module=settings doesn't have 'nocache-fields'
			if ($this->nocache_fields) {
				$commoncode = preg_replace('/"('.implode('|', $this->nocache_fields).')":[0-9]+,?/', '', $commoncode);
			}

			// Allow plug-ins to replace their own non-needed content (eg: thumbprints, defined in Core)
			$commoncode = apply_filters('\PoP\Engine\Engine:etag_header:commoncode', $commoncode);

			header("ETag: ".wp_hash($commoncode));
		}
	}

	protected function get_extra_uris() {

		// The extra URIs must be cached! That is because we will change the requested URI in $vars, upon which the hook to inject extra URIs (eg: for page INITIALFRAMES) will stop working
		if (!is_null($this->extra_uris)) {
			return $this->extra_uris;
		}

		$this->extra_uris = array();
		if (Server\Utils::enable_extrauris_by_params()) {

			$this->extra_uris = $_REQUEST[GD_URLPARAM_EXTRAURIS] ?? array();
			$this->extra_uris = is_array($this->extra_uris) ? $this->extra_uris : array($this->extra_uris);
		}

		// Enable to add extra URLs in a fixed manner
		$this->extra_uris = apply_filters(
			'\PoP\Engine\Engine:get_extra_uris',
			$this->extra_uris
		);

		return $this->extra_uris;
	}

	protected function list_extra_uri_vars() {

		if ($has_extra_uris = !empty($this->get_extra_uris())) {

			$model_instance_id = ModelInstanceProcessor_Utils::get_model_instance_id();
			$current_uri = remove_domain(Utils::get_current_url());
		}

		return array($has_extra_uris, $model_instance_id, $current_uri);
	}

	function generate_data() {

		do_action('\PoP\Engine\Engine:beginning');

		// Process the request and obtain the results
		$this->data = $this->helperCalculations = array();
		$this->process_and_generate_data();

		// See if there are extra URIs to be processed in this same request
		if ($extra_uris = $this->get_extra_uris()) {

			// Combine the response for each extra URI together with the original response, merging all JSON objects together, but under each's URL/model_instance_id

			// To obtain the hierarchy for each URI, we use a hack: change the current URI and create a new WP object, which will process the query_vars and from there obtain the hierarchy
			// First make a backup of the current URI to set it again later
			$current_request_uri = $_SERVER['REQUEST_URI'];

			// Process each extra URI, and merge its results with all others
			foreach ($extra_uris as $uri) {

				// From this hack, we obtain the hierarchy
				$_SERVER['REQUEST_URI'] = $uri;
				
				// Reset $vars so that it gets created anew
				Engine_Vars::reset();

				// Allow functionalities to be reset too. Eg: ActionExecuterBase results
				do_action('\PoP\Engine\Engine:generate_data:reset');
				
				// Process the request with the new $vars and merge it with all other results
				// Can't use array_merge_recursive since it creates arrays when the key is the same, which is not what is expected in this case
				$this->process_and_generate_data();
			}

			// Set the previous values back
			$_SERVER['REQUEST_URI'] = $current_request_uri;
			Engine_Vars::reset();
		}

		// Add session/site meta
		$this->add_shared_meta();

		// If any formatter is passed, then format the data accordingly
		$this->format_data();

		// Keep only the data that is needed to be sent, and encode it as JSON
		$this->calculate_outuput_data();

		// Send the ETag-header
		$this->send_etag_header();
	}

	protected function format_data() {

		$formatter = Utils::get_datastructure_formatter();
		$this->data = $formatter->get_formatted_data($this->data);
	}

	function calculate_outuput_data() {

		$this->output_data = $this->get_encoded_data_object($this->data);
	}

	// Allow PoPFrontend_Engine to override this function
	protected function get_encoded_data_object($data) {

		// Comment Leo 14/09/2018: Re-enable here:
		// if (true) {
		// 	unset($data['combinedstatedata']);
		// }

		return $data;
	}

	// function trigger_output_hooks() {
		
	// 	// Allow extra functionalities. Eg: Save the logged-in user meta information
	// 	do_action('\PoP\Engine\Engine:output:end');
	// 	do_action('\PoP\Engine\Engine:rendered');
	// }

	// function trigger_outputdata_hooks() {
		
	// 	// Allow extra functionalities. Eg: Save the logged-in user meta information
	// 	do_action('\PoP\Engine\Engine:output_data:end');
	// 	do_action('\PoP\Engine\Engine:rendered');
	// }

	// function output() {

	// 	// Indicate that this is a json response in the HTTP Header
	// 	header('Content-type: application/json');
		
	// 	echo $this->encoded_data;

	// 	// Allow extra functionalities. Eg: Save the logged-in user meta information
	// 	$this->trigger_output_hooks();
	// }

	// function output_data() {

	// 	// Indicate that this is a json response in the HTTP Header
	// 	header('Content-type: application/json');
		
	// 	echo $this->encoded_data;

	// 	// Allow extra functionalities. Eg: Save the logged-in user meta information
	// 	$this->trigger_outputdata_hooks();
	// }

	// function check_redirect($addoutput) {

	// 	$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();
		
	// 	$pop_module_settingsmanager = Settings\SettingsManager_Factory::get_instance();
	// 	if ($redirect = $pop_module_settingsmanager->get_redirect_url()) {

	// 		if ($addoutput) {
				
	// 			$redirect = add_query_arg(GD_URLPARAM_OUTPUT, GD_URLPARAM_OUTPUT_JSON, $redirect);

	// 			if ($target = $_REQUEST[GD_URLPARAM_TARGET]) {

	// 				$redirect = add_query_arg(GD_URLPARAM_TARGET, $target, $redirect);
	// 			}
	// 			if ($datastructure = $_REQUEST[GD_URLPARAM_DATASTRUCTURE]) {

	// 				$redirect = add_query_arg(GD_URLPARAM_DATASTRUCTURE, $datastructure, $redirect);
	// 			}
	// 			if ($mangled = $_REQUEST[GD_URLPARAM_MANGLED]) {

	// 				$redirect = add_query_arg(GD_URLPARAM_MANGLED, $mangled, $redirect);
	// 			}
	// 			if ($modulefilter = $_REQUEST[GD_URLPARAM_MODULEFILTER]) {

	// 				$redirect = add_query_arg(GD_URLPARAM_MODULEFILTER, $modulefilter, $redirect);
					
	// 				if ($modulefilter == POP_MODULEFILTER_MODULEPATHS && ($modulepaths = $_REQUEST[GD_URLPARAM_MODULEPATHS])) {

	// 					$redirect = add_query_arg(GD_URLPARAM_MODULEPATHS, $modulepaths, $redirect);
	// 				}
	// 				elseif ($modulefilter == POP_MODULEFILTER_HEADMODULE && ($headmodule = $_REQUEST[GD_URLPARAM_HEADMODULE])) {

	// 					$redirect = add_query_arg(GD_URLPARAM_HEADMODULE, $headmodule, $redirect);
	// 				}
	// 			}
	// 			if ($actionpath = $_REQUEST[GD_URLPARAM_ACTIONPATH]) {

	// 				$redirect = add_query_arg(GD_URLPARAM_ACTIONPATH, $actionpath, $redirect);
	// 			}
	// 			if (Server\Utils::enable_config_by_params()) {
					
	// 				if ($config = $_REQUEST[POP_URLPARAM_CONFIG]) {

	// 					$redirect = add_query_arg(POP_URLPARAM_CONFIG, $config, $redirect);
	// 				}
	// 			}
	// 		}

	// 		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
	// 		$cmsapi->redirect($redirect); exit;
	// 	}
	// }

	// function maybe_redirect() {

	// 	$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();
		
	// 	$pop_module_settingsmanager = Settings\SettingsManager_Factory::get_instance();
	// 	if ($redirect = $pop_module_settingsmanager->get_redirect_url()) {

	// 		if ($query = $_SERVER['QUERY_STRING']) {

	// 			$redirect .= '?'.$query;
	// 		}

	// 		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
	// 		$cmsapi->redirect($redirect); exit;
	// 	}
	// }

	function get_model_props_moduletree($module) {

		global $pop_module_cachemanager;
		$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();

		$processor = $moduleprocessor_manager->get_processor($module);
		
		// Important: cannot use it if doing POST, because the request may have to be handled by a different block than the one whose data was cached
		// Eg: doing GET on /add-post/ will show the form BLOCK_ADDPOST_CREATE, but doing POST on /add-post/ will bring the action ACTION_ADDPOST_CREATE
		// First check if there's a cache stored
		$use_cache = Server\Utils::use_cache();
		if ($use_cache) {
			
			$model_props = $pop_module_cachemanager->get_cache_by_model_instance(POP_CACHETYPE_PROPS, true);
		}

		// If there is no cached one, or not using the cache, generate the props and cache it
		if (!$model_props) {

			$model_props = array();
			$processor->init_model_props_moduletree($module, $model_props, array(), array());
			
			if ($use_cache) {
				$pop_module_cachemanager->store_cache_by_model_instance(POP_CACHETYPE_PROPS, $model_props, true);
			}
		}

		return $model_props;
	}

	// Notice that $props is passed by copy, this way the input $model_props and the returned $immutable_plus_request_props are different objects
	function add_request_props_moduletree($module, $props) {

		$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();
		$processor = $moduleprocessor_manager->get_processor($module);
		
		// The input $props is the model_props. We add, on object, the mutableonrequest props, resulting in a "static + mutableonrequest" props object
		$processor->init_request_props_moduletree($module, $props, array(), array());

		return $props;
	}

	protected function process_and_generate_data() {

		$vars = Engine_Vars::get_vars();

		// Externalize logic into function so it can be overridden by PoP Frontend Engine
		$dataoutputitems = $vars['dataoutputitems'];

		// From the state we know if to process static/staful content or both
		$datasources = $vars['datasources'];

		// Get the entry module based on the application configuration and the hierarchy
		$module = $this->get_entry_module();

		// Save it to be used by the children class
		// Static props are needed for both static/mutableonrequest operations, so build it always
		$this->model_props = $this->get_model_props_moduletree($module);

		// If only getting static content, then no need to add the mutableonrequest props
		if ($datasources == GD_URLPARAM_DATASOURCES_ONLYMODEL) {

			$this->props = $this->model_props;
		}
		else {

			$this->props = $this->add_request_props_moduletree($module, $this->model_props);
		}

		// Allow for extra operations (eg: calculate resources)
		do_action(
			'\PoP\Engine\Engine:helperCalculations',
			array(&$this->helperCalculations),
			$module,
			array(&$this->props)
		);

		// Always send back the requestsettings, which indicates which is the entry module
		$data = array(
			// 'requestsettings' => $this->get_request_settings($module, $model_props),
		);
				
		if (in_array(GD_URLPARAM_DATAOUTPUTITEMS_MODULESETTINGS, $dataoutputitems)) {

			$data = array_merge(
				$data,
				$this->get_module_settings($module, $this->model_props, $this->props)
			);
		}

		// Comment Leo 20/01/2018: we must first initialize all the settings, and only later add the data.
		// That is because calculating the data may need the values from the settings. Eg: for the resourceLoader, 
		// calculating $loadingframe_resources needs to know all the Handlebars templates from the sitemapping as to generate file "resources.js",
		// which is done through an action, called through get_data()
		// Data = dbobjectids (data-ids) + feedback + database
		if (
			in_array(GD_URLPARAM_DATAOUTPUTITEMS_MODULEDATA, $dataoutputitems) ||
			in_array(GD_URLPARAM_DATAOUTPUTITEMS_DATABASES, $dataoutputitems)
		) {

			$data = array_merge(
				$data,
				$this->get_module_data($module, $this->model_props, $this->props)
			);

			if (in_array(GD_URLPARAM_DATAOUTPUTITEMS_DATABASES, $dataoutputitems)) {

				$data = array_merge(
					$data,
					$this->get_databases()
				);
			}
		}	

		list($has_extra_uris, $model_instance_id, $current_uri) = $this->list_extra_uri_vars();

		if (
			in_array(GD_URLPARAM_DATAOUTPUTITEMS_MODULESETTINGS, $dataoutputitems) ||
			in_array(GD_URLPARAM_DATAOUTPUTITEMS_MODULEDATA, $dataoutputitems)
		) {

			// Also add the request, session and site meta.
			// IMPORTANT: Call these methods after doing ->get_module_data, since the background_urls and other info is calculated there and printed here
			if ($requestmeta = $this->get_request_meta()) {
				$data['requestmeta'] = $has_extra_uris ? array($current_uri => $requestmeta) : $requestmeta;
			}
		}

		// Comment Leo 14/09/2018: Re-enable here:
		// // Combine the statelessdata and mutableonrequestdata objects
		// if ($data['modulesettings']) {

		// 	$data['modulesettings']['combinedstate'] = array_merge_recursive(
		// 		$data['modulesettings']['immutable'] ?? array()
		// 		$data['modulesettings']['mutableonmodel'] ?? array()
		// 		$data['modulesettings']['mutableonrequest'] ?? array(),
		// 	);	
		// }
		// if ($data['moduledata']) {

		// 	$data['moduledata']['combinedstate'] = array_merge_recursive(
		// 		$data['moduledata']['immutable'] ?? array()
		// 		$data['moduledata']['mutableonmodel'] ?? array()
		// 		$data['moduledata']['mutableonrequest'] ?? array(),
		// 	);	
		// }
		// if ($data['datasetmoduledata']) {

		// 	$data['datasetmoduledata']['combinedstate'] = array_merge_recursive(
		// 		$data['datasetmoduledata']['immutable'] ?? array()
		// 		$data['datasetmoduledata']['mutableonmodel'] ?? array()
		// 		$data['datasetmoduledata']['mutableonrequest'] ?? array(),
		// 	);	
		// }

		// Do array_replace_recursive because it may already contain data from doing 'extra-uris'
		$this->data = array_replace_recursive(
			$this->data,
			$data
		);
	}

	protected function add_shared_meta() {

		$vars = Engine_Vars::get_vars();

		// Externalize logic into function so it can be overridden by PoP Frontend Engine
		$dataoutputitems = $vars['dataoutputitems'];

		if (
			in_array(GD_URLPARAM_DATAOUTPUTITEMS_MODULESETTINGS, $dataoutputitems) ||
			in_array(GD_URLPARAM_DATAOUTPUTITEMS_MODULEDATA, $dataoutputitems)
		) {

			// Also add the request, session and site meta.
			// IMPORTANT: Call these methods after doing ->get_module_data, since the background_urls and other info is calculated there and printed here
			// If it has extra-uris, pass along this information, so that the client can fetch the setting from under $model_instance_id ("mutableonmodel") and $uri ("mutableonrequest")
			if ($this->get_extra_uris()) {
				$this->data['requestmeta'][POP_JS_MULTIPLEURIS] = true;
			}
			if ($sitemeta = $this->get_site_meta()) {
				$this->data['sitemeta'] = $sitemeta;
			}
		}

		if (in_array(GD_URLPARAM_DATAOUTPUTITEMS_SESSION, $dataoutputitems)) {
		
			if ($sessionmeta = $this->get_session_meta()) {
				$this->data['sessionmeta'] = $sessionmeta;
			}
		}
	}

	function get_module_settings($module, $model_props, $props) {

		global $pop_module_cachemanager;
		$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();

		$ret = array();

		$processor = $moduleprocessor_manager->get_processor($module);
		
		// From the state we know if to process static/staful content or both
		$vars = Engine_Vars::get_vars();
		$datasources = $vars['datasources'];
		$dataoutputmode = $vars['dataoutputmode'];

		// Templates: What modules must be executed after call to loadMore is back with data:
		// CB: list of modules to merge
		$this->cachedsettings = false;

		// First check if there's a cache stored
		$use_cache = Server\Utils::use_cache();
		if ($use_cache) {
			
			$immutable_settings = $pop_module_cachemanager->get_cache_by_model_instance(POP_CACHETYPE_IMMUTABLESETTINGS, true);
			$mutableonmodel_settings = $pop_module_cachemanager->get_cache_by_model_instance(POP_CACHETYPE_STATEFULSETTINGS, true);
		}

		// If there is no cached one, generate the configuration and cache it
		$this->cachedsettings = false;
		if ($immutable_settings) {

			$this->cachedsettings = true;
		}
		else {

			$immutable_settings = $processor->get_immutable_settings_moduletree($module, $model_props);
			$mutableonmodel_settings = $processor->get_mutableonmodel_settings_moduletree($module, $model_props);

			if ($use_cache) {
				$pop_module_cachemanager->store_cache_by_model_instance(POP_CACHETYPE_IMMUTABLESETTINGS, $immutable_settings, true);
				$pop_module_cachemanager->store_cache_by_model_instance(POP_CACHETYPE_STATEFULSETTINGS, $mutableonmodel_settings, true);
			}
		}
		if ($datasources == GD_URLPARAM_DATASOURCES_MODELANDREQUEST) {

			$mutableonrequest_settings = $processor->get_mutableonrequest_settings_moduletree($module, $props);
		}

		// If there are multiple URIs, then the results must be returned under the corresponding $model_instance_id for "mutableonmodel", and $url for "mutableonrequest"
		list($has_extra_uris, $model_instance_id, $current_uri) = $this->list_extra_uri_vars();

		if ($dataoutputmode == GD_URLPARAM_DATAOUTPUTMODE_SPLITBYSOURCES) {

			// Save the model settings
			if ($immutable_settings) {
				$ret['modulesettings']['immutable'] = $immutable_settings;
			}
			if ($mutableonmodel_settings) {
				$ret['modulesettings']['mutableonmodel'] = $has_extra_uris ? array($model_instance_id => $mutableonmodel_settings) : $mutableonmodel_settings;
			}
			if ($mutableonrequest_settings) {
				$ret['modulesettings']['mutableonrequest'] = $has_extra_uris ? array($current_uri => $mutableonrequest_settings) : $mutableonrequest_settings;
			}
		}
		elseif ($dataoutputmode == GD_URLPARAM_DATAOUTPUTMODE_COMBINED) {

			// If everything is combined, then it belongs under "mutableonrequest"
			if ($combined_settings = array_merge_recursive(
				$immutable_settings ?? array(),
				$mutableonmodel_settings ?? array(),
				$mutableonrequest_settings ?? array()
			)) {
				$ret['modulesettings'] = $has_extra_uris ? array($current_uri => $combined_settings) : $combined_settings;
			}
		}

		return $ret;
	}

	// function get_request_settings($module, $props) {

	// 	return apply_filters(
	// 		'\PoP\Engine\Engine:request-settings',
	// 		array()
	// 	);
	// }

	function get_request_meta() {

		$meta = array(
			POP_CONSTANT_ENTRYMODULE => $this->get_entry_module(),
			// Review: is it needed?
			// POP_UNIQUEID => POP_CONSTANT_UNIQUE_ID,
			GD_URLPARAM_URL => Utils::get_current_url(),
			'modelinstanceid' => ModelInstanceProcessor_Utils::get_model_instance_id(),
		);
		
		if ($this->backgroundload_urls) {
			$meta[GD_URLPARAM_BACKGROUNDLOADURLS] = $this->backgroundload_urls;
		};

		// Starting from what modules must do the rendering. Allow for empty arrays (eg: modulepaths[]=somewhatevervalue)
		$modulefilter_manager = ModuleFilterManager_Factory::get_instance();
		$not_excluded_module_sets = $modulefilter_manager->get_not_excluded_module_sets();
		if (!is_null($not_excluded_module_sets)) {
			
			// Print the settings id of each module. Then, a module can feed data to another one by sharing the same settings id (eg: POP_MODULE_BLOCK_USERAVATAR_EXECUTEUPDATE and POP_MODULE_BLOCK_USERAVATAR_UPDATE)
			$filteredsettings = array();
			foreach ($not_excluded_module_sets as $modules) {
				
				$filteredsettings[] = array_map(function($module) {

						$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();
						return $moduleprocessor_manager->get_processor($module)->get_settings_id($module);
					},
					$modules
				);
			}

			$meta['filteredmodules'] = $filteredsettings;
		}

		// Any errors? Send them back
		if (Utils::$errors) {

			if (count(Utils::$errors) > 1) {
				
				$meta[GD_URLPARAM_ERROR] = __('Ops, there were some errors:', 'pop-engine').implode('<br/>', Utils::$errors);
			}
			else {

				$meta[GD_URLPARAM_ERROR] = __('Ops, there was an error: ', 'pop-engine').Utils::$errors[0];
			}
		}

		return apply_filters(
			'\PoP\Engine\Engine:request-meta',
			$meta
		);
	}

	function get_session_meta() {

		return apply_filters(
			'\PoP\Engine\Engine:session-meta',
			array()
		);
	}

	function get_site_meta() {

		$meta = array();
		if (Utils::fetching_site()) {
		
			$vars = Engine_Vars::get_vars();

			// $meta['domain'] = get_site_url();
			$meta['sitename'] = get_bloginfo('name');

			$meta[GD_DATALOAD_PARAMS] = array();
			$pushurlprops = array();

			// Comment Leo 05/04/2017: Create the params array only in the fetching_site()
			// Before it was outside, and calling the initial-frames page brought params=[], 
			// and this was overriding the params in the topLevelFeedback removing all info there

			// Add the version to the topLevel feedback to be sent in the URL params
			$meta[GD_DATALOAD_PARAMS][GD_URLPARAM_VERSION] = pop_version();

			// Send the current selected theme back
			if ($vars['theme']) {
				$meta[GD_DATALOAD_PARAMS][GD_URLPARAM_THEME] = $vars['theme'];
			}
			if ($vars['thememode']) {
				$meta[GD_DATALOAD_PARAMS][GD_URLPARAM_THEMEMODE] = $vars['thememode'];
			}
			if ($vars['themestyle']) {
				$meta[GD_DATALOAD_PARAMS][GD_URLPARAM_THEMESTYLE] = $vars['themestyle'];
			}
			$meta[GD_DATALOAD_PARAMS][GD_URLPARAM_DATAOUTPUTMODE] = $vars['dataoutputmode'];

			if ($vars['format']) {
				$meta[GD_DATALOAD_PARAMS][GD_URLPARAM_SETTINGSFORMAT] = $vars['format'];
			}
			if ($vars['mangled']) {
				$meta[GD_DATALOAD_PARAMS][GD_URLPARAM_MANGLED] = $vars['mangled'];
			}
			if (Server\Utils::enable_config_by_params() && $vars['config']) {
				$meta[GD_DATALOAD_PARAMS][POP_URLPARAM_CONFIG] = $vars['config'];
			}

			// Theme: send only when it's not the default one (so the user can still see/copy/share the embed/print URL)
			if ($vars['theme'] && !$vars['theme-isdefault']) {
				$pushurlprops[GD_URLPARAM_THEME] = $vars['theme'];
			}
			if ($vars['thememode'] && !$vars['thememode-isdefault']) {
				$pushurlprops[GD_URLPARAM_THEMEMODE] = $vars['thememode'];
			}		
			if ($vars['themestyle'] && !$vars['themestyle-isdefault']) {
				$pushurlprops[GD_URLPARAM_THEMESTYLE] = $vars['themestyle'];
			}	

			if ($pushurlprops) {
				$meta[GD_DATALOAD_PUSHURLATTS] = $pushurlprops;
			}

			// Tell the front-end: are the results from the cache? Needed for the editor, to initialize it since WP will not execute the code
			if (!is_null($this->cachedsettings)) {
				$meta['cachedsettings'] = $this->cachedsettings;
			};	
		}
		return apply_filters(
			'\PoP\Engine\Engine:site-meta', 
			$meta
		);
	}

	private function combine_ids_datafields(&$ids_data_fields, $dataloader_name, $ids, $data_fields) {

		$ids_data_fields[$dataloader_name] = $ids_data_fields[$dataloader_name] ?? array();
		foreach ($ids as $id) {

			// Make sure to always add the 'id' data-field, since that's the key for the dbobject in the client database
			$ids_data_fields[$dataloader_name][$id] = $ids_data_fields[$dataloader_name][$id] ?? array('id');
			$ids_data_fields[$dataloader_name][$id] = array_unique(array_merge(
				$ids_data_fields[$dataloader_name][$id],
				$data_fields ?? array()
			));
		}
	}

	private function add_dataset_to_database(&$database, $database_key, $dataitems) {

		// Do not create the database key entry when there are no items, or it produces an error when deep merging the database object in the frontend with that from the response
		if (!$dataitems) {
			return;
		}

		// Save in the database under the corresponding database-key (this way, different dataloaders, like 'list-users' and 'author', 
		// can both save their results under database key 'users'			
		if (!$database[$database_key]) {
			$database[$database_key] = $dataitems;
		}
		else {
			// array_merge_recursive doesn't work as expected (it merges 2 hashmap arrays into an array, so then I manually do a foreach instead)
			foreach ($dataitems as $dbobject_id => $dbobject_values) {

				if (!$database[$database_key][$dbobject_id]) {
					$database[$database_key][$dbobject_id] = array();
				}

				$database[$database_key][$dbobject_id] = array_merge(
					$database[$database_key][$dbobject_id],
					$dbobject_values
				);
			}
		}
	}

	protected function get_interreferenced_module_fullpaths($module, &$props) {

		$paths = array();
		$this->add_interreferenced_module_fullpaths($paths, array(), $module, $props);
		return $paths;
	}

	private function add_interreferenced_module_fullpaths(&$paths, $module_path, $module, &$props) {

		$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();
		$processor = $moduleprocessor_manager->get_processor($module);

		$modulefilter_manager = ModuleFilterManager_Factory::get_instance();
		
		// If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
		if (!$modulefilter_manager->exclude_module($module, $props)) {
			
			// If the current module loads data, then add its path to the list
			if ($interreferenced_modulepath = $processor->get_data_feedback_interreferenced_modulepath($module, $props)) {

				$referenced_modulepath = ModulePathManager_Utils::stringify_module_path($interreferenced_modulepath);
				$paths[$referenced_modulepath] = $paths[$referenced_modulepath] ?? array();
				$paths[$referenced_modulepath][] = array_merge(
					$module_path, 
					array(
						$module
					)
				);
			}
		}

		$submodule_path = array_merge(
			$module_path,
			array(
				$module,
			)
		);
		
		// Propagate to its inner modules
		$submodules = $processor->get_descendant_modules($module);
		$submodules = $modulefilter_manager->remove_excluded_submodules($module, $submodules);
		
		// This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
		$module_path_manager = ModulePathManager_Factory::get_instance();
		$module_path_manager->prepare_for_propagation($module);
		foreach ($submodules as $submodule) {

			$this->add_interreferenced_module_fullpaths($paths, $submodule_path, $submodule, $props[$module][POP_PROPS_MODULES]);
		}
		$module_path_manager->restore_from_propagation($module);
	}

	protected function get_dataloading_module_fullpaths($module, &$props) {

		$paths = array();
		$this->add_dataloading_module_fullpaths($paths, array(), $module, $props);
		return $paths;
	}

	private function add_dataloading_module_fullpaths(&$paths, $module_path, $module, &$props) {

		$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();
		$processor = $moduleprocessor_manager->get_processor($module);

		$modulefilter_manager = ModuleFilterManager_Factory::get_instance();
		
		// If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
		if (!$modulefilter_manager->exclude_module($module, $props)) {
			
			// If the current module loads data, then add its path to the list
			if ($processor->get_dataloader($module)) {
				$paths[] = array_merge(
					$module_path, 
					array(
						$module
					)
				);
			}
		}

		$submodule_path = array_merge(
			$module_path,
			array(
				$module,
			)
		);
		
		// Propagate to its inner modules
		$submodules = $processor->get_descendant_modules($module);
		$submodules = $modulefilter_manager->remove_excluded_submodules($module, $submodules);
		
		// This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
		$module_path_manager = ModulePathManager_Factory::get_instance();
		$module_path_manager->prepare_for_propagation($module);
		foreach ($submodules as $submodule) {

			$this->add_dataloading_module_fullpaths($paths, $submodule_path, $submodule, $props[$module][POP_PROPS_MODULES]);
		}
		$module_path_manager->restore_from_propagation($module);
	}

	protected function assign_value_for_module(&$array, $module_path, $module, $key, $value) {

		$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();

		$array_pointer = &$array;
		foreach ($module_path as $submodule) {

			// Notice that when generating the array for the response, we don't use $module anymore, but $settings_id
			$submodule_settings_id = $moduleprocessor_manager->get_processor($submodule)->get_settings_id($submodule);

			// If the path doesn't exist, create it
			if (!isset($array_pointer[$submodule_settings_id][GD_JS_MODULES])) {
				$array_pointer[$submodule_settings_id][GD_JS_MODULES] = array();
			}

			// The pointer is the location in the array where the value will be set
			$array_pointer = &$array_pointer[$submodule_settings_id][GD_JS_MODULES];
		}

		$settings_id = $moduleprocessor_manager->get_processor($module)->get_settings_id($module);
		$array_pointer[$settings_id][$key] = $value;
	}

	protected function validate_checkpoints($checkpoints, $module) {

		global $gd_dataload_checkpointprocessor_manager;

		// Iterate through the list of all checkpoints, process all of them, if any produces an error, already return it
		foreach ($checkpoints as $checkpoint) {

			$result = $gd_dataload_checkpointprocessor_manager->process($checkpoint, $module);
			if (is_wp_error($result)) {
				return $result;
			}
		}

		return true;
	}

	protected function get_module_path_key($module_path, $module) {

		return $module.'-'.implode('.', $module_path);
	}

	// This function is not private, so it can be accessed by the automated emails to regenerate the html for each user
	function get_module_data($root_module, $root_model_props, $root_props) {

		global $pop_module_cachemanager;
		$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();

		$root_processor = $moduleprocessor_manager->get_processor($root_module);

		// From the state we know if to process static/staful content or both
		$vars = Engine_Vars::get_vars();
		$datasources = $vars['datasources'];
		$dataoutputmode = $vars['dataoutputmode'];

		$immutable_moduledata = $mutableonmodel_moduledata = $mutableonrequest_moduledata = array();
		$immutable_datasetmoduledata = $mutableonmodel_datasetmoduledata = $mutableonrequest_datasetmoduledata = array();
		$immutable_datasetmodulemeta = $mutableonmodel_datasetmodulemeta = $mutableonrequest_datasetmodulemeta = array();
		$this->dbdata = array();

		// Save all the BACKGROUND_LOAD urls to send back to the browser, to load immediately again (needed to fetch non-cacheable data-fields)
		$this->backgroundload_urls = array();

		// Load under global key (shared by all pagesections / blocks)
		$this->ids_data_fields = array();	

		// Allow PoP UserState to add the lazy-loaded userstate data triggers
		do_action(
			'\PoP\Engine\Engine:get_module_data:start',
			$root_module, 
			array(&$root_model_props), 
			array(&$root_props),
			array(&$this->helperCalculations),
			$this
		);	

		$use_cache = Server\Utils::use_cache();

		// First check if there's a cache stored
		if ($use_cache) {
			
			$immutable_data_properties = $pop_module_cachemanager->get_cache_by_model_instance(POP_CACHETYPE_STATICDATAPROPERTIES, true);
			$mutableonmodel_data_properties = $pop_module_cachemanager->get_cache_by_model_instance(POP_CACHETYPE_STATEFULDATAPROPERTIES, true);
		}

		// If there is no cached one, generate the props and cache it
		if (!$immutable_data_properties) {

			$immutable_data_properties = $root_processor->get_immutable_data_properties_datasetmoduletree($root_module, $root_model_props);
			$mutableonmodel_data_properties = $root_processor->get_mutableonmodel_data_properties_datasetmoduletree($root_module, $root_model_props);
			if ($use_cache) {
				$pop_module_cachemanager->store_cache_by_model_instance(POP_CACHETYPE_STATICDATAPROPERTIES, $immutable_data_properties, true);
				$pop_module_cachemanager->store_cache_by_model_instance(POP_CACHETYPE_STATEFULDATAPROPERTIES, $mutableonmodel_data_properties, true);
			}
		}

		$model_data_properties = array_merge_recursive(
			$immutable_data_properties,
			$mutableonmodel_data_properties
		);

		if ($datasources == GD_URLPARAM_DATASOURCES_ONLYMODEL) {

			$root_data_properties = $model_data_properties;
		}
		else {

			$mutableonrequest_data_properties = $root_processor->get_mutableonrequest_data_properties_datasetmoduletree($root_module, $root_props);
			$root_data_properties = array_merge_recursive(
				$model_data_properties,
				$mutableonrequest_data_properties
			);
		}

		// Get the list of all modules which calculate their data feedback using another module's results
		$interreferenced_modulefullpaths = $this->get_interreferenced_module_fullpaths($root_module, $root_props);

		// Get the list of all modules which load data, as a list of the module path starting from the top element (the entry module)
		$module_fullpaths = $this->get_dataloading_module_fullpaths($root_module, $root_props);

		$module_path_manager = ModulePathManager_Factory::get_instance();

		// The modules below are already included, so tell the filtermanager to not validate if they must be excluded or not
		$modulefilter_manager = ModuleFilterManager_Factory::get_instance();
		$modulefilter_manager->never_exclude(true);
		foreach ($module_fullpaths as $module_path) {

			// The module is the last element in the path. 
			// Notice that the module is removed from the path, providing the path to all its properties
			$module = array_pop($module_path);

			// Artificially set the current path on the path manager. It will be needed in get_datasetmeta, which calls get_dataload_source, which needs the current path
			$module_path_manager->set_propagation_current_path($module_path);

			// Data Properties: assign by reference, so that changes to this variable are also performed in the original variable
			$data_properties = &$root_data_properties;
			foreach ($module_path as $submodule) {
				$data_properties = &$data_properties[$submodule][GD_JS_MODULES];
			}
			$data_properties = &$data_properties[$module][POP_CONSTANT_DATAPROPERTIES];			
			$datasource = $data_properties[GD_DATALOAD_DATASOURCE];

			// If we are only requesting data from the model alone, and this dataloading module depends on mutableonrequest, then skip it
			if ($datasources == GD_URLPARAM_DATASOURCES_ONLYMODEL && $datasource == POP_DATALOAD_DATASOURCE_MUTABLEONREQUEST) {

				continue;
			}

			// Load data if the property Skip Data Load is not true
			$load_data = !$data_properties[GD_DATALOAD_SKIPDATALOAD];

			// ------------------------------------------
			// Checkpoint validation
			// ------------------------------------------
			// Load data if the checkpoint did not fail
			if ($load_data && $checkpoints = $data_properties[GD_DATALOAD_CHECKPOINTS]) {
				
				// Check if the module fails checkpoint validation. If so, it must not load its data or execute the actionexecuter					
				$checkpoint_validation = $this->validate_checkpoints($checkpoints, $module);
				$load_data = !is_wp_error($checkpoint_validation);
			}

			// The $props is directly moving the array to the corresponding path 
			$props = &$root_props;
			$model_props = &$root_model_props;
			foreach ($module_path as $submodule) {
				$props = &$props[$submodule][POP_PROPS_MODULES];
				$model_props = &$model_props[$submodule][POP_PROPS_MODULES];
			}

			if (in_array($datasource, array(
				POP_DATALOAD_DATASOURCE_IMMUTABLE,
				POP_DATALOAD_DATASOURCE_MUTABLEONMODEL,
			))) {
				$module_props = &$model_props;
			}
			elseif ($datasource == POP_DATALOAD_DATASOURCE_MUTABLEONREQUEST) {
				$module_props = &$props;
			}

			$processor = $moduleprocessor_manager->get_processor($module);

			// The module path key is used for storing temporary results for later retrieval
			$module_path_key = $this->get_module_path_key($module_path, $module);

			// If data is not loaded, then an empty array will be saved for the dbobject ids
			$dbobjectids = $dataset_meta = array();
			$executed = null;
			if ($load_data) {

				// ------------------------------------------
				// Action Executers
				// ------------------------------------------
				// Allow to plug-in functionality here (eg: form submission)
				// Execute at the very beginning, so the result of the execution can also be fetched later below
				// (Eg: creation of a new location => retrieving its data / Adding a new comment)
				// Pass data_properties so these can also be modified (eg: set id of newly created Location)
				if ($actionexecuter_name = $processor->get_actionexecuter($module)) {

					// Validate that the actionexecution must be triggered through its own checkpoints
					$execute = true;
					if ($actionexecution_checkpoints = $data_properties[GD_DATALOAD_ACTIONEXECUTIONCHECKPOINTS]) {
						
						// Check if the module fails checkpoint validation. If so, it must not load its data or execute the actionexecuter					
						$actionexecution_checkpoint_validation = $this->validate_checkpoints($actionexecution_checkpoints, $module);
						$execute = !is_wp_error($actionexecution_checkpoint_validation);
					}

					if ($execute) {
					
						$gd_dataload_actionexecution_manager = ActionExecution_Manager_Factory::get_instance();
						$actionexecuter = $gd_dataload_actionexecution_manager->get_actionexecuter($actionexecuter_name);
						$executed = $actionexecuter->execute($data_properties);
					}
				}

				// Allow modules to change their data_properties based on the actionexecution of previous modules. 
				$processor->prepare_data_properties_after_actionexecution($module, $module_props, $data_properties);

				// Re-calculate $data_load, it may have been changed by `prepare_data_properties_after_actionexecution`
				$load_data = !$data_properties[GD_DATALOAD_SKIPDATALOAD];
				if ($load_data) {

					// ------------------------------------------
					// Data Properties Query Args: add mutableonrequest data
					// ------------------------------------------
					// Execute and get the ids and the meta
					$dbobjectids = $processor->get_dbobject_ids($module, $module_props, $data_properties);

					// Store the ids under $data under key dataload_name => id
					$dataloader_name = $processor->get_dataloader($module);
					$data_fields = $data_properties['data-fields'] ?? array();
					$this->combine_ids_datafields($this->ids_data_fields, $dataloader_name, $dbobjectids, $data_fields);

					// Add the IDs to the possibly-already produced IDs for this dataloader/pageSection/Block
					$this->initialize_dataloader_entry($this->dbdata, $dataloader_name, $module_path_key);
					$this->dbdata[$dataloader_name][$module_path_key]['ids'] = array_merge(
						$this->dbdata[$dataloader_name][$module_path_key]['ids'],
						$dbobjectids
					);

					// The supplementary dbobject data is independent of the dataloader of the block.
					// Even if it is STATIC, the extend ids must be loaded. That's why we load the extend now,
					// Before checking below if the checkpoint failed or if the block content must not be loaded.
					// Eg: Locations Map for the Create Individual Profile: it allows to pre-select locations,  
					// these ones must be fetched even if the block has a static dataloader
					// If it has extend, add those ids under its dataloader_name
					$dataload_extend_settings = $processor->get_model_supplementary_dbobjectdata_moduletree($module, $model_props);
					if ($datasource == POP_DATALOAD_DATASOURCE_MUTABLEONREQUEST) {
						
						$dataload_extend_settings = array_merge_recursive(
							$dataload_extend_settings,
							$processor->get_mutableonrequest_supplementary_dbobjectdata_moduletree($module, $props)
						);	
					}
					foreach ($dataload_extend_settings as $extend_dataloader_name => $extend_data_properties) {
						
						// Get the info for the subcomponent dataloader
						$extend_data_fields = $extend_data_properties['data-fields'] ? $extend_data_properties['data-fields'] : array();
						$extend_ids = $extend_data_properties['ids'];
								
						$this->combine_ids_datafields($this->ids_data_fields, $extend_dataloader_name, $extend_ids, $extend_data_fields);					

						// This is needed to add the dataloader-extend IDs, for if nobody else creates an entry for this dataloader
						$this->initialize_dataloader_entry($this->dbdata, $extend_dataloader_name, $module_path_key);
					}

					// Keep iterating for its subcomponents
					$this->integrate_subcomponent_data_properties($this->dbdata, $data_properties, $dataloader_name, $module_path_key);
				}
			}

			// Save the results on either the static or mutableonrequest branches
			if ($datasource == POP_DATALOAD_DATASOURCE_IMMUTABLE)  {
				$datasetmoduledata = &$immutable_datasetmoduledata;
				$datasetmodulemeta = &$immutable_datasetmodulemeta;
				$this->moduledata = &$immutable_moduledata;
			}
			elseif ($datasource == POP_DATALOAD_DATASOURCE_MUTABLEONMODEL)  {
				$datasetmoduledata = &$mutableonmodel_datasetmoduledata;
				$datasetmodulemeta = &$mutableonmodel_datasetmodulemeta;
				$this->moduledata = &$mutableonmodel_moduledata;
			}
			elseif ($datasource == POP_DATALOAD_DATASOURCE_MUTABLEONREQUEST) {
				$datasetmoduledata = &$mutableonrequest_datasetmoduledata;
				$datasetmodulemeta = &$mutableonrequest_datasetmodulemeta;
				$this->moduledata = &$mutableonrequest_moduledata;
			}

			// Integrate the dbobjectids into $datasetmoduledata
			// ALWAYS print the $dbobjectids, even if its an empty array. This to indicate that this is a dataloading module, so the application in the frontend knows if to load a new batch of dbobjectids, or reuse the ones from the previous module when iterating down
			if (!is_null($datasetmoduledata)) {
			
				$this->assign_value_for_module($datasetmoduledata, $module_path, $module, POP_CONSTANT_DBOBJECTIDS, $dbobjectids);
			}

			// Save the meta into $datasetmodulemeta
			if (!is_null($datasetmodulemeta)) {
			
				if ($dataset_meta = $processor->get_datasetmeta($module, $module_props, $data_properties, $checkpoint_validation, $executed, $dbobjectids)) {
					$this->assign_value_for_module($datasetmodulemeta, $module_path, $module, POP_CONSTANT_META, $dataset_meta);
				}
			}

			// Integrate the feedback into $moduledata
			$this->process_and_add_module_data($module_path, $module, $module_props, $data_properties, $checkpoint_validation, $executed, $dbobjectids);

			// Allow other modules to produce their own feedback using this module's data results
			if ($referencer_modulefullpaths = $interreferenced_modulefullpaths[ModulePathManager_Utils::stringify_module_path(array_merge($module_path, array($module)))]) {

				foreach ($referencer_modulefullpaths as $referencer_modulepath) {

					$referencer_module = array_pop($referencer_modulepath);

					$referencer_props = &$root_props;
					$referencer_model_props = &$root_model_props;
					foreach ($referencer_modulepath as $submodule) {
						$referencer_props = &$referencer_props[$submodule][POP_PROPS_MODULES];
						$referencer_model_props = &$referencer_model_props[$submodule][POP_PROPS_MODULES];
					}

					if (in_array($datasource, array(
						POP_DATALOAD_DATASOURCE_IMMUTABLE,
						POP_DATALOAD_DATASOURCE_MUTABLEONMODEL,
					))) {
						$referencer_module_props = &$referencer_model_props;
					}
					elseif ($datasource == POP_DATALOAD_DATASOURCE_MUTABLEONREQUEST) {
						$referencer_module_props = &$referencer_props;
					}
					$this->process_and_add_module_data($referencer_modulepath, $referencer_module, $referencer_module_props, $data_properties, $checkpoint_validation, $executed, $dbobjectids);
				}
			}

			// Incorporate the background URLs
			$this->backgroundload_urls = array_merge(
				$this->backgroundload_urls,
				$processor->get_backgroundurls_mergeddatasetmoduletree($module, $module_props, $data_properties, $checkpoint_validation, $executed, $dbobjectids)
			);

			// Allow PoP UserState to add the lazy-loaded userstate data triggers
			do_action(
				'\PoP\Engine\Engine:get_module_data:dataloading-module',
				$module, 
				array(&$module_props), 
				array(&$data_properties), 
				$checkpoint_validation, 
				$executed, 
				$dbobjectids,
				array(&$this->helperCalculations),
				$this
			);
		}

		// Reset the filtermanager state and the pathmanager current path
		$modulefilter_manager->never_exclude(false);
		$module_path_manager->set_propagation_current_path();

		$ret = array();

		$dataoutputitems = $vars['dataoutputitems'];
		if (in_array(GD_URLPARAM_DATAOUTPUTITEMS_MODULEDATA, $dataoutputitems)) {

			// If there are multiple URIs, then the results must be returned under the corresponding $model_instance_id for "mutableonmodel", and $url for "mutableonrequest"
			list($has_extra_uris, $model_instance_id, $current_uri) = $this->list_extra_uri_vars();

			if ($dataoutputmode == GD_URLPARAM_DATAOUTPUTMODE_SPLITBYSOURCES) {
				if ($immutable_moduledata) {
					$ret['moduledata']['immutable'] = $immutable_moduledata;
				}
				if ($mutableonmodel_moduledata) {
					$ret['moduledata']['mutableonmodel'] = $has_extra_uris ? array($model_instance_id => $mutableonmodel_moduledata) : $mutableonmodel_moduledata;
				}
				if ($mutableonrequest_moduledata) {
					$ret['moduledata']['mutableonrequest'] = $has_extra_uris ? array($current_uri => $mutableonrequest_moduledata) : $mutableonrequest_moduledata;
				}
				if ($immutable_datasetmoduledata) {
					$ret['datasetmoduledata']['immutable'] = $immutable_datasetmoduledata;
				}
				if ($mutableonmodel_datasetmoduledata) {
					$ret['datasetmoduledata']['mutableonmodel'] = $has_extra_uris ? array($model_instance_id => $mutableonmodel_datasetmoduledata) : $mutableonmodel_datasetmoduledata;
				}
				if ($mutableonrequest_datasetmoduledata) {
					$ret['datasetmoduledata']['mutableonrequest'] = $has_extra_uris ? array($current_uri => $mutableonrequest_datasetmoduledata) : $mutableonrequest_datasetmoduledata;
				}
				if ($immutable_datasetmodulemeta) {
					$ret['datasetmodulemeta']['immutable'] = $immutable_datasetmodulemeta;
				}
				if ($mutableonmodel_datasetmodulemeta) {
					$ret['datasetmodulemeta']['mutableonmodel'] = $has_extra_uris ? array($model_instance_id => $mutableonmodel_datasetmodulemeta) : $mutableonmodel_datasetmodulemeta;
				}
				if ($mutableonrequest_datasetmodulemeta) {
					$ret['datasetmodulemeta']['mutableonrequest'] = $has_extra_uris ? array($current_uri => $mutableonrequest_datasetmodulemeta) : $mutableonrequest_datasetmodulemeta;
				}
			}
			elseif ($dataoutputmode == GD_URLPARAM_DATAOUTPUTMODE_COMBINED) {

				// If everything is combined, then it belongs under "mutableonrequest"
				if ($combined_moduledata = array_merge_recursive(
					$immutable_moduledata ?? array(),
					$mutableonmodel_moduledata ?? array(),
					$mutableonrequest_moduledata ?? array()
				)) {
					$ret['moduledata'] = $has_extra_uris ? array($current_uri => $combined_moduledata) : $combined_moduledata;
				}
				if ($combined_datasetmoduledata = array_merge_recursive(
					$immutable_datasetmoduledata ?? array(),
					$mutableonmodel_datasetmoduledata ?? array(),
					$mutableonrequest_datasetmoduledata ?? array()
				)) {
					$ret['datasetmoduledata'] = $has_extra_uris ? array($current_uri => $combined_datasetmoduledata) : $combined_datasetmoduledata;
				}
				if ($combined_datasetmodulemeta = array_merge_recursive(
					$immutable_datasetmodulemeta ?? array(),
					$mutableonmodel_datasetmodulemeta ?? array(),
					$mutableonrequest_datasetmodulemeta ?? array()
				)) {
					$ret['datasetmodulemeta'] = $has_extra_uris ? array($current_uri => $combined_datasetmodulemeta) : $combined_datasetmodulemeta;
				}
			}
		}

		// Allow PoP UserState to add the lazy-loaded userstate data triggers
		do_action(
			'\PoP\Engine\Engine:get_module_data:end',
			$root_module, 
			array(&$root_model_props), 
			array(&$root_props),
			array(&$this->helperCalculations),
			$this
		);

		return $ret;
	}

	function get_databases() {
		
		global $gd_dataload_manager, $gd_dataquery_manager;
		
		$vars = Engine_Vars::get_vars();
		$formatter = Utils::get_datastructure_formatter();

		// Save all database elements here, under dataloader
		$databases = array();
		$this->nocache_fields = array();
		$format = $vars['format'];

		// Keep an object with all fetched IDs/fields for each dataloader. Then, we can keep using the same dataloader as subcomponent,
		// but we need to avoid fetching those DB objects that were already fetched in a previous iteration
		$already_loaded_ids_data_fields = array();
		$subcomponent_data_fields = array();

		// Iterate while there are dataloaders with data to be processed
		while (!empty($this->ids_data_fields)) {

			// Move the pointer to the first element, and get it
			reset($this->ids_data_fields);
			$dataloader_name = key($this->ids_data_fields);
			$dataloader_ids_data_fields = $this->ids_data_fields[$dataloader_name];

			// Remove the dataloader element from the array, so it doesn't process it anymore
			// Do it immediately, so that subcomponents can load new IDs for this current dataloader (eg: posts => related)
			unset($this->ids_data_fields[$dataloader_name]);

			// If no ids to execute, then skip
			if (empty($dataloader_ids_data_fields)) {
				continue;
			}

			// Store the loaded IDs/fields in an object, to avoid fetching them again in later iterations on the same dataloader
			$already_loaded_ids_data_fields[$dataloader_name] = $already_loaded_ids_data_fields[$dataloader_name] ?? array();
			$already_loaded_ids_data_fields[$dataloader_name] = array_merge_recursive(
				$already_loaded_ids_data_fields[$dataloader_name],
				$dataloader_ids_data_fields
			);

			$dataloader = $gd_dataload_manager->get($dataloader_name);
			$database_key = $dataloader->get_database_key();

			// Execute the dataloader for all combined ids
			$resultset = $dataloader->get_data($dataloader_ids_data_fields);
			$dataitems = $dataloader->get_dataitems($formatter, $resultset, $dataloader_ids_data_fields);

			// Save in the database under the corresponding database-key (this way, different dataloaders, like 'list-users' and 'author', 
			// can both save their results under database key 'users'
			// Plugin PoP User Login: Also save those results which depend on the logged-in user. These are treated separately because:
			// 1: They contain personal information, so it must be erased from the front-end as soon as the user logs out
			// 2: These results make the page state-full, so this page is not cacheable
			// By splitting the results into state-full and state-less, we can split all functionality into cacheable and non-cacheable,
			// thus caching most of the website even for logged-in users
			foreach ($dataitems['dbitems'] as $dbname => $dbitems) {
				$this->add_dataset_to_database($databases[$dbname], $database_key, $dbitems);
			}

			// Keep the list of elements that must be retrieved once again from the server
			if ($dataquery_name = $dataloader->get_dataquery()) {
				
				$dataquery = $gd_dataquery_manager->get($dataquery_name);
				$objectid_fieldname = $dataquery->get_objectid_fieldname();
				
				// Force retrieval of data from the server. Eg: recommendpost-count
				$forceserverload_fields = $dataquery->get_nocachefields();
				
				// Lazy fields. Eg: comments
				$lazylayouts = $dataquery->get_lazylayouts();
				$lazyload_fields = array_keys($lazylayouts);
				
				// Store the intersected fields and the corresponding ids
				$forceserverload = array(
					'ids' => array(),
					'fields' => array()
				);	
				$lazyload = array(
					'ids' => array(),
					'layouts' => array()
				);	

				// Compare the fields in the result dbobjectids, with the dataquery's specified list of fields that must always be retrieved from the server
				// (eg: comment-count, since adding a comment doesn't delete the cache)
				foreach ($dataitems['dbobjectids'] as $dataitem_id) {

					// Get the fields requested to that dataitem, for both the database and user database
					$dataitem_fields = array_merge(
						array_keys($dataitems['dataitems'][$dataitem_id] ?? array()),
						array_keys($dataitems['user-dataitems'][$dataitem_id] ?? array())
					);

					// Intersect these with the fields that must be loaded from server
					// Comment Leo 31/03/2017: do it only if we are not currently in the noncacheable_page 
					// If we are, then we came here loading a backgroundload-url, and we don't need to load it again
					// Otherwise, it would create an infinite loop, since the fields loaded here are, exactly, those defined in the noncacheable_fields
					// Eg: https://www.mesym.com/en/loaders/posts/data/?pid[0]=21636&pid[1]=21632&pid[2]=21630&pid[3]=21628&pid[4]=21624&pid[5]=21622&fields[0]=recommendpost-count&fields[1]=recommendpost-count-plus1&fields[2]=userpostactivity-count&format=updatedata
					if (!Utils::is_page($dataquery->get_noncacheable_page())) {
						if ($intersect = array_values(array_intersect($dataitem_fields, $forceserverload_fields))) {

							$forceserverload['ids'][] = $dataitem_id;
							$forceserverload['fields'] = array_merge(
								$forceserverload['fields'],
								$intersect
							);
						}
					}

					// Intersect these with the lazyload fields
					if ($intersect = array_values(array_intersect($dataitem_fields, $lazyload_fields))) {

						$lazyload['ids'][] = $dataitem_id;
						foreach ($intersect as $field) {
							
							// Get the layout for the current format, if it exists, or the default one if not
							$lazyload['layouts'][] = $lazylayouts[$field][$format] ?? $lazylayouts[$field]['default'];
						}
					}
				}
				if ($forceserverload['ids']) {

					$forceserverload['fields'] = array_unique($forceserverload['fields']);

					$url = get_permalink($dataquery->get_noncacheable_page());
					$url = add_query_arg($objectid_fieldname, $forceserverload['ids'], $url);
					$url = add_query_arg(GD_URLPARAM_FIELDS, $forceserverload['fields'], $url);
					$url = add_query_arg(GD_URLPARAM_FORMAT, POP_FORMAT_FIELDS, $url);
					$this->backgroundload_urls[urldecode($url)] = array(POP_TARGET_MAIN);

					// Keep the nocache fields to remove those from the code when generating the ETag
					$this->nocache_fields = array_merge(
						$this->nocache_fields,
						$forceserverload['fields']
					);
				}
				if ($lazyload['ids']) {

					$lazyload['layouts'] = array_unique($lazyload['layouts']);

					$url = get_permalink($dataquery->get_cacheable_page());
					$url = add_query_arg($objectid_fieldname, $lazyload['ids'], $url);
					$url = add_query_arg(GD_URLPARAM_LAYOUTS, $lazyload['layouts'], $url);
					$url = add_query_arg(GD_URLPARAM_FORMAT, POP_FORMAT_LAYOUTS, $url);
					$this->backgroundload_urls[urldecode($url)] = array(POP_TARGET_MAIN);
				}
			}

			foreach ($this->dbdata[$dataloader_name] as $module_path_key => $dataloader_data) {

				// Remove the data immediately, so that subcomponents with the same dataloader can load their own data
				unset($this->dbdata[$dataloader_name][$module_path_key]);
				
				// Check if it has subcomponents, and then bring this data				
				if ($subcomponents_data_properties = $dataloader_data['subcomponents']) {

					$dataloader_ids = $dataloader_data['ids'];
					foreach ($subcomponents_data_properties as $subcomponent_data_field => $subcomponent_dataloder_data_properties) {
						foreach ($subcomponent_dataloder_data_properties as $subcomponent_dataloader_name => $subcomponent_data_properties) {
							
							// The array_merge_recursive when there are at least 2 levels will make the data_fields to be duplicated, so remove duplicates now
							if ($subcomponent_data_fields = array_unique($subcomponent_data_properties['data-fields'] ?? array())) {

								$subcomponent_already_loaded_ids_data_fields = array();
								if ($already_loaded_ids_data_fields && $already_loaded_ids_data_fields[$subcomponent_dataloader_name]) {
									$subcomponent_already_loaded_ids_data_fields = $already_loaded_ids_data_fields[$subcomponent_dataloader_name];
								}

								foreach ($dataloader_ids as $id) {
							
									// $databases may contain more the 1 DB shipped by pop-engine/ ("primary"). Eg: PoP User Login adds db "userstate"
									// Fetch the field_ids from all these DBs
									$field_ids = array();
									foreach ($databases as $dbname => $database) {
										
										if ($database_field_ids = $database[$database_key][$id][$subcomponent_data_field]) {

											$field_ids = array_merge(
												$field_ids,
												is_array($database_field_ids) ? $database_field_ids : array($database_field_ids)
											);
										}
									}
									if ($field_ids) {

										foreach ($field_ids as $field_id) {

											// Do not add again the IDs/Fields already loaded
											if ($subcomponent_already_loaded_data_fields = $subcomponent_already_loaded_ids_data_fields[$field_id]) {

												$id_subcomponent_data_fields = array_values(array_diff(
													$subcomponent_data_fields,
													$subcomponent_already_loaded_data_fields
												));
											}
											else {

												$id_subcomponent_data_fields = $subcomponent_data_fields;
											}

											if ($id_subcomponent_data_fields) {

												$this->combine_ids_datafields($this->ids_data_fields, $subcomponent_dataloader_name, array($field_id), $id_subcomponent_data_fields);
												$this->initialize_dataloader_entry($this->dbdata, $subcomponent_dataloader_name, $module_path_key);
												$this->dbdata[$subcomponent_dataloader_name][$module_path_key]['ids'][] = $field_id;
											}
										}
									}
								}

								if ($this->dbdata[$subcomponent_dataloader_name][$module_path_key]) {
									
									$this->dbdata[$subcomponent_dataloader_name][$module_path_key]['ids'] = array_unique($this->dbdata[$subcomponent_dataloader_name][$module_path_key]['ids']);				
									$this->dbdata[$subcomponent_dataloader_name][$module_path_key]['data-fields'] = array_unique($this->dbdata[$subcomponent_dataloader_name][$module_path_key]['data-fields']);				
								}
							}	
						}	
					}				
				}			
			}	
		}

		$ret = array();
		
		// Do not add the "database", "userstatedatabase" entries unless there are values in them
		// Otherwise, it messes up integrating the current databases in the frontend with those from the response when deep merging them
		if ($databases) {
			$ret['databases'] = $databases;
		}

		return $ret;
	}

	protected function process_and_add_module_data($module_path, $module, &$props, $data_properties, $checkpoint_validation, $executed, $dbobjectids) {

		$moduleprocessor_manager = ModuleProcessor_Manager_Factory::get_instance();
		$processor = $moduleprocessor_manager->get_processor($module);
		
		// Integrate the feedback into $moduledata
		if (!is_null($this->moduledata)) {

			$moduledata = &$this->moduledata;

			// Add the feedback into the object
			if ($feedback = $processor->get_data_feedback_datasetmoduletree($module, $props, $data_properties, $checkpoint_validation, $executed, $dbobjectids)) {

				// Advance the position of the array into the current module
				foreach ($module_path as $submodule) {
					$submodule_settings_id = $moduleprocessor_manager->get_processor($submodule)->get_settings_id($submodule);
					$moduledata[$submodule_settings_id][GD_JS_MODULES] = $moduledata[$submodule_settings_id][GD_JS_MODULES] ?? array();
					$moduledata = &$moduledata[$submodule_settings_id][GD_JS_MODULES];
				}
				// Merge the feedback in
				$moduledata = array_merge_recursive(
					$moduledata,
					$feedback
				);
			}
		}
	}

	private function initialize_dataloader_entry(&$dbdata, $dataloader_name, $module_path_key) {

		if (is_null($dbdata[$dataloader_name][$module_path_key])) {
			$dbdata[$dataloader_name][$module_path_key] = array(
				'ids' => array(),
				'data-fields' => array(),
				'subcomponents' => array(),
			);
		}
	}

	private function integrate_subcomponent_data_properties(&$dbdata, $data_properties, $dataloader_name, $module_path_key) {

		// Process the subcomponents
		// If it has subcomponents, bring its data to, after executing get_data on the primary dataloader, execute get_data also on the subcomponent dataloader
		if ($subcomponents_data_properties = $data_properties['subcomponents']) {
			
			// Merge them into the data
			$dbdata[$dataloader_name][$module_path_key]['subcomponents'] = array_merge_recursive(
				$dbdata[$dataloader_name][$module_path_key]['subcomponents'],
				$subcomponents_data_properties
			);
				
			foreach ($subcomponents_data_properties as $subcomponent_data_field => $subcomponent_dataloader_data_properties) {
				
				foreach ($subcomponent_dataloader_data_properties as $subcomponent_dataloader_name => $subcomponent_data_properties) {
					
					// Get the info for the subcomponent dataloader
					$subcomponent_data_fields = $subcomponent_data_properties['data-fields'];
					
					// Add to data (but do not bring the ids yet, this comes as a result of get_data on the parent dataloader)
					$this->initialize_dataloader_entry($dbdata, $subcomponent_dataloader_name, $module_path_key);
					$dbdata[$subcomponent_dataloader_name][$module_path_key]['data-fields'] = array_unique(array_merge(
						$dbdata[$subcomponent_dataloader_name][$module_path_key]['data-fields'],
						$subcomponent_data_fields ?? array()
					));

					// Recursion: Keep including levels below
					if ($subcomponent_subcomponents = $subcomponent_data_properties['subcomponents']) {
						
						$dbdata[$subcomponent_dataloader_name][$module_path_key]['subcomponents'] = array_merge_recursive(
							$dbdata[$subcomponent_dataloader_name][$module_path_key]['subcomponents'],
							$subcomponent_subcomponents
						);
						$this->integrate_subcomponent_data_properties($dbdata, $subcomponent_data_properties, $subcomponent_dataloader_name, $module_path_key);
					}
				}
			}
		}
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Engine();
