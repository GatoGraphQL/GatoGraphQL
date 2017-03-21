<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATECOMPONENT_CONFIGURATION_TYPE_MAP', 'map');
define ('GD_TEMPLATECOMPONENT_CONFIGURATION_TYPE_ARRAY', 'array');
define ('GD_DEFINITIONS_FILTEROBJECT', 'filter-object');

define ('GD_TEMPLATEBLOCKSETTINGS_MAIN', 'main');
define ('GD_TEMPLATEBLOCKSETTINGS_INDEPENDENT', 'independent');
define ('GD_TEMPLATEBLOCKSETTINGS_REPLICABLE', 'replicable');
define ('GD_TEMPLATEBLOCKSETTINGS_FRAME', 'frame');
define ('GD_TEMPLATEBLOCKSETTINGS_FRAMEREPLICABLE', 'framereplicable');
define ('GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP', 'blockgroup');
define ('GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUPINDEPENDENT', 'blockgroup-independent');
define ('GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUPREPLICABLE', 'blockgroup-replicable');
// define ('GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUPFRAME', 'blockgroup-frame');

class PoP_Engine {

	var $json, $atts;

	function __construct() {

		// Set myself as the PoP_Engine instance in the Factory
		PoP_Engine_Factory::set_instance($this);
	}

	function get_toplevel_template_id() {

		if (is_home() || is_front_page()) {

			return GD_TEMPLATE_TOPLEVEL_HOME;
		}
		elseif (is_tag()) {

			return GD_TEMPLATE_TOPLEVEL_TAG;
		}
		elseif (is_page()) {
			
			return GD_TEMPLATE_TOPLEVEL_PAGE;
		}
		elseif (is_single()) {
			
			return GD_TEMPLATE_TOPLEVEL_SINGLE;
		}
		elseif (is_author()) {
			
			return GD_TEMPLATE_TOPLEVEL_AUTHOR;
		}
		elseif (is_404()) {
			
			return GD_TEMPLATE_TOPLEVEL_404;
		}

		return null;
	}

	function send_etag_header($json) {

		// Set the ETag-header? This is needed for the Service Workers
		// Comment Leo 01/03/2017: it had been sent only for JSON requests, but also added for the first, loading_frame() request,
		// so that also that one can be properly validated
		// Comment Leo 01/03/2017: Always send the ETag, because it's needed to use together with the Control-Cache header
		// to know when to refetch data from the server: https://developers.google.com/web/fundamentals/performance/optimizing-content-efficiency/http-caching
		if (true/*apply_filters('PoP_Engine:output_json:add_etag_header', false)*/) {

			// The same page will have different hashs only because of those random elements added each time,
			// such as the unique_id and the current_time. So remove these to generate the hash
			$differentiators = array(
				POP_CONSTANT_UNIQUE_ID,
				POP_CONSTANT_CURRENTTIMESTAMP,
				POP_CONSTANT_RAND,
				POP_CONSTANT_TIME,
			);
			$commoncode = str_replace($differentiators, '', $json['json']);

			// Also replace all those tags with content that, even if it's different, should not alter the output
			// Eg: comments-count. Because adding a comment does not delete the cache, then the comments-count is allowed
			// to be shown stale. So if adding a new comment, there's no need for the user to receive the
			// "This page has been updated, click here to refresh it." notification
			// Because we already got the JSON, then remove entries of the type:
			// "userpostactivity-count":1, (if there are more elements after)
			// and
			// "userpostactivity-count":1
			$nocache_fields = $json['nocache-fields'];
			$commoncode = preg_replace('/"('.implode('|', $nocache_fields).')":[0-9]+,?/', '', $commoncode);
			header("ETag: ".wp_hash($commoncode));
		}
	}

	function get_output_items() {

		// What items to fetch
		$vars = GD_TemplateManager_Utils::get_vars();
		if ($vars['fetching-json']) {

			// Give different response depending on the target: Hierarchy / Block / Item
			if ($vars['fetching-json-settingsdata']) {

				// PageSection target: bring everything
				return array('settings', 'data');
			}
			elseif ($vars['fetching-json-settings']) {
				
				// Bring only the settings
				return array('settings');
			}
			elseif ($vars['fetching-json-data']) {
				
				// Block target: bring only the data, no need for the settings
				return array('data');
			}
		}

		// Bring everything
		return array('settings', 'data');
	}

	function generate_json() {

		do_action('PoP_Engine:beginning');	

		$vars = GD_TemplateManager_Utils::get_vars();
		if ($vars['fetching-json']) {

			do_action('PoP_Engine:output_json:beginning');	
		}
		else {

			do_action('PoP_Engine:output:beginning');
		}

		$template_id = $this->get_toplevel_template_id();
		$output_items = $this->get_output_items();
		if (!($this->json = $this->get_json($template_id, $output_items))) {

			return;
		}

		// Send the ETag-header
		$this->send_etag_header($this->json);
	}

