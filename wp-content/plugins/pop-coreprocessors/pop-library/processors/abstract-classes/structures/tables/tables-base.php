<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// class GD_Template_Processor_TablesBase extends GD_Template_Processor_LayoutGridStructuresBase {
class GD_Template_Processor_TablesBase extends GD_Template_Processor_StructuresBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_TABLE;
	}

	function get_header_titles($template_id) {
	
		return array();
	}

	protected function get_description($template_id, $atts) {
	
		return '';
	}
	
	function get_template_configuration($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = parent::get_template_configuration($template_id, $atts);

		if ($header_titles = $this->get_header_titles($template_id)) {
			$ret['header'][GD_JS_TITLES/*'titles'*/] = $header_titles;

			if ($description = $this->get_description($template_id, $atts)) {
				$ret[GD_JS_DESCRIPTION/*'description'*/] = $description;
			}
		}
		
		return $ret;
	}

	function get_template_crawlableitem($template_id, $atts) {

		$ret = parent::get_template_crawlableitem($template_id, $atts);
		
		$configuration = $this->get_template_configuration($template_id, $atts);
	
		if ($description = $configuration[GD_JS_DESCRIPTION]) {
			$ret[] = $description;
		}
		
		return $ret;
	}
}
