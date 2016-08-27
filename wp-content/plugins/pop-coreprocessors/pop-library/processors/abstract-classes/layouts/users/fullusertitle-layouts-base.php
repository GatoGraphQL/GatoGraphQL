<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_FullUserTitleLayoutsBase extends GD_Template_Processor_FullObjectTitleLayoutsBase {

	function get_title_field($template_id, $atts) {
		
		return 'display-name';
	}
	
	function get_condition_field($template_id, $atts) {
		
		return 'is-profile';
	}
}