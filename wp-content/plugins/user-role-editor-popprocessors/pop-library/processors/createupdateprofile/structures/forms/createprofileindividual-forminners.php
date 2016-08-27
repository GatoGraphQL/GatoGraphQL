<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMINNER_PROFILEINDIVIDUAL_CREATE', PoP_ServerUtils::get_template_definition('forminner-profileindividual-create'));

class GD_URE_Template_Processor_CreateProfileIndividualFormInners extends GD_URE_Template_Processor_CreateProfileIndividualFormInnersBase {

	function get_templates_to_process() {
	
		return array(			
			GD_TEMPLATE_FORMINNER_PROFILEINDIVIDUAL_CREATE
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CreateProfileIndividualFormInners();