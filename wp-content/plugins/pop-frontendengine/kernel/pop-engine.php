<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPFrontend_Engine extends PoP_Engine {

	function output_end() {
	
		// Seach Engine Crawlable data
		printf(
			'<div class="hidden searchengine-crawlable"><div class="crawlable-items">%s</div><div class="runtime-crawlable-items">%s</div><div class="crawlable-data">%s</div></div>',
			$this->json['crawlable-items'],
			$this->json['runtime-crawlable-items'],
			$this->json['crawlable-data']
		);
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
