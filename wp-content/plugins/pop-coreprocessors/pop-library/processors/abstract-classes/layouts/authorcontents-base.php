<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_AuthorContentLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_AUTHORCONTENT;
	}

	function get_data_fields($template_id, $atts) {
	
		return array('display-name', 'description-formatted', 'short-description-formatted');
	}

	function get_description_maxlength($template_id, $atts) {
	
		return false;
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);	

		if ($maxlength = $this->get_description_maxlength($template_id, $atts)) {
			$ret['description-maxlength'] = $maxlength;
		}

		return $ret;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		if ($maxlength = $this->get_description_maxlength($template_id, $atts)) {
			$this->add_jsmethod($ret, 'showmore');
		}

		return $ret;
	}
}