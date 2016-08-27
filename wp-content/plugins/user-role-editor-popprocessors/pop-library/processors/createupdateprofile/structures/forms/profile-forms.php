<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORM_EDITMEMBERSHIP', PoP_ServerUtils::get_template_definition('form-editmembership'));
define ('GD_TEMPLATE_FORM_MYCOMMUNITIES_UPDATE', PoP_ServerUtils::get_template_definition('form-mycommunities-update'));

class GD_URE_Template_Processor_ProfileForms extends GD_Template_Processor_FormsBase {

	function get_templates_to_process() {
	
		return array(			
			// GD_TEMPLATE_FORM_INVITENEWMEMBERS,
			GD_TEMPLATE_FORM_EDITMEMBERSHIP,
			GD_TEMPLATE_FORM_MYCOMMUNITIES_UPDATE,
		);
	}

	function get_inner_template($template_id) {
	
		switch ($template_id) {

			// case GD_TEMPLATE_FORM_INVITENEWMEMBERS:

			// 	return GD_TEMPLATE_FORMINNER_INVITENEWMEMBERS;

			case GD_TEMPLATE_FORM_EDITMEMBERSHIP:

				return GD_TEMPLATE_FORMINNER_EDITMEMBERSHIP;

			case GD_TEMPLATE_FORM_MYCOMMUNITIES_UPDATE:

				return GD_TEMPLATE_FORMINNER_MYCOMMUNITIES_UPDATE;
		}

		return parent::get_inner_template($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_ProfileForms();