<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_TagMentionComponentLayoutsBase extends GD_Template_Processor_TagTypeaheadComponentLayoutsBase {

	function get_data_fields($template_id, $atts) {

		$ret = parent::get_data_fields($template_id, $atts);
	
		$ret[] = 'mention-queryby';
		
		return $ret;
	}
}
