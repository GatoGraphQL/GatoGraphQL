<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CustomFullUserLayoutsBase extends GD_Template_Processor_FullUserLayoutsBase {

	function get_title_template($template_id) {

		// Allow URE to Change it, to inject the (Organization / Organization+Members) links
		// return apply_filters(
		// 	'GD_Template_Processor_CustomFullUserLayoutsBase:title_template',
		// 	GD_TEMPLATE_LAYOUT_FULLUSERTITLE
		// );
		return GD_TEMPLATE_LAYOUT_FULLUSERTITLE;
	}

	function show_description($template_id, $atts) {

		// Show the description only if configured to show in the body, otherwise it will be a widget
		return PoPTheme_Wassup_Utils::author_fulldescription();
	}

	function get_template_configuration($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = parent::get_template_configuration($template_id, $atts);

		$ret[GD_JS_CLASSES/*'classes'*/]['sidebar'] = 'col-sm-12';
		$ret[GD_JS_CLASSES/*'classes'*/]['content-body'] = 'col-sm-12';
		
		return $ret;
	}
}