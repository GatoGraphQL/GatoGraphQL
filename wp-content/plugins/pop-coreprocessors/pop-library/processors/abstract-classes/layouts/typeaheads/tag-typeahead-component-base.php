<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUTTAG_TYPEAHEAD_COMPONENT', PoP_ServerUtils::get_template_definition('layouttag-typeahead-component'));

class GD_Template_Processor_TagTypeaheadComponentLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_LAYOUTTAG_TYPEAHEAD_COMPONENT;
	}

	function get_data_fields($template_id, $atts) {
	
		// return array('name', 'symbol');
		return array('namedescription', 'symbol');
	}
}