	function output() {
		
		if (!$this->json) {

			return;
		}

		// // If the current request is done by a crawler, then directly return the result for the crawler and nothing else.
		// // Nothing to fear, since WP Super Cache will not cache this result
		// // If it is not the crawler, then do include the crawlable-data in the results, because the WP Super Cache will return 
		// // the cached version of the page if it exists
		// if (!GD_TemplateManager_Utils::is_search_engine()) {

		$output = 
			// Tell the front-end if the settings are from the cache
			'<script type="text/javascript">var POP_CACHED_SETTINGS = %s;</script>'.
			// Template Hierarchy JSON Settings and Data
			'<script type="application/json" id="%s">%s</script>'
		;
		printf(
			$output,
			$this->json['cachedsettings'] ? "true" : "false",
			GD_TEMPLATEID_TOPLEVEL_SETTINGSID,
			$this->json['json']
		);
		// }

		// Allow extra functionalities. Eg: Save the logged-in user meta information
		do_action('PoP_Engine:output:end');
		do_action('PoP_Engine:rendered');
	}

	function output_end() {
	
		// Implemented in pop-frontendengine
	}

	function output_json() {

		// Indicate that this is a json response in the HTTP Header
		header('Content-type: application/json');
		
		if (!$this->json) {

			return;
		}

		// Skip returning 'crawlable-data', no need for JSON request
		echo $this->json['json'];

		// Allow extra functionalities. Eg: Save the logged-in user meta information
		do_action('PoP_Engine:output_json:end');
		do_action('PoP_Engine:rendered');
	}

	function check_redirect($addoutput) {

		global $gd_template_processor_manager;
		$template_id = $this->get_toplevel_template_id();

		if (!($processor = $gd_template_processor_manager->get_processor($template_id))) {
		
			return;
		}

		// Check redirection
		if ($redirect = $processor->get_redirect_url($template_id)) {

			if ($addoutput) {
				$redirect = add_query_arg(GD_URLPARAM_OUTPUT, GD_URLPARAM_OUTPUT_JSON, $redirect);

				if ($target = $_REQUEST[GD_URLPARAM_TARGET]) {

					$redirect = add_query_arg(GD_URLPARAM_TARGET, $target, $redirect);
				}
				if ($datastructure = $_REQUEST[GD_URLPARAM_DATASTRUCTURE]) {

					$redirect = add_query_arg(GD_URLPARAM_DATASTRUCTURE, $datastructure, $redirect);
				}
				if ($mangled = $_REQUEST[POP_URLPARAM_MANGLED]) {

					$redirect = add_query_arg(POP_URLPARAM_MANGLED, $mangled, $redirect);
				}
			}

			wp_redirect($redirect); exit;
		}
	}

	protected function get_json($template_id, $output_items) {

		global $gd_template_processor_manager, $gd_template_cachemanager;
		if (!($processor = $gd_template_processor_manager->get_processor($template_id))) {
		
			return;
		}

		$formatter = GD_TemplateManager_Utils::get_datastructure_formatter();
		$request = $_REQUEST;

		// // If passing no setting items, then bring everything
		// if (empty($output_items)) {

		// 	$output_items = array(
		// 		'settings',
		// 		'data'
		// 	);
		// }

		$initial_atts = array();
		
		// Important: cannot use it if doing POST, because the request may have to be handled by a different block than the one whose data was cached
		// Eg: doing GET on /add-post/ will show the form BLOCK_ADDPOST_CREATE, but doing POST on /add-post/ will bring the action ACTION_ADDPOST_CREATE
		// First check if there's a cache stored
		if (!doing_post() && PoP_ServerUtils::use_cache()) {
			
			$atts = $gd_template_cachemanager->get_cache($template_id, POP_CACHETYPE_ATTS, true);

			// If there is no cached one, generate the atts and cache it
			if (!$atts) {

				$atts = $processor->init_atts($template_id, $initial_atts);
				$gd_template_cachemanager->store_cache($template_id, POP_CACHETYPE_ATTS, $atts, true);
			}
		}
		else {
			$atts = $processor->init_atts($template_id, $initial_atts);
		}

		// Save it to be used by the children class
		$this->atts = $atts;
				
		// Templates: What templates must be executed after call to loadMore is back with data:
		// CB: list of templates to merge
		$cachedsettings = false;
		if (in_array('settings', $output_items)) {

			// Important: cannot use it if doing POST, because the request may have to be handled by a different block than the one whose data was cached
			// Eg: doing GET on /add-post/ will show the form BLOCK_ADDPOST_CREATE, but doing POST on /add-post/ will bring the action ACTION_ADDPOST_CREATE
			// First check if there's a cache stored
			if (!doing_post() && PoP_ServerUtils::use_cache()) {
				
				$settings = $gd_template_cachemanager->get_cache($template_id, POP_CACHETYPE_SETTINGS);

				// If there is no cached one, generate the configuration and cache it
				if ($settings) {

					$cachedsettings = true;
				}
				else {

					$settings = json_encode($this->get_json_settings($template_id, $atts));
					$gd_template_cachemanager->store_cache($template_id, POP_CACHETYPE_SETTINGS, $settings);
				}
			}
			else {

				$settings = json_encode($this->get_json_settings($template_id, $atts));
			}

			$runtimesettings = $this->get_json_runtimesettings($template_id, $atts);
		}

		// Data = dataset (data-ids) + feedback + database
		// + Search Engine Crawlable data
		if (in_array('data', $output_items)) {

			$data = $this->get_data($template_id, $processor, $atts, $formatter, $request);
		}

		$json = $formatter->get_formatted_data($settings, $runtimesettings, $data);

		// Tell the front-end: are the results from the cache? Needed for the editor, to initialize it since WP will not execute the code
		$json['cachedsettings'] = $cachedsettings;

		// Give the nocache-fields back also, to properly generate the ETag
		$json['nocache-fields'] = $data['nocache-fields'];

		return $json;
	}

