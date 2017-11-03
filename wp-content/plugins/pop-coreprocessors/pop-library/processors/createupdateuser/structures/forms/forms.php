<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORM_USER_CHANGEPASSWORD', PoP_TemplateIDUtils::get_template_definition('form-user-changepwd'));
define ('GD_TEMPLATE_FORM_MYPREFERENCES', PoP_TemplateIDUtils::get_template_definition('form-mypreferences'));

class GD_Template_Processor_UserForms extends GD_Template_Processor_FormsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORM_USER_CHANGEPASSWORD,
			GD_TEMPLATE_FORM_MYPREFERENCES,
		);
	}

	function get_inner_template($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_FORM_USER_CHANGEPASSWORD:

				return GD_TEMPLATE_FORMINNER_USER_CHANGEPASSWORD;

			case GD_TEMPLATE_FORM_MYPREFERENCES:

				return GD_TEMPLATE_FORMINNER_MYPREFERENCES;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_FORM_MYPREFERENCES:			

				// For security reasons: delete passwords (more since introducing Login block in offcanvas, so that it will stay there forever and other users could re-login using the exisiting filled-in info)
				$this->append_att($template_id, $atts, 'class', 'form-mypreferences');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_UserForms();