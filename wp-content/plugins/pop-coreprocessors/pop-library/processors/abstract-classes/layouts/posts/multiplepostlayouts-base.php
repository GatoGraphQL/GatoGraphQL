<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_MultiplePostLayoutsBase extends GD_Template_Processor_MultipleLayoutsBase {

	function get_data_fields($template_id, $atts) {
	
		$ret = parent::get_data_fields($template_id, $atts);

		// If multiple-layouts, then we need 'post-type' and 'cats' data-fields
		$ret[] = 'post-type';
		$ret[] = 'cat';// 'cats';
		
		return $ret;
	}
}