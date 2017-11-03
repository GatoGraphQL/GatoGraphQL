<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEAD', PoP_TemplateIDUtils::get_template_definition('formcomponent-text-typeahead'));
define ('GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADSEARCH', PoP_TemplateIDUtils::get_template_definition('formcomponent-text-typeaheadsearch'));
define ('GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADPROFILES', PoP_TemplateIDUtils::get_template_definition('formcomponent-text-typeaheadprofiles'));
define ('GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADPOSTAUTHORS', PoP_TemplateIDUtils::get_template_definition('formcomponent-text-typeaheadpostauthors'));
define ('GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADRELATEDCONTENT', PoP_TemplateIDUtils::get_template_definition('formcomponent-text-typeaheadrelatedcontent'));

class GD_Template_Processor_TypeaheadTextFormComponentInputs extends GD_Template_Processor_TextFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEAD,
			GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADSEARCH,
			GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADPROFILES,
			GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADPOSTAUTHORS,
			GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADRELATEDCONTENT,
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADSEARCH:

				return sprintf(__('Search %s', 'pop-coreprocessors'), get_bloginfo('name'));

			case GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADPROFILES:
			case GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADPOSTAUTHORS:
		
				return __('Author(s)', 'pop-coreprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADRELATEDCONTENT:

				// return __('Responded/Annotated by', 'pop-coreprocessors');
				// return __('Post title', 'pop-coreprocessors');
				return __('In response to', 'pop-coreprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADSEARCH:
				$this->add_jsmethod($ret, 'typeaheadSearchInput');
				break;
		}
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_TypeaheadTextFormComponentInputs();