<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_DataQueriesBase extends GD_Template_ProcessorBase {

	// Comment Leo 12/01/2017: make it runtime instead of static, since it exactly is getting its data from the URL
	// Comment Leo 12/01/2017: actually, it can be static, because the 'fields' are saved in the cache filename (file wp-content/plugins/pop-engine/kernel/cache/pop-cacheprocessor.php function get_cache_filename($template_id))
	function get_data_fields($template_id, $atts) {
	// function get_runtime_datafields($template_id, $atts) {

		$vars = GD_TemplateManager_Utils::get_vars();
		// $fields = isset($_REQUEST['fields']) ? $_REQUEST['fields'] : array();
		$fields = isset($vars['fields']) ? $vars['fields'] : array();

		// Only allow from a specific list of fields. Precaution against hackers.
		// $fields = array_intersect($fields, $atts['dataquery-allowedfields']);
		global $gd_dataquery_manager;
		$fields = array_intersect($fields, $gd_dataquery_manager->get_allowedfields());

		return $fields;
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);	
		// Comment Leo 12/01/2017: make it runtime instead of static, since it exactly is getting its data from the URL
		// Comment Leo 12/01/2017: actually, it can be static, because the 'fields' are saved in the cache filename (file wp-content/plugins/pop-engine/kernel/cache/pop-cacheprocessor.php function get_cache_filename($template_id))
		$ret['fields'] = $this->get_data_fields($template_id, $atts);
		// $ret['fields'] = $this->get_runtime_datafields($template_id, $atts);
		return $ret;
	}
}
