<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_GF_TEMPLATE_FORMCOMPONENT_MESSAGE', PoP_TemplateIDUtils::get_template_definition('gf-field-message'));
define ('GD_GF_TEMPLATE_FORMCOMPONENT_ADDITIONALMESSAGE', PoP_TemplateIDUtils::get_template_definition('gf-field-additionalmessage'));
define ('GD_GF_TEMPLATE_FORMCOMPONENT_WHYVOLUNTEER', PoP_TemplateIDUtils::get_template_definition('gf-field-whyvolunteer'));
define ('GD_GF_TEMPLATE_FORMCOMPONENT_WHYFLAG', PoP_TemplateIDUtils::get_template_definition('gf-field-whyflag'));

class GD_GF_Template_Processor_TextareaFormComponentInputs extends GD_Template_Processor_TextareaFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_GF_TEMPLATE_FORMCOMPONENT_MESSAGE,
			GD_GF_TEMPLATE_FORMCOMPONENT_ADDITIONALMESSAGE,
			GD_GF_TEMPLATE_FORMCOMPONENT_WHYVOLUNTEER,
			GD_GF_TEMPLATE_FORMCOMPONENT_WHYFLAG,
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {
				
			case GD_GF_TEMPLATE_FORMCOMPONENT_MESSAGE:
				
				return __('Message', 'poptheme-wassup');

			case GD_GF_TEMPLATE_FORMCOMPONENT_ADDITIONALMESSAGE:

				return __('Additional message', 'poptheme-wassup');
			
			case GD_GF_TEMPLATE_FORMCOMPONENT_WHYVOLUNTEER:
				
				return __('Why do you want to volunteer?', 'poptheme-wassup');

			case GD_GF_TEMPLATE_FORMCOMPONENT_WHYFLAG:

				return __('Please explain why this post is inappropriate', 'poptheme-wassup');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function is_mandatory($template_id, $atts) {

		switch ($template_id) {

			case GD_GF_TEMPLATE_FORMCOMPONENT_MESSAGE:
			case GD_GF_TEMPLATE_FORMCOMPONENT_WHYVOLUNTEER:
			case GD_GF_TEMPLATE_FORMCOMPONENT_WHYFLAG:
				
				return true;
		}
		
		return parent::is_mandatory($template_id, $atts);
	}
	
	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {

			case GD_GF_TEMPLATE_FORMCOMPONENT_MESSAGE:
			case GD_GF_TEMPLATE_FORMCOMPONENT_WHYVOLUNTEER:
			case GD_GF_TEMPLATE_FORMCOMPONENT_WHYFLAG:

				$this->add_att($template_id, $atts, 'pop-form-clear', true);
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_GF_Template_Processor_TextareaFormComponentInputs();