	protected function get_json_runtimesettings($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$processor = $gd_template_processor_manager->get_processor($template_id);

		$json_runtimesettings = array();

		// Query: Each block has a query indicating how to process loadMore ajax call
		$json_runtimesettings['query-url'] = $processor->get_query_url($template_id, $atts);
		$json_runtimesettings['configuration'] = $processor->get_template_runtimeconfigurations($template_id, $atts);
		
		return $json_runtimesettings;
	}

	protected function get_json_settings($template_id, $atts) {
	
		global $gd_template_processor_manager, $gd_template_cachemanager;

		$processor = $gd_template_processor_manager->get_processor($template_id);
		$json_settings = array();

		// Templates: What templates must be executed after call to loadMore is back with data:
		// CB: list of templates to merge
		$json_settings['db-keys'] = $processor->get_database_keys($template_id, $atts);
		$json_settings['configuration'] = $processor->get_template_configurations($template_id, $atts);

		// Comment Leo 12/06/2016: Not needed, since we can't merge this info into M.ALLOWED_URLS because not all query-domains are initially loaded, so for those not loaded the "click" will not be intercepted
		// $json_settings['query-domains'] = $processor->get_query_domains($template_id, $atts);

		// Important: Override the uniqueId sent in the topLevel Feedback with the one sent through the settings.
		// This way it works also with the cache, since we need the same uniqueId as the one used in the cached file
		// If not using the cache, then the 2 IDs are the same. If fetching block data no settings comes back, it will
		// then naturally only use the topLevel Feedback ID
		// $json_settings[POP_UNIQUEID] = POP_CONSTANT_UNIQUE_ID;
		
		return $json_settings;
	}
	
	private function combine_ids_datafields(&$ids_data_fields, $dataloader_name, $ids, $data_fields) {

		if (!$ids_data_fields[$dataloader_name]) {
		
			$ids_data_fields[$dataloader_name] = array();
		}
		foreach ($ids as $id) {

			if (is_null($ids_data_fields[$dataloader_name][$id])) {
						
				// Save data-fields for that one id
				$ids_data_fields[$dataloader_name][$id] = $data_fields;
			}
			else {
		
				// If it already has data-fields from a visited dataloader, combine all data-fields
				$ids_data_fields[$dataloader_name][$id] = array_merge(
					$ids_data_fields[$dataloader_name][$id],
					$data_fields
				);
			}
		}
	}

	private function add_dataset_to_database(&$database, $database_key, $dataset) {

		// Save in the database under the corresponding database-key (this way, different dataloaders, like 'list-users' and 'author', 
		// can both save their results under database key 'users'			
		if (!$database[$database_key]) {
			$database[$database_key] = $dataset;
		}
		else {
			// array_merge_recursive doesn't work as expected (it merges 2 hashmap arrays into an array, so then I manually do a foreach instead)
			foreach ($dataset as $key => $value) {

				if (!$database[$database_key][$key]) {
					$database[$database_key][$key] = array();
				}

				$database[$database_key][$key] = array_merge(
					$database[$database_key][$key],
					$value
				);
			}
		}
	}

