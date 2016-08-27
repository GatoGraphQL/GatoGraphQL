<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_PostAuthorNameLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_LAYOUTPOST_AUTHORNAME;
	}

	function get_url_field($template_id, $atts) {
	
		return 'url';
	}

	function get_link_target($template_id, $atts) {
	
		return '';
	}

	function get_data_fields($template_id, $atts) {

		$ret = parent::get_data_fields($template_id, $atts);
	
		$ret[] = $this->get_url_field($template_id, $atts);
		$ret[] = 'display-name';

		return $ret;
	}


	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		if ($target = $this->get_link_target($template_id, $atts)) {
			$ret['targets']['link'] = $target;
		}
		$ret['url-field'] = $this->get_url_field($template_id, $atts);

		return $ret;
	}
}