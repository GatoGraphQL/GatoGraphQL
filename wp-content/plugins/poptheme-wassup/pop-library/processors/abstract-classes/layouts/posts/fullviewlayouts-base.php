<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CustomFullViewLayoutsBase extends GD_Template_Processor_FullViewLayoutsBase {

	function get_title_template($template_id) {

		return GD_TEMPLATE_LAYOUT_FULLVIEWTITLE;
	}

	function get_template_configuration($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = parent::get_template_configuration($template_id, $atts);

		$ret[GD_JS_CLASSES/*'classes'*/]['sidebar'] = 'col-sm-12';
		$ret[GD_JS_CLASSES/*'classes'*/]['content-body'] = 'col-sm-12';
		
		return $ret;
	}
}
