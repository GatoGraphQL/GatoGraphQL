<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_MESSAGE', PoP_TemplateIDUtils::get_template_definition('gf-field-message'));
define ('GD_TEMPLATE_FORMCOMPONENT_ADDITIONALMESSAGE', PoP_TemplateIDUtils::get_template_definition('gf-field-additionalmessage'));
define ('GD_TEMPLATE_FORMCOMPONENT_WHYVOLUNTEER', PoP_TemplateIDUtils::get_template_definition('gf-field-whyvolunteer'));
define ('GD_TEMPLATE_FORMCOMPONENT_WHYFLAG', PoP_TemplateIDUtils::get_template_definition('gf-field-whyflag'));

class GenericForms_Template_Processor_TextareaFormComponentInputs extends GD_Template_Processor_TextareaFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_MESSAGE,
			GD_TEMPLATE_FORMCOMPONENT_ADDITIONALMESSAGE,
			GD_TEMPLATE_FORMCOMPONENT_WHYVOLUNTEER,
			GD_TEMPLATE_FORMCOMPONENT_WHYFLAG,
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENT_MESSAGE:
				
				return __('Message', 'pop-genericforms');

			case GD_TEMPLATE_FORMCOMPONENT_ADDITIONALMESSAGE:

				return __('Additional message', 'pop-genericforms');
			
			case GD_TEMPLATE_FORMCOMPONENT_WHYVOLUNTEER:
				
				return __('Why do you want to volunteer?', 'pop-genericforms');

			case GD_TEMPLATE_FORMCOMPONENT_WHYFLAG:

				return __('Please explain why this post is inappropriate', 'pop-genericforms');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function is_mandatory($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_MESSAGE:
			case GD_TEMPLATE_FORMCOMPONENT_WHYVOLUNTEER:
			case GD_TEMPLATE_FORMCOMPONENT_WHYFLAG:
				
				return true;
		}
		
		return parent::is_mandatory($template_id, $atts);
	}
	
	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_MESSAGE:
			case GD_TEMPLATE_FORMCOMPONENT_WHYVOLUNTEER:
			case GD_TEMPLATE_FORMCOMPONENT_WHYFLAG:

				$this->add_att($template_id, $atts, 'pop-form-clear', true);
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GenericForms_Template_Processor_TextareaFormComponentInputs();