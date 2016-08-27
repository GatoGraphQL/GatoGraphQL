<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_CUSTOM_TEMPLATE_FORMINNER_PROFILEORGANIZATION_CREATE', PoP_ServerUtils::get_template_definition('custom-forminner-profileorganization-create'));

class GD_URE_Custom_Template_Processor_CreateProfileOrganizationFormInners extends GD_URE_Template_Processor_CreateProfileOrganizationFormInnersBase {

	function get_templates_to_process() {
	
		return array(			
			GD_CUSTOM_TEMPLATE_FORMINNER_PROFILEORGANIZATION_CREATE,
		);
	}

	function get_layouts($template_id) {
	
		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_CUSTOM_TEMPLATE_FORMINNER_PROFILEORGANIZATION_CREATE:
	
				// Add common Create/Update components
				GD_Custom_Template_Processor_CreateUpdateProfileOrganizationFormsUtils::get_components($template_id, $ret, $this);
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Custom_Template_Processor_CreateProfileOrganizationFormInners();