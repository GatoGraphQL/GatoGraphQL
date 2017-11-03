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
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_FetchlinkTypeaheadFormComponentInputs();