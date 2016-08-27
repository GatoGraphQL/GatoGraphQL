<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_SocialMediaBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_SOCIALMEDIA;
	}

	function use_counter($template_id) {
		return false;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		if ($this->use_counter($template_id)) {
			$this->add_jsmethod($ret, 'socialmedia');
		}
		
		return $ret;
	}
	
	function init_atts($template_id, &$atts) {
	
		$title = $this->get_att($template_id, $atts, 'title');
		foreach ($this->get_modules($template_id) as $module) {
			$this->add_att($module, $atts, 'title', $title);
		}
		
		$this->append_att($template_id, $atts, 'class', 'pop-hidden-print');
		
		return parent::init_atts($template_id, $atts);
	}
}