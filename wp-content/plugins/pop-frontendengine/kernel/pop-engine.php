<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPFrontend_Engine extends PoP_Engine {

	var $crawlableitems, $runtimecrawlableitems, $scripts, $enqueue;

	function __construct() {

		parent::__construct();

		// Print needed scripts
		$this->scripts = $this->enqueue = array();
		add_action('wp_print_footer_scripts', array($this, 'print_scripts'));

		// Priority 60: after priority 50 in wp-content/plugins/pop-frontendengine/kernel/resourceloader/initialization.php
		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'), 60);
	}

	function print_json() {
		
		return !PoP_Frontend_ServerUtils::disable_js();
	}

	protected function add_crawlable_data() {

		// Get the crawlable data only if not doing the server-side rendering
		// Otherwise, we already have the final HTML, so the crawlable data serves no purpose
		return !PoP_Frontend_ServerUtils::use_serverside_rendering();
	}

	// Allow PoPFrontend_Engine to override this function
	protected function get_encoded_json_object($json) {

		$json = parent::get_encoded_json_object($json);

		// Optimizations to be made when first loading the website
		if (GD_TemplateManager_Utils::loading_frame()) {

			// If we are using serverside-rendering, and set the config to remove the database code,
			// then do not send the data to the front-end (most likely there is no need, since the HTML has already been produced)
			if (PoP_Frontend_ServerUtils::remove_database_from_output()) {

				// We do not need the DB
				if (isset($json['database'])) {

					unset($json['database']);
				}
				if (isset($json['userdatabase'])) {

					unset($json['userdatabase']);
				}
			}

			// Do not send the sitemapping or the settings to be output as code. 
			// Instead, save the settings contents into a javascript file, and enqueue it
			if (PoP_Frontend_ServerUtils::generate_resources_on_runtime()) {

				// Sitemapping
				$this->optimize_encoded_json($json, 'sitemapping', POP_RUNTIMECONTENTTYPE_SITEMAPPING, false);
				
				// Settings
				$this->optimize_encoded_json($json, 'settings', POP_RUNTIMECONTENTTYPE_SETTINGS, true);
			}
		}
			
		return $json;
	}

	function optimize_encoded_json(&$json, $property, $type, $do_replacements) {

		// Sitemapping
		if ($value = $json[$property]) {
			
			// Check if this file has already been generated. If not, then save it
			global $gd_template_runtimecontentmanager;
			$template_id = $this->get_toplevel_template_id();
			if (!$gd_template_runtimecontentmanager->cache_exists($template_id, $type)) {

				// Generate and save the JS code
				$value_js = sprintf(
					'popTopLevelJSON.strings[\'%1$s\'] = %2$s;',
					$property,
					// Encoded twice: the first one for the array, the 2nd one to convert it to string
					json_encode(json_encode($value))
				);
				$gd_template_runtimecontentmanager->store_cache_by_template_id($template_id, $type, $value_js);

				// In addition, this file must be uploaded to AWS S3 bucket, so that this scheme of generating the file on runtime
				// can also work when hosting the website at multiple servers 
				do_action(
					'PoP_Engine:optimize_encoded_json:file_stored',
					$template_id,
					$property, 
					$type
					// $value_js
				);
			}

			// Enqueue the .js file
			if ($file_url = $gd_template_runtimecontentmanager->get_file_url($template_id, $type)) {

				// Comment Leo 07/11/2017: if enqueuing bundle/bundlegroup scripts, then we don't have the pop-manager.js file enqueued, 
				// Then, move the functionality below to `enqueue_scripts`, as to wait until the corresponding scripts have been enqueued
				$this->enqueue[] = array(
					'property' => $property,
					'file-url' => $file_url,
				);
				// $script = PoP_ResourceLoaderProcessorUtils::get_noconflict_resource_name(POP_RESOURCELOADER_POPMANAGER);
				// wp_register_script('pop-'.$property, $file_url, array($script), pop_version(), true);
				// wp_enqueue_script('pop-'.$property);
			}

			// We must also do the replacements on the JS code (get_loadfile_cache_replacements)
			if ($do_replacements) {

				if ($replacements = $gd_template_runtimecontentmanager->get_loadfile_cache_replacements()) {
					
					$from = $replacements['from'];
					$to = $replacements['to'];
					if ($from && $to) {
						
						$replaces = '';
						for ($i=0; $i<count($from); $i++) {
							
							$replaces .= sprintf(
								'.replace(/%s/g, \'%s\')',
								$from[$i],
								$to[$i]
							);
						}
						$this->scripts[] = sprintf(
							'if (popTopLevelJSON.strings[\'%1$s\']) {'.
								'popTopLevelJSON.strings[\'%1$s\'] = popTopLevelJSON.strings[\'%1$s\']%2$s;'.
							'}',
							$property,
							$replaces
						);
					}
				}
			}

			// Remove the entry from the html output
			unset($json[$property]);
		}
	}

	function print_scripts() {

		if ($this->scripts) {

			printf(
				'<script type="text/javascript">%s</script>', 
				implode(PHP_EOL, $this->scripts)
			);
		}
	}

	function enqueue_scripts() {

		$version = pop_version();

		$script = 'pop';
		if (PoP_Frontend_ServerUtils::use_code_splitting()) {

			global $pop_jsresourceloaderprocessor_manager;
			$script = $pop_jsresourceloaderprocessor_manager->first_script;
		}
		elseif (PoP_Frontend_ServerUtils::use_bundled_resources()) {

			// The bundle application file must be registered under "pop-app"
			$script = 'pop-app';
		}
		foreach ($this->enqueue as $item) {

			// When using the 'app-bundle' then there is no $first_script, just use 'pop'
			$dependencies = array($script);
			wp_register_script('pop-'.$item['property'], $item['file-url'], $dependencies, $version, true);
			wp_enqueue_script('pop-'.$item['property']);
		}
	}

	function get_output_items() {

		$output_items = parent::get_output_items();

		// What items to fetch
		$vars = GD_TemplateManager_Utils::get_vars();
		if (!$vars['fetching-json']) {

			// Include the 'crawlable' when loading the frame
			if ($this->add_crawlable_data()) {
				$output_items[] = 'crawlable';
			}
		}

		return $output_items;
	}

	function output_end() {
	
		// Seach Engine Crawlable data + Fallback for non-JS browsers
		if ($this->add_crawlable_data()) {
			
			$div_id = 'fallback';
			printf(
				'<div id="%1$s" class="searchengine-crawlable"><script type="text/javascript">document.getElementById("%1$s").style.display = "none";</script><div class="crawlable-items">%2$s</div><div class="runtime-crawlable-items">%3$s</div><div class="crawlable-data">%4$s</div></div>',
				$div_id,
				$this->crawlableitems,
				$this->runtimecrawlableitems,
				$this->resultsObject['crawlable-data']
			);
		}
	}

	protected function get_results_object($template_id, $output_items) {

		global $gd_template_processor_manager, $gd_template_cachemanager;

		// Get the JSON from the parent
		$resultsObject = parent::get_results_object($template_id, $output_items);

		// Add the crawlable items
		if (in_array('crawlable', $output_items)) {

			// Important: cannot use it if doing POST, because the request may have to be handled by a different block than the one whose data was cached
			// Eg: doing GET on /add-post/ will show the form BLOCK_ADDPOST_CREATE, but doing POST on /add-post/ will bring the action ACTION_ADDPOST_CREATE
			// First check if there's a cache stored
			if (!doing_post() && PoP_ServerUtils::use_cache()) {
				
				// Crawlable items
				$this->crawlableitems = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_CRAWLABLEITEMS, false, POP_CACHE_EXT_HTML);

				// If there is no cached one, generate it and cache it
				if (!$this->crawlableitems) {

					$this->crawlableitems = implode("\n", $this->get_crawlable_items($template_id, $this->atts));
					$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_CRAWLABLEITEMS, $this->crawlableitems, false, POP_CACHE_EXT_HTML);
				}
			}
			else {
				
				$this->crawlableitems = implode("\n", $this->get_crawlable_items($template_id, $this->atts));
			}

			$this->runtimecrawlableitems = implode("\n", $this->get_runtime_crawlable_items($template_id, $this->atts));
		}

		return $resultsObject;
	}

	protected function get_json_settings($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$json_settings = parent::get_json_settings($template_id, $atts);

		// Otherwise, get the dynamic configuration
		$processor = $gd_template_processor_manager->get_processor($template_id);

		// Templates: What templates must be executed after call to loadMore is back with data:
		// CB: list of templates to merge
		// $json_settings['template-sources'] = $processor->get_templates_sources($template_id, $atts);
		$json_settings['templates-cbs'] = $processor->get_templates_cbs($template_id, $atts);
		$json_settings['templates-paths'] = $processor->get_templates_paths($template_id, $atts);
		// JS Settings: allow for Javascript to be executed (eg: Twitter typeahead initialization)
		$json_settings['js-settings'] = $processor->get_js_settings($template_id, $atts);
		$json_settings['jsmethods'] = array(
			'pagesection' => $processor->get_pagesection_jsmethods($template_id, $atts),
			'block' => $processor->get_block_jsmethods($template_id, $atts)
		);
		
		return $json_settings;
	}

	protected function get_json_sitemapping($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$json_sitemapping = parent::get_json_sitemapping($template_id, $atts);

		// Otherwise, get the dynamic configuration
		$processor = $gd_template_processor_manager->get_processor($template_id);

		// Needed for the ResourceLoader, to load also all other needed sources by each template
		if (PoP_Frontend_ServerUtils::use_code_splitting()) {

			$json_sitemapping['template-extra-sources'] = $processor->get_templates_extra_sources($template_id, $atts);
			$json_sitemapping['template-resources'] = $processor->get_templates_resources($template_id, $atts);

			// The dynamic template sources will only be needed to optimize handlebars templates loading, when doing serverside-rendering and doing code-splitting
			if (PoP_Frontend_ServerUtils::use_serverside_rendering()) {
				
				global $gd_template_memorymanager;

				// Check if results are already on the cache
				$dynamic_template_sources = $gd_template_memorymanager->get_cache_by_template_id($template_id, POP_MEMORYTYPE_DYNAMICTEMPLATERESOURCES, true);
				if (!$dynamic_template_sources) {

					// If not, calculate the values now...
					$dynamic_template_sources = $processor->get_dynamic_templates_sources($template_id, $atts);
					
					// And store them on the cache
					$gd_template_memorymanager->store_cache_by_template_id($template_id, POP_MEMORYTYPE_DYNAMICTEMPLATERESOURCES, $dynamic_template_sources, true);
				}
				$json_sitemapping['dynamic-template-sources'] = $dynamic_template_sources;

				// Also save the value to be used by `is_defer`
				// if (/*!doing_post() && */PoP_ServerUtils::use_cache()) {
				// global $pop_resourceloader_resourcecachemanager;
				// $pop_resourceloader_resourcecachemanager->set_dynamic_template_sources($vars_hash_id, $dynamic_template_sources);
				// }
			}
		}
		
		return $json_sitemapping;
	}

	protected function get_json_runtimesettings($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$json_runtimesettings = parent::get_json_runtimesettings($template_id, $atts);

		// Otherwise, get the dynamic configuration
		$processor = $gd_template_processor_manager->get_processor($template_id);

		$json_runtimesettings['js-settings'] = $processor->get_js_runtimesettings($template_id, $atts);
		
		return $json_runtimesettings;
	}

	protected function get_crawlable_items($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$processor = $gd_template_processor_manager->get_processor($template_id);
		return $processor->get_template_crawlableitems($template_id, $atts);
	}

	protected function get_runtime_crawlable_items($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$processor = $gd_template_processor_manager->get_processor($template_id);
		return $processor->get_template_runtimecrawlableitems($template_id, $atts);
	}	
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPFrontend_Engine();
