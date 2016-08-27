<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_PostTypeaheadComponentLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_LAYOUTPOST_TYPEAHEAD_COMPONENT;
	}

	function get_data_fields($template_id, $atts) {
	
		$thumb = 'thumb-xs';
		return array('id', $thumb, 'title', 'url');
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		$thumb = 'thumb-xs';
		$ret['thumb'] = array(
			'name' => $thumb,
		);
		
		return $ret;
	}
}
