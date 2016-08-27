<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_FEATUREDIMAGE', PoP_ServerUtils::get_template_definition('formcomponent-featuredimage'));

class GD_Template_Processor_FeaturedImageComponentInputs extends GD_Template_Processor_FeaturedImageFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_FEATUREDIMAGE
		);
	}

	function get_featuredimageinner($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_FEATUREDIMAGE:

				return GD_TEMPLATE_FORMCOMPONENT_FEATUREDIMAGEINNER;
		}

		return parent::get_featuredimageinner($template_id);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_FEATUREDIMAGE:
				
				return __('Featured Image', 'pop-coreprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function is_mandatory($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_FEATUREDIMAGE:
				
				return true;
		}
		
		return parent::is_mandatory($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_FeaturedImageComponentInputs();