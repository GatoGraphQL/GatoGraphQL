<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_MultipleUserLayoutsBase extends GD_Template_Processor_MultipleLayoutsBase {

	function get_data_fields($template_id, $atts) {
	
		$ret = parent::get_data_fields($template_id, $atts);

		// If multiple-layouts, then we need 'role' data-fields
		$ret[] = 'role';
		
		return $ret;
	}
}