<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_TYPEAHEAD_COMPONENT_TAGS', PoP_TemplateIDUtils::get_template_definition('formcomponent-typeaheadcomponent-tags'));

class GD_Template_Processor_TagTypeaheadComponentFormComponentInputs extends GD_Template_Processor_TagTypeaheadComponentFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_TYPEAHEAD_COMPONENT_TAGS,
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_TYPEAHEAD_COMPONENT_TAGS:

				return gd_navigation_menu_item(POP_WPAPI_PAGE_TAGS, true).__('Tags:', 'pop-coreprocessors');
		}

		return parent::get_label_text($template_id, $atts);
	}

	protected function get_typeahead_dataload_source($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_TYPEAHEAD_COMPONENT_TAGS:

				return get_permalink(POP_WPAPI_PAGE_TAGS);
		}

		return parent::get_typeahead_dataload_source($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_TagTypeaheadComponentFormComponentInputs();