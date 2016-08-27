<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORM_PROFILEORGANIZATION_UPDATE', PoP_ServerUtils::get_template_definition('form-profileorganization-update'));
define ('GD_TEMPLATE_FORM_PROFILEINDIVIDUAL_UPDATE', PoP_ServerUtils::get_template_definition('form-profileindividual-update'));

class GD_URE_Template_Processor_UpdateProfileForms extends GD_Template_Processor_FormsBase {

	function get_templates_to_process() {
	
		return array(			
			GD_TEMPLATE_FORM_PROFILEORGANIZATION_UPDATE,
			GD_TEMPLATE_FORM_PROFILEINDIVIDUAL_UPDATE,
		);
	}

	function get_inner_template($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_FORM_PROFILEORGANIZATION_UPDATE:

				return apply_filters('GD_URE_Template_Processor_UpdateProfileForms:get_inner_template:profileorganization', GD_TEMPLATE_FORMINNER_PROFILEORGANIZATION_UPDATE);

			case GD_TEMPLATE_FORM_PROFILEINDIVIDUAL_UPDATE:

				return apply_filters('GD_URE_Template_Processor_UpdateProfileForms:get_inner_template:profileindividual', GD_TEMPLATE_FORMINNER_PROFILEINDIVIDUAL_UPDATE);
		}

		return parent::get_inner_template($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_UpdateProfileForms();