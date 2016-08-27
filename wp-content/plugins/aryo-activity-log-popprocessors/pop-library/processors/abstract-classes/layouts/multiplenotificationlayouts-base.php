<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_MultipleNotificationLayoutsBase extends GD_Template_Processor_MultipleLayoutsBase {

	function get_data_fields($template_id, $atts) {
	
		$ret = parent::get_data_fields($template_id, $atts);

		// If multiple-layouts, then we need 'object-type' and 'action' data-fields
		$ret[] = 'object-type';
		$ret[] = 'action';
		
		return $ret;
	}
}