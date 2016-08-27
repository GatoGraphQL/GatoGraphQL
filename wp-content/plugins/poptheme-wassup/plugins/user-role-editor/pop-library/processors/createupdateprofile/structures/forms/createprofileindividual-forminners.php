<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_CUSTOM_TEMPLATE_FORMINNER_PROFILEINDIVIDUAL_CREATE', PoP_ServerUtils::get_template_definition('custom-forminner-profileindividual-create'));

class GD_URE_Custom_Template_Processor_CreateProfileIndividualFormInners extends GD_URE_Template_Processor_CreateProfileIndividualFormInnersBase {

	function get_templates_to_process() {
	
		return array(			
			GD_CUSTOM_TEMPLATE_FORMINNER_PROFILEINDIVIDUAL_CREATE
		);
	}

	function get_layouts($template_id) {
	
		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_CUSTOM_TEMPLATE_FORMINNER_PROFILEINDIVIDUAL_CREATE:

				// Add common Create/Update components
				GD_Custom_Template_Processor_CreateUpdateProfileIndividualFormsUtils::get_components($template_id, $ret, $this);
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Custom_Template_Processor_CreateProfileIndividualFormInners();