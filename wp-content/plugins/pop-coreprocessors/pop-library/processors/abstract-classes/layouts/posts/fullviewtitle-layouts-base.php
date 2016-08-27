<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_FullViewTitleLayoutsBase extends GD_Template_Processor_FullObjectTitleLayoutsBase {

	function get_title_field($template_id, $atts) {
		
		return 'title';
	}
	
	function get_titleattr_field($template_id, $atts) {
		
		return 'alt';
	}

	function get_condition_field($template_id, $atts) {
		
		return 'published';
	}
}