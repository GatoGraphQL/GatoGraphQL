<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMINNER_PROFILEORGANIZATION_CREATE', PoP_ServerUtils::get_template_definition('forminner-profileorganization-create'));

class GD_URE_Template_Processor_CreateProfileOrganizationFormInners extends GD_URE_Template_Processor_CreateProfileOrganizationFormInnersBase {

	function get_templates_to_process() {
	
		return array(			
			GD_TEMPLATE_FORMINNER_PROFILEORGANIZATION_CREATE,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CreateProfileOrganizationFormInners();