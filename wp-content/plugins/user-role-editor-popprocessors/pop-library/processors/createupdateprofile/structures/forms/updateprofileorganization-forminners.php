<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMINNER_PROFILEORGANIZATION_UPDATE', PoP_TemplateIDUtils::get_template_definition('forminner-profileorganization-update'));

class GD_URE_Template_Processor_UpdateProfileOrganizationFormInners extends GD_URE_Template_Processor_UpdateProfileOrganizationFormInnersBase {

	function get_templates_to_process() {
	
		return array(			
			GD_TEMPLATE_FORMINNER_PROFILEORGANIZATION_UPDATE,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_UpdateProfileOrganizationFormInners();