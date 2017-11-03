<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMINNER_PROFILEINDIVIDUAL_UPDATE', PoP_TemplateIDUtils::get_template_definition('forminner-profileindividual-update'));

class GD_URE_Template_Processor_UpdateProfileIndividualFormInners extends GD_URE_Template_Processor_UpdateProfileIndividualFormInnersBase {

	function get_templates_to_process() {
	
		return array(			
			GD_TEMPLATE_FORMINNER_PROFILEINDIVIDUAL_UPDATE,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_UpdateProfileIndividualFormInners();