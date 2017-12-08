<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORM_INVITENEWUSERS', PoP_TemplateIDUtils::get_template_definition('form-inviteusers'));
define ('GD_TEMPLATE_FORM_EVERYTHINGQUICKLINKS', PoP_TemplateIDUtils::get_template_definition('form-everythingquicklinks'));

class PoP_Core_Template_Processor_Forms extends GD_Template_Processor_FormsBase {

	function get_templates_to_process() {
	
		return array(			
			GD_TEMPLATE_FORM_INVITENEWUSERS,
			GD_TEMPLATE_FORM_EVERYTHINGQUICKLINKS,
		);
	}

	function get_inner_template($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_FORM_INVITENEWUSERS:

				return GD_TEMPLATE_FORMINNER_INVITENEWUSERS;

			case GD_TEMPLATE_FORM_EVERYTHINGQUICKLINKS:

				return GD_TEMPLATE_FORMINNER_EVERYTHINGQUICKLINKS;
		}

		return parent::get_inner_template($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_Core_Template_Processor_Forms();