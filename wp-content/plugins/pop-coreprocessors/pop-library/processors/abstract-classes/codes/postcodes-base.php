<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_PostCodesBase extends GD_Template_Processor_CodesBase {

	function get_code($template_id, $atts) {

		$codepost = get_post($this->get_post_id($template_id));
		return apply_filters('the_content', $codepost->post_content);
	}

	function get_post_id($template_id) {

		return null;
	}
}
