<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_QUICKLINKTYPEAHEAD_EVERYTHING', PoP_TemplateIDUtils::get_template_definition('formcomponent-quicklinktypeahead-everything'));

class GD_Template_Processor_FetchlinkTypeaheadFormComponentInputs extends GD_Template_Processor_FetchlinkTypeaheadFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_QUICKLINKTYPEAHEAD_EVERYTHING
		);
	}

	function get_components($template_id) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENT_QUICKLINKTYPEAHEAD_EVERYTHING:

				return array(
					// GD_TEMPLATE_TYPEAHEAD_COMPONENT_ALLPROFILES,
					GD_TEMPLATE_TYPEAHEAD_COMPONENT_ALLUSERS,
					GD_TEMPLATE_TYPEAHEAD_COMPONENT_ALLCONTENT,
					GD_TEMPLATE_TYPEAHEAD_COMPONENT_TAGS,
					GD_TEMPLATE_TYPEAHEAD_COMPONENT_STATICSEARCH,
				);
		}

		return parent::get_components($template_id);
	}

	function get_input_template($template_id) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENT_QUICKLINKTYPEAHEAD_EVERYTHING:

				return GD_TEMPLATE_FORMCOMPONENT_INPUTGROUP_TYPEAHEADSEARCH;
		}

		return parent::get_input_template($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENT_QUICKLINKTYPEAHEAD_EVERYTHING:

				// Comment Leo 08/12/2017: Assign the input the "searchfor" name, so that it works to perform search 
				// even when JS is disabled
				if (PoP_Frontend_ServerUtils::disable_js()) {
					$this->add_att(GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADSEARCH, $atts, 'name', GD_TEMPLATE_FILTERFORMCOMPONENT_SEARCH);
				}
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_FetchlinkTypeaheadFormComponentInputs();