<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORM_PROFILEORGANIZATION_CREATE', PoP_TemplateIDUtils::get_template_definition('form-profileorganization-create'));
define ('GD_TEMPLATE_FORM_PROFILEINDIVIDUAL_CREATE', PoP_TemplateIDUtils::get_template_definition('form-profileindividual-create'));

class GD_URE_Template_Processor_CreateProfileForms extends GD_Template_Processor_FormsBase {

	function get_templates_to_process() {
	
		return array(			
			GD_TEMPLATE_FORM_PROFILEORGANIZATION_CREATE,
			GD_TEMPLATE_FORM_PROFILEINDIVIDUAL_CREATE
		);
	}

	function get_inner_template($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_FORM_PROFILEORGANIZATION_CREATE:
	
				// Allow the Custom implementation to override
				return apply_filters(
					'GD_URE_Template_Processor_CreateProfileForms:get_inner_template:profileorganization',
					GD_TEMPLATE_FORMINNER_PROFILEORGANIZATION_CREATE
				);

			case GD_TEMPLATE_FORM_PROFILEINDIVIDUAL_CREATE:

				return apply_filters(
					'GD_URE_Template_Processor_CreateProfileForms:get_inner_template:profileindividual',
					GD_TEMPLATE_FORMINNER_PROFILEINDIVIDUAL_CREATE
				);
		}

		return parent::get_inner_template($template_id);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		switch ($template_id) {
		
			case GD_TEMPLATE_FORM_PROFILEORGANIZATION_CREATE:
			case GD_TEMPLATE_FORM_PROFILEINDIVIDUAL_CREATE:

				$this->add_jsmethod($ret, 'addDomainClass');
				break;
		}
		
		return $ret;
	}
	function get_js_setting($template_id, $atts) {

		$ret = parent::get_js_setting($template_id, $atts);

		switch ($template_id) {
		
			case GD_TEMPLATE_FORM_PROFILEORGANIZATION_CREATE:
			case GD_TEMPLATE_FORM_PROFILEINDIVIDUAL_CREATE:

				// For function addDomainClass
				$ret['prefix'] = 'visible-notloggedin-';
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_FORM_PROFILEORGANIZATION_CREATE:
			case GD_TEMPLATE_FORM_PROFILEINDIVIDUAL_CREATE:

				// Do not show if user already logged in
				$this->append_att($template_id, $atts, 'class', 'visible-notloggedin');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CreateProfileForms();