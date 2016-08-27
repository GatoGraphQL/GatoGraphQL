<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_LatestCountScriptsLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_LAYOUT_LATESTCOUNTSCRIPT;
	}

	function get_data_fields($template_id, $atts) {

		return array_merge(
			parent::get_data_fields($template_id, $atts),
			array('post-type', 'cats', 'authors', 'tags', 'references')
		);
	}
}