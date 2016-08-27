<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_FetchlinkTypeaheadFormComponentsBase extends GD_Template_Processor_TypeaheadFormComponentsBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_FORMCOMPONENT_FETCHLINKTYPEAHEAD;
	}

	function get_typeahead_class($template_id, $atts) {

		$ret = parent::get_typeahead_class($template_id, $atts);
		$ret .= ' fetchlink';	
		return $ret;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		$this->add_jsmethod($ret, 'fetchlinkTypeahead');
		return $ret;
	}

	function get_data_fields($template_id, $atts) {
	
		$ret = parent::get_data_fields($template_id, $atts);

		// Add the 'URL' since that's where it will go upon selection of the typeahead value
		$ret[] = 'url';

		return $ret;
	}	
}
