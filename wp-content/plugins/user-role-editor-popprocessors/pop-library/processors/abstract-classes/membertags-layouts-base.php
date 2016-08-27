<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_URE_Template_Processor_MemberTagsLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id) {
	
		return GD_TEMPLATESOURCE_LAYOUTUSER_MEMBERTAGS;
	}

	function get_data_fields($template_id, $atts) {
	
		return array('membertags-strings');
	}	

	function get_description($template_id, $atts) {
	
		return '';
	}	

	function get_label_class($template_id, $atts) {

		return 'label-info';
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		if ($description = $this->get_description($template_id, $atts)) {
			$ret[GD_JS_TITLES/*'titles'*/]['description'] = $description;
		}

		if ($label_class = $this->get_label_class($template_id, $atts)) {
			$ret[GD_JS_CLASSES/*'classes'*/]['label'] = $label_class;
		}
	
		return $ret;
	}	
}