	private function get_data($toplevel_template_id, $toplevel_processor, $toplevel_atts, $formatter, $request = array()) {
			
		global $gd_template_processor_manager, $gd_dataload_manager, $gd_dataload_iohandle_manager, $gd_dataload_actionexecution_manager, $gd_dataquery_manager, $gd_template_cachemanager;

		$vars = GD_TemplateManager_Utils::get_vars();

		// Load under pagesection => block_settings_id
		$dataset_ids = array();
		$data = array();
		$block_feedback = array();
		$target_params = array();
		$pagesection_feedback = array();
		
		// Load under global key (shared by all pagesections / blocks)
		$ids_data_fields = array();		
		$subcomponent_data_fields = array();
		$crawlable_data = array();

		// Important: cannot use it if doing POST, because the request may have to be handled by a different block than the one whose data was cached
		// Eg: doing GET on /add-post/ will show the form BLOCK_ADDPOST_CREATE, but doing POST on /add-post/ will bring the action ACTION_ADDPOST_CREATE
		// First check if there's a cache stored
		if (!doing_post() && PoP_ServerUtils::use_cache()) {
			
			$static_datasettings = $gd_template_cachemanager->get_cache($toplevel_template_id, POP_CACHETYPE_DATASETTINGS, true);

			// If there is no cached one, generate the atts and cache it
			if (!$static_datasettings) {
				$static_datasettings = $toplevel_processor->get_data_settings($toplevel_template_id, $toplevel_atts);
				$gd_template_cachemanager->store_cache($toplevel_template_id, POP_CACHETYPE_DATASETTINGS, $static_datasettings, true);
			}
		}
		else {
			$static_datasettings = $toplevel_processor->get_data_settings($toplevel_template_id, $toplevel_atts);
		}

		$runtime_datasettings = $toplevel_processor->get_runtime_datasettings($toplevel_template_id, $toplevel_atts);
		$data_settings = array_merge_recursive(
			$static_datasettings,
			$runtime_datasettings
		);
		$toplevel_settings_id = $toplevel_processor->get_settings_id($toplevel_template_id);

		// Needed for topLevel feedback
		$execution_bag = array();
		$executions = array();

		$blocks_template_id = array();
		$blocks_atts = array();


		// TopLevel attributes
		$toplevel_data_settings = $data_settings[$toplevel_template_id];
		$toplevel_iohandler_name = $toplevel_data_settings['iohandler'];
		$toplevel_iohandler_atts = $toplevel_data_settings['iohandler-atts'] ? $toplevel_data_settings['iohandler-atts'] : array();
		$toplevel_iohandler = $gd_dataload_iohandle_manager->get($toplevel_iohandler_name);
		
		// Produce TopLevel checkpoint: can we continue to get the blocks data or a validation failed?
		// $checkpoint = $tl_feedback['result'];
		$checkpoint = $toplevel_iohandler->checkpoint($request, $toplevel_iohandler_atts, $toplevel_atts);
		$checkpoint_failed = is_wp_error($checkpoint);

		// foreach ($toplevel_processor->get_modules($toplevel_template_id, $toplevel_atts) as $pagesection_template_id) {
		foreach ($toplevel_processor->get_modules($toplevel_template_id) as $pagesection_template_id) {

			$pagesection_processor = $gd_template_processor_manager->get_processor($pagesection_template_id);
			$pagesection_atts = $toplevel_atts[$pagesection_template_id];		
			
			$pagesection_data_settings = $data_settings[$pagesection_template_id];
			$pagesection_settings_id = $pagesection_processor->get_settings_id($pagesection_template_id);

			// Initialize responses for the pagesection
			$dataset_ids[$pagesection_settings_id] = array();
			$block_feedback[$pagesection_settings_id] = array();
			$target_params[$pagesection_settings_id] = array();
			

			// Obtain all block_template_id and block_atts back
			// To get the $block_atts, we need to iterate through all keys of $atts and find the one with 'block-settings-id' with our known value
			// This is because all values in $atts are under $template_id as key, and we don't know the $template_id here, so find it again
			$blocks_template_id[$pagesection_settings_id] = array();
			$blocks_atts[$pagesection_settings_id] = array();
			foreach ($pagesection_data_settings as $module_settings_id => $module_data_settings) {
				
				// Do not process the pageSection, which is also contained under the iterated data_settings
				if ($module_settings_id == $pagesection_settings_id) continue;

				// Only now we're sure it's a block
				$block_settings_id = $module_settings_id;
				$block_data_settings = $module_data_settings;
				foreach ($pagesection_atts as $atts_block_id => $temp_block_atts) {

					if ($temp_block_atts['block-settings-id'] == $block_settings_id) {
						$blocks_atts[$pagesection_settings_id][$block_settings_id] = $temp_block_atts;
						$blocks_template_id[$pagesection_settings_id][$block_settings_id] = $temp_block_atts['block-template-id'];
						break;
					}
				}
			}

			// First do all the executions, if any
			$executions[$pagesection_settings_id] = array();
			$execution_bag[$pagesection_template_id] = array();
			foreach ($pagesection_data_settings as $module_settings_id => $module_data_settings) {
				if ($module_settings_id == $pagesection_settings_id) continue;
				$block_settings_id = $module_settings_id;
				$block_data_settings = $module_data_settings;

				if (($checkpoint_failed && $block_data_settings[GD_DATALOAD_VALIDATECHECKPOINTS]) || $block_data_settings[GD_DATALOAD_CONTENTLOADED] === false) {

					$dataset_ids[$pagesection_settings_id][$block_settings_id] = array();
					continue;
				}
				// Allow to plug-in functionality here (eg: form submission)
				// Execute at the very beginning, so the result of the execution can also be fetched later below
				// (Eg: creation of a new location => retrieving its data / Adding a new comment)
				// Pass data_settings so these can also be modified (eg: set id of newly created Location)
				$executed = null;
				$actionexecuter_name = $block_data_settings['actionexecuter'];
				if ($actionexecuter_name) {
					
					// Execution bag: Place under block template, so it can be easily accessed by all other blocks
					$block_atts = $blocks_atts[$pagesection_settings_id][$block_settings_id];
					$block_template_id = $blocks_template_id[$pagesection_settings_id][$block_settings_id];
					$block_execution_bag = &$execution_bag[$pagesection_template_id][$block_template_id];
					
					$executed = $gd_dataload_actionexecution_manager->execute($actionexecuter_name, $block_data_settings, $block_atts, $block_execution_bag);

					// After modifying the data-settings, insert it again
					$pagesection_data_settings[$block_settings_id] = $block_data_settings;
				}
				$executions[$pagesection_settings_id][$block_settings_id] = $executed;
			}

			foreach ($pagesection_data_settings as $module_settings_id => $module_data_settings) {
				
				if ($module_settings_id == $pagesection_settings_id) continue;
				$block_settings_id = $module_settings_id;
				$block_data_settings = $module_data_settings;

				$block_atts = $blocks_atts[$pagesection_settings_id][$block_settings_id];
				$block_template_id = $blocks_template_id[$pagesection_settings_id][$block_settings_id];

				// Allow execution results to affect the data-settings of other blocks
				// (eg: after adding a comment through AddCommentBlock, set the 'include' to the new comment ID in block CommentListBlock)
				$block_processor = $gd_template_processor_manager->get_processor($block_template_id);
				$block_processor->integrate_execution_bag($block_template_id, $block_atts, $block_data_settings, $execution_bag);

				$iohandler_name = $block_data_settings['iohandler'];
				$iohandler_atts = $block_data_settings['iohandler-atts'] ? $block_data_settings['iohandler-atts'] : array();
				
				$iohandler = $gd_dataload_iohandle_manager->get($iohandler_name);

				$dataloader_name = $block_data_settings['dataloader'];
				$dataload_atts = $block_data_settings['dataload-atts'] ? $block_data_settings['dataload-atts'] : array();
				$data_fields = $block_data_settings['data-fields'];

				// Integrate $_REQUEST into $dataloader_atts
				$dataload_atts = array_merge(
					$dataload_atts,
					$request
				);
				
				// Also include the filter
				// if ($filter_object = $block_atts['filter-object']) {
					
				// 	$dataload_atts[GD_DEFINITIONS_FILTEROBJECT] = $filter_object;
				// }	
				if ($filter = $block_atts['filter']) {
					
					$filter_processor = $gd_template_processor_manager->get_processor($filter);
					$dataload_atts[GD_DEFINITIONS_FILTEROBJECT] = $filter_processor->get_filter_object($filter);
				}	

				// The dataload-extend is independent of the dataloader of the block.
				// Even if it is STATIC, the extend ids must be loaded. That's why we load the extend now,
				// Before checking below if the checkpoint failed or if the block content must not be loaded.
				// Eg: Locations Map for the Create Individual Profile: it allows to pre-select locations,  
				// these ones must be fetched even if the block has a static dataloader
				// If it has extend, add those ids under its dataloader_name
				$dataload_extend_settings = $block_data_settings['dataload-extend'] ? $block_data_settings['dataload-extend'] : array();
				foreach ($dataload_extend_settings as $extend_settings_id => $extend_data_settings) {
					$extend_dataloader_name = $extend_data_settings['dataloader'];// It could be that when doing array_merge_recursive in function propagate_data_settings_components in pop-processors,
					
					// the dataloaders down below few levels get merged into an array. In that case, just get the fist element (all elements should be the same)
					if (is_array($extend_dataloader_name)) {
						$extend_dataloader_name = $extend_dataloader_name[0];
					}
				
					// Get the info for the subcomponent dataloader
					$extend_data_fields = $extend_data_settings['data-fields'] ? $extend_data_settings['data-fields'] : array();
					$extend_ids = $extend_data_settings['ids'];
							
					$this->combine_ids_datafields($ids_data_fields, $extend_dataloader_name, $extend_ids, $extend_data_fields);
					
					if (!$data[$extend_dataloader_name][$pagesection_settings_id][$block_settings_id]['ids']) {
					
						$data[$extend_dataloader_name][$pagesection_settings_id][$block_settings_id]['ids'] = array();
					}						
				}

				$executed = $executions[$pagesection_settings_id][$block_settings_id];

				// If it's lazy or Pop, then do nothing, return just the feedback unitialized for content
				$dataload_atts[GD_DATALOAD_LOAD] = $iohandler_atts[GD_DATALOAD_LOAD] = $block_data_settings[GD_DATALOAD_LOAD];
				$dataload_atts[GD_DATALOAD_CONTENTLOADED] = $iohandler_atts[GD_DATALOAD_CONTENTLOADED] = $block_data_settings[GD_DATALOAD_CONTENTLOADED];
				$block_checkpointvalidation_failed = $checkpoint_failed && $block_data_settings[GD_DATALOAD_VALIDATECHECKPOINTS];
				// Tell the block that its checkpoint validation failed
				if ($block_checkpointvalidation_failed) {
					$iohandler_atts['checkpointvalidation-failed'] = true;
				}
				if ($block_checkpointvalidation_failed || $block_data_settings[GD_DATALOAD_LOAD] === false || $block_data_settings[GD_DATALOAD_CONTENTLOADED] === false) {

					$feedback = $iohandler->get_feedback($checkpoint, array(), $dataload_atts, $iohandler_atts, $executed, $block_atts);
					$block_feedback[$pagesection_settings_id][$block_settings_id] = $feedback;
					$params = $iohandler->get_params($checkpoint, array(), $dataload_atts, $iohandler_atts, $executed, $block_atts);
					$target_params[$pagesection_settings_id][$block_settings_id] = $params;
					// This is needed for several reasons:
					// 1. so that memory.dataset[pssId] is not considered an array but an object by jQuery
					// 2. so that when replicating the initial memory dataset, it will override the value with the empty one. Otherwise it does nothing.
					$dataset_ids[$pagesection_settings_id][$block_settings_id] = array();
					continue;
				}

				// Add all subcomponents data
				$this->add_data($data, $pagesection_data_settings, $ids_data_fields, $block_data_settings, $iohandler, $dataload_atts, $iohandler_atts, $dataloader_name, $data_fields, $pagesection_settings_id, $block_settings_id);

				// Save the ids for the block (only for the first level dataloader, no need for subcomponents)
				$dataloader = $gd_dataload_manager->get($dataloader_name);
				$ids = $data[$dataloader_name][$pagesection_settings_id][$block_settings_id]['ids'];
				$dataset_ids[$pagesection_settings_id][$block_settings_id] = $dataloader->get_data_response($ids, $dataload_atts);

				// Save the feedback / Add crawlable data
				$feedback = $iohandler->get_feedback($checkpoint, $ids, $dataload_atts, $iohandler_atts, $executed, $block_atts);
				$block_feedback[$pagesection_settings_id][$block_settings_id] = $feedback;
				$params = $iohandler->get_params($checkpoint, $ids, $dataload_atts, $iohandler_atts, $executed, $block_atts);
				$target_params[$pagesection_settings_id][$block_settings_id] = $params;
				$crawlable_data = array_merge(
					$crawlable_data,
					$iohandler->get_crawlable_data($feedback, $params, $block_data_settings)
				);
			}

			// If the pageSection was provided as an attribute, then we're fetching data for the block, we don't need to calculate the feedback for the pageSection, it's already known
			if (!$vars['pagesection']) {

				// Execute the pageSection feedback
				$pagesection_iohandler_name = $pagesection_data_settings[$pagesection_settings_id]['iohandler'];
				$pagesection_iohandler_atts = $pagesection_data_settings[$pagesection_settings_id]['iohandler-atts'] ? $pagesection_data_settings[$pagesection_settings_id]['iohandler-atts'] : array();
				$pagesection_iohandler = $gd_dataload_iohandle_manager->get($pagesection_iohandler_name);

				$feedback = $pagesection_iohandler->get_feedback($checkpoint, array(), $request, $pagesection_iohandler_atts, null, $pagesection_atts);
				$pagesection_feedback[$pagesection_settings_id] = $feedback;
			}
		}

		// Finally, after executing all block feedbacks, produce the toplevel feedback (do it at the end, so it's also valid when the user logs in (actionexecuter "Log in" being a block))
		$toplevel_feedback = $toplevel_iohandler->get_feedback($checkpoint, array(), $request, $toplevel_iohandler_atts, null, $toplevel_atts);
		
		// Save all database elements here, under dataloader
		$database = $userdatabase = $nocache_fields = array();

		// Save all the BACKGROUND_LOAD urls to send back to the browser, to load immediately again (needed to fetch non-cacheable data-fields)
		$backgroundload_urls = array();

		// Iterate all dataloaders, in order given by their execution priority, 
		// and for each get the data through all $ids from all blocks, all in 1 query
		$dataloaders = array_keys($data);	
		
		$dataloaders_ordered_by_priority = $gd_dataload_manager->order($dataloaders);

		foreach ($dataloaders_ordered_by_priority as $dataloader_name) {		

			$dataloader_ids_data_fields = &$ids_data_fields[$dataloader_name];

			// If no ids to execute, then skip
			if (empty($dataloader_ids_data_fields)) {
				continue;
			}

			$dataloader = $gd_dataload_manager->get($dataloader_name);
			$database_key = $dataloader->get_database_key();

			// Execute the dataloader for all combined ids
			$resultset = $dataloader->get_data($dataloader_ids_data_fields);
			$dataset = $dataloader->get_dataset($formatter, $resultset, $dataloader_ids_data_fields);

			// Add the crawlable data
			$crawlable_data = array_merge(
				$crawlable_data,
				$dataset['crawlable-data']
			);

			// Save in the database under the corresponding database-key (this way, different dataloaders, like 'list-users' and 'author', 
			// can both save their results under database key 'users'			
			$this->add_dataset_to_database($database, $database_key, $dataset['dataset']);

			// Also save those results which depend on the logged-in user. These are treated separately because:
			// 1: They contain personal information, so it must be erased from the front-end as soon as the user logs out
			// 2: These results make the page state-full, so this page is not cacheable
			// By splitting the results into state-full and state-less, we can split all functionality into cacheable and non-cacheable,
			// thus caching most of the website even for logged-in users
			$this->add_dataset_to_database($userdatabase, $database_key, $dataset['user-dataset']);

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

				// Compare the fields in the result dataset, with the dataquery's specified list of fields that must always be retrieved from the server
				// (eg: comment-count, since adding a comment doesn't delete the cache)
				foreach ($dataset['dataset'] as $dataitem_id => $dataitem_entries) {

					// Get the fields requested to that dataitem
					$dataitem_fields = array_keys($dataitem_entries);

					// Intersect these with the fields that must be loaded from server
					if ($intersect = array_intersect($dataitem_fields, $forceserverload_fields)) {

						$forceserverload['ids'][] = $dataitem_id;
						$forceserverload['fields'] = array_merge(
							$forceserverload['fields'],
							$intersect
						);
					}

					// Intersect these with the lazyload fields
					if ($intersect = array_intersect($dataitem_fields, $lazyload_fields)) {

						$lazyload['ids'][] = $dataitem_id;
						foreach ($intersect as $field) {
							
							$lazyload['layouts'][] = $lazylayouts[$field]['default'];
						}
					}
				}
				if ($forceserverload['ids']) {

					$forceserverload['fields'] = array_unique($forceserverload['fields']);

					$url = get_permalink($dataquery->get_noncacheable_page());
					$url = add_query_arg($objectid_fieldname, $forceserverload['ids'], $url);
					$url = add_query_arg('fields', $forceserverload['fields'], $url);
					$url = add_query_arg(GD_URLPARAM_FORMAT, GD_TEMPLATEFORMAT_UPDATEDATA, $url);
					$backgroundload_urls[urldecode($url)] = array(GD_URLPARAM_TARGET_MAIN);

					// Keep the nocache fields to remove those from the code when generating the ETag
					$nocache_fields = array_merge(
						$nocache_fields,
						$forceserverload['fields']
					);
				}
				if ($lazyload['ids']) {

					$lazyload['layouts'] = array_unique($lazyload['layouts']);

					$url = get_permalink($dataquery->get_cacheable_page());
					$url = add_query_arg($objectid_fieldname, $lazyload['ids'], $url);
					$url = add_query_arg('layouts', $lazyload['layouts'], $url);
					$url = add_query_arg(GD_URLPARAM_FORMAT, GD_TEMPLATEFORMAT_REQUESTLAYOUTS, $url);
					$backgroundload_urls[urldecode($url)] = array(GD_URLPARAM_TARGET_MAIN);
				}
			}

			foreach ($data[$dataloader_name] as $pagesection_settings_id => $hierarchy_data) {

				foreach ($hierarchy_data as $block_settings_id => $dataloader_data) {
					// Subcomponents
					// Check if it has subcomponents, and then bring this data				
					$dataloader_ids = $dataloader_data['ids'];

					$subcomponents_data_settings = $dataloader_data['subcomponents'];

					if ($subcomponents_data_settings) {

						foreach ($subcomponents_data_settings as $subcomponent_id_field => $subcomponent_data_settings) {
							
							// The array_merge_recursive when there are at least 2 levels will make the data_fields to be duplicated, so remove duplicates now
							$subcomponent_data_fields = array_unique($subcomponent_data_settings['data-fields']);
							$subcomponent_dataloader_name = $subcomponent_data_settings['dataloader'];

							// It could be that when doing array_merge_recursive in function propagate_data_settings_components in pop-processors,
							// the dataloaders down below few levels get merged into an array. In that case, just get the fist element (all elements should be the same)
							if (is_array($subcomponent_dataloader_name)) {
								$subcomponent_dataloader_name = $subcomponent_dataloader_name[0];
							}

							if ($subcomponent_data_fields && $subcomponent_dataloader_name) {

								// This piece of code comes here, so that we can have subcomponents of subcomponents
								// (eg: Single Story => References => Author) and still write the db-key
								// $subcomponent_dataloader = $gd_dataload_manager->get($subcomponent_dataloader_name);
								
								foreach ($dataloader_ids as $id) {
							
									if ($database_field_ids = $database[$database_key][$id][$subcomponent_id_field]) {
										if (!is_array($database_field_ids)) {
											$database_field_ids = array($database_field_ids);
										}
									}
									if ($userdatabase_field_ids = $userdatabase[$database_key][$id][$subcomponent_id_field]) {
										if (!is_array($userdatabase_field_ids)) {
											$userdatabase_field_ids = array($userdatabase_field_ids);
										}
									}
									$field_ids = array_merge(
										$database_field_ids ? $database_field_ids : array(),
										$userdatabase_field_ids ? $userdatabase_field_ids : array()
									);
									if ($field_ids) {

										// Field ID can be a single value, or an array (eg: locations)
										if (!is_array($field_ids)) {
									
											$field_ids = array($field_ids);
										}
										foreach ($field_ids as $field_id) {

											$data[$subcomponent_dataloader_name][$pagesection_settings_id][$block_settings_id]['ids'][] = $field_id;
											$this->combine_ids_datafields($ids_data_fields, $subcomponent_dataloader_name, array($field_id), $subcomponent_data_fields);
										}
									}
								}

								if ($data[$subcomponent_dataloader_name][$pagesection_settings_id][$block_settings_id] && $data[$subcomponent_dataloader_name][$pagesection_settings_id][$block_settings_id]) {
									$data[$subcomponent_dataloader_name][$pagesection_settings_id][$block_settings_id]['ids'] = array_unique($data[$subcomponent_dataloader_name][$pagesection_settings_id][$block_settings_id]['ids']);				
								}
							}	
						}	
					}				
				}			
			}	
		}

		// Exceptional case in the topLevel Feedback: add now what extra URLs must be loaded
		// This is not set in the IOHandler because we don't have this data by the time we execute get_feedback()
		$toplevel_feedback[GD_URLPARAM_BACKGROUNDLOADURLS] = $backgroundload_urls;

		$ret = array(
			'dataset' => $dataset_ids,
			'database' => $database,
			'userdatabase' => $userdatabase,
			'crawlable-data' => implode("\n", $crawlable_data),
			'params' => $target_params,
			'feedback' => array(
				'block' => $block_feedback,
				'toplevel' => $toplevel_feedback,
			),
			'nocache-fields' => $nocache_fields,
		);

		if (!$vars['pagesection']) {
			$ret['feedback']['pagesection'] = $pagesection_feedback;
		}

		return $ret;
	}

	
	/**
	 * Settings: includes configuration, data and database
	 */
	private function add_data(&$data, &$pagesection_data_settings, &$ids_data_fields, $data_settings, $iohandler, $dataload_atts, $iohandler_atts, $dataloader_name, $data_fields, $pagesection_settings_id, $block_settings_id) {

		global $gd_dataload_manager;
			
		// From the $atts generate the input to the dataloader, coming from its iohandler
		$iohandler_vars = $iohandler->get_vars($dataload_atts, $iohandler_atts);

		// Execute and get the ids
		$dataloader = $gd_dataload_manager->get($dataloader_name);
		$ids = $dataloader->get_data_ids($iohandler_vars, true);

		$this->add_subcomponent_data($data, $pagesection_data_settings, $ids_data_fields, $ids, $data_settings, $dataloader_name, $data_fields, $pagesection_settings_id, $block_settings_id);
	}

	private function add_subcomponent_data(&$data, &$pagesection_data_settings, &$ids_data_fields, $ids, $data_settings, $dataloader_name, $data_fields, $pagesection_settings_id, $block_settings_id) {

		global $gd_dataload_manager;

		// Store the ids under $data under key dataload_name => block_id
		$this->combine_ids_datafields($ids_data_fields, $dataloader_name, $ids, $data_fields);

		$subcomponents_data_settings = $data_settings['subcomponents'];
		$data[$dataloader_name][$pagesection_settings_id][$block_settings_id] = array(
			'ids' => $ids,
			'subcomponents' => $subcomponents_data_settings
		);

		// If it has subcomponents, bring its data to, after executing get_data on the primary dataloader, execute get_data also on the subcomponent dataloader
		if ($subcomponents_data_settings) {
				
			// Add the subcomponent data to the data-settings response
			$pagesection_data_settings[$block_settings_id]['subcomponents'] = array();

			foreach ($subcomponents_data_settings as $subcomponent_id_field => $subcomponent_data_settings) {
				if ($subcomponent_dataloader_name = $subcomponent_data_settings['dataloader']) {

					// It could be that when doing array_merge_recursive in function propagate_data_settings_components in pop-processors,
					// the dataloaders down below few levels get merged into an array. In that case, just get the fist element (all elements should be the same)
					if (is_array($subcomponent_dataloader_name)) {
						$subcomponent_dataloader_name = $subcomponent_dataloader_name[0];
					}

					// Get the info for the subcomponent dataloader
					$subcomponent_data_fields = $subcomponent_data_settings['data-fields'];
					$subcomponent_subcomponents = $subcomponent_data_settings['subcomponents'];
					
					// Add to data (but do not bring the ids yet, this comes as a result of get_data on the parent dataloader)
					$data[$subcomponent_dataloader_name][$pagesection_settings_id][$block_settings_id] = array(
						'ids' => array(),
						'data-fields' => $subcomponent_data_fields,
						'subcomponents' => $subcomponent_subcomponents
					);

					// Recursion: Keep including levels below
					if ($subcomponent_subcomponents) {

						$this->add_subcomponent_data($data, $pagesection_data_settings, $ids_data_fields, array(), $subcomponent_data_settings, $subcomponent_dataloader_name, $subcomponent_data_fields, $pagesection_settings_id, $block_settings_id);
					}
				}
			}
		}
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_Engine();
