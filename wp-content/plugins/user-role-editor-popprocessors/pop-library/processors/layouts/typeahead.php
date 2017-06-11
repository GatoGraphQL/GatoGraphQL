<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_LAYOUTUSER_TYPEAHEAD_SELECTED_FILTERBYCOMMUNITY', PoP_ServerUtils::get_template_definition('ure-layoutuser-typeahead-selected-filterbycommunity'));

class GD_URE_Template_Processor_TypeaheadLayouts extends GD_URE_Template_Processor_TypeaheadLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_LAYOUTUSER_TYPEAHEAD_SELECTED_FILTERBYCOMMUNITY,
		);
	}


	// function get_data_fields($template_id, $atts) {
	
	// 	switch ($template_id) {
				
	// 		case GD_URE_TEMPLATE_LAYOUTUSER_TYPEAHEAD_SELECTED_FILTERBYCOMMUNITY:
			
	// 			return array('is-community');
	// 	}
		
	// 	return parent::get_data_fields($template_id, $atts);
	// }

	// function get_template_configuration($template_id, $atts) {

	// 	$ret = parent::get_template_configuration($template_id, $atts);
	
	// 	switch ($template_id) {

	// 		case GD_URE_TEMPLATE_LAYOUTUSER_TYPEAHEAD_SELECTED_FILTERBYCOMMUNITY:		

	// 			$ret[GD_JS_TITLES/*'titles'*/]['includemembers'] = __('Include members?', 'ure-popprocessors');
	// 			$ret['community-template'] = GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITIES_POST;
	// 			break;
	// 	}
		
	// 	return $ret;
	// }

	// function get_block_jsmethod($template_id, $atts) {

	// 	$ret = parent::get_block_jsmethod($template_id, $atts);

	// 	switch ($template_id) {

	// 		case GD_URE_TEMPLATE_LAYOUTUSER_TYPEAHEAD_SELECTED_FILTERBYCOMMUNITY:

	// 			$this->add_jsmethod($ret, 'filterByCommunity', 'filterbycommunity');
	// 			break;
	// 	}

	// 	return $ret;
	// }
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_TypeaheadLayouts();