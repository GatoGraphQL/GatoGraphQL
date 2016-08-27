<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_PostViewComponentHeadersBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_VIEWCOMPONENT_HEADER_POST;
	}

	function get_data_fields($template_id, $atts) {
	
		$thumb = $this->get_thumb_name($template_id, $atts);
		$data_fields = array('id', 'title', $thumb);
		if ($this->header_show_url($template_id, $atts)) {

			$data_fields[] = 'url';
		}

		return $data_fields;
	}

	function header_show_url($template_id, $atts) {

		return false;
	}

	function get_thumb_name($template_id, $atts) {

		return 'thumb-xs';
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);
	
		// Add the URL in the header? Sometimes yes (eg: Addon) sometimes not (eg: modal)
		if ($this->header_show_url($template_id, $atts)) {

			$ret['header-show-url'] = true;
		}

		$ret['thumb'] = array(
			'name' => $this->get_thumb_name($template_id, $atts)
		);
		
		return $ret;
	}
}
