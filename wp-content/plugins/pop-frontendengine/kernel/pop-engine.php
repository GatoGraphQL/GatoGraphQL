<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPFrontend_Engine extends PoP_Engine {

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
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPFrontend_Engine();
