<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_AppendCommentLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_SCRIPT_APPENDCOMMENT;
	}
	
	function get_data_fields($template_id, $atts) {

		$ret = parent::get_data_fields($template_id, $atts);

		$ret[] = 'post-id';
		$ret[] = 'parent';
		
		return $ret;
	}
	
	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		$ret['post-db-key'] = GD_DATABASE_KEY_POSTS;
		$ret[GD_JS_CLASSES/*'classes'*/][GD_JS_APPENDABLE/*'appendable'*/] = 'comments';
		
		return $ret;
	}
}