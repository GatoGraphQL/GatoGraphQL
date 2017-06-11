<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_URE_Template_Processor_TypeaheadLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_LAYOUTUSER_TYPEAHEAD_SELECTED_FILTERBYCOMMUNITY;
	}

	function get_data_fields($template_id, $atts) {
	
		return array('is-community');
	}

	function get_title($template_id, $atts) {
	
		return __('Include members?', 'ure-popprocessors');
	}

	function get_community_template($template_id, $atts) {
	
		return GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITIES_POST;
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);
	
		// switch ($template_id) {

		// 	case GD_URE_TEMPLATE_LAYOUTUSER_TYPEAHEAD_SELECTED_FILTERBYCOMMUNITY:		

		$ret[GD_JS_TITLES/*'titles'*/]['includemembers'] = $this->get_title($template_id, $atts);
		$ret['community-template'] = $this->get_community_template($template_id, $atts);
		// 		break;
		// }
		
		return $ret;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		$this->add_jsmethod($ret, 'filterByCommunity', 'filterbycommunity');

		return $ret;
	}
}
