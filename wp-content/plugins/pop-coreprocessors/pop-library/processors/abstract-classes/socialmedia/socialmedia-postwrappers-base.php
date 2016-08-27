<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_SocialMediaPostWrapperBase extends GD_Template_Processor_ConditionWrapperBase {

	function get_socialmedia_template($template_id) {
	
		return null;
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		$ret[] = $this->get_socialmedia_template($template_id);

		return $ret;
	}

	function get_condition_field($template_id) {
	
		return 'published';
	}

	function init_atts($template_id, &$atts) {

		$this->append_att($template_id, $atts, 'class', 'pop-hidden-print');		
		return parent::init_atts($template_id, $atts);
	}
}