<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_FORMCOMPONENT_CUP_CONTACTPERSON', PoP_TemplateIDUtils::get_template_definition('formcomponent-ure-cup-contactperson'));
define ('GD_URE_TEMPLATE_FORMCOMPONENT_CUP_CONTACTNUMBER', PoP_TemplateIDUtils::get_template_definition('formcomponent-ure-cup-contactnumber'));
define ('GD_URE_TEMPLATE_FORMCOMPONENT_CUP_LASTNAME', PoP_TemplateIDUtils::get_template_definition('formcomponent-ure-cup-lastname'));

class GD_URE_Template_Processor_TextFormComponentInputs extends GD_Template_Processor_TextFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_FORMCOMPONENT_CUP_LASTNAME,
			GD_URE_TEMPLATE_FORMCOMPONENT_CUP_CONTACTPERSON,
			GD_URE_TEMPLATE_FORMCOMPONENT_CUP_CONTACTNUMBER,
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_URE_TEMPLATE_FORMCOMPONENT_CUP_LASTNAME:
				
				return __('Last name', 'ure-popprocessors');

			case GD_URE_TEMPLATE_FORMCOMPONENT_CUP_CONTACTPERSON:

				// return '<i class="fa fa-fw fa-user"></i> '.__('Contact person', 'ure-popprocessors');
				return __('Contact person', 'ure-popprocessors');

			case GD_URE_TEMPLATE_FORMCOMPONENT_CUP_CONTACTNUMBER:

				// return '<i class="fa fa-fw fa-phone"></i> '.__('Telephone / Fax', 'ure-popprocessors');
				return __('Telephone / Fax', 'ure-popprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);;
	
		switch ($template_id) {

			case GD_URE_TEMPLATE_FORMCOMPONENT_CUP_LASTNAME:
				
				$ret[] = array('key' => 'value', 'field' => 'lastname');
				break;

			case GD_URE_TEMPLATE_FORMCOMPONENT_CUP_CONTACTPERSON:
				
				$ret[] = array('key' => 'value', 'field' => 'contact-person');
				break;

			case GD_URE_TEMPLATE_FORMCOMPONENT_CUP_CONTACTNUMBER:
				
				$ret[] = array('key' => 'value', 'field' => 'contact-number');
				break;
		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_TextFormComponentInputs();