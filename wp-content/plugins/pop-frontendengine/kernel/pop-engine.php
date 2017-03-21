<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPFrontend_Engine extends PoP_Engine {

	var $crawlableitems, $runtimecrawlableitems;

	function get_output_items() {

		$output_items = parent::get_output_items();

		// What items to fetch
		$vars = GD_TemplateManager_Utils::get_vars();
		if (!$vars['fetching-json']) {

			// Include the 'crawlable' when loading the frame
			$output_items[] = 'crawlable';
		}

		return $output_items;
	}

	function output_end() {
	
		// Seach Engine Crawlable data + Fallback for non-JS browsers
		$div_id = 'fallback';
		printf(
			'<div id="%1$s" class="searchengine-crawlable"><script type="text/javascript">document.getElementById("%1$s").style.display = "none";</script><div class="crawlable-items">%2$s</div><div class="runtime-crawlable-items">%3$s</div><div class="crawlable-data">%4$s</div></div>',
			$div_id,
			$this->crawlableitems, // $this->json['crawlable-items'],
			$this->runtimecrawlableitems, // $this->json['runtime-crawlable-items'],
			$this->json['crawlable-data']
		);
	}

	protected function get_json($template_id, $output_items) {

		global $gd_template_processor_manager, $gd_template_cachemanager;

		// Get the JSON from the parent
		$json = parent::get_json($template_id, $output_items);

		// Add the crawlable items
		if (in_array('crawlable', $output_items)) {

			// Important: cannot use it if doing POST, because the request may have to be handled by a different block than the one whose data was cached
			// Eg: doing GET on /add-post/ will show the form BLOCK_ADDPOST_CREATE, but doing POST on /add-post/ will bring the action ACTION_ADDPOST_CREATE
			// First check if there's a cache stored
			if (!doing_post() && PoP_ServerUtils::use_cache()) {
				
				// Crawlable items
				$this->crawlableitems = $gd_template_cachemanager->get_cache($template_id, POP_CACHETYPE_CRAWLABLEITEMS, false, POP_CACHE_EXT_HTML);

				// If there is no cached one, generate it and cache it
				if (!$this->crawlableitems) {

					$this->crawlableitems = implode("\n", $this->get_crawlable_items($template_id, $this->atts));
					$gd_template_cachemanager->store_cache($template_id, POP_CACHETYPE_CRAWLABLEITEMS, $this->crawlableitems, false, POP_CACHE_EXT_HTML);
				}
			}
			else {
				
				$this->crawlableitems = implode("\n", $this->get_crawlable_items($template_id, $this->atts));
			}

			$this->runtimecrawlableitems = implode("\n", $this->get_runtime_crawlable_items($template_id, $this->atts));
		}

		return $json;
	}

	protected function get_json_settings($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$json_settings = parent::get_json_settings($template_id, $atts);

		// Otherwise, get the dynamic configuration
		$processor = $gd_template_processor_manager->get_processor($template_id);

		// Templates: What templates must be executed after call to loadMore is back with data:
		// CB: list of templates to merge
		$json_settings['template-sources'] = $processor->get_templates_sources($template_id, $atts);
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
