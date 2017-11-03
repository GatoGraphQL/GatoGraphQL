<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERPRIVILEGES', PoP_TemplateIDUtils::get_template_definition('ure-formcomponent-memberprivileges'));
define ('GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERTAGS', PoP_TemplateIDUtils::get_template_definition('ure-formcomponent-membertags'));
define ('GD_URE_TEMPLATE_FILTERFORMCOMPONENT_MEMBERPRIVILEGES', PoP_TemplateIDUtils::get_template_definition('memberprivileges', true));
define ('GD_URE_TEMPLATE_FILTERFORMCOMPONENT_MEMBERTAGS', PoP_TemplateIDUtils::get_template_definition('membertags', true));
define ('GD_URE_TEMPLATE_FILTERFORMCOMPONENT_MEMBERSTATUS', PoP_TemplateIDUtils::get_template_definition('memberstatus', true));

class GD_URE_Template_Processor_ProfileMultiSelectFormComponentInputs extends GD_Template_Processor_MultiSelectFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERPRIVILEGES,
			GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERTAGS,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENT_MEMBERPRIVILEGES,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENT_MEMBERTAGS,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENT_MEMBERSTATUS,
		);
	}

	function is_filtercomponent($template_id) {

		switch ($template_id) {

			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_MEMBERPRIVILEGES:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_MEMBERTAGS:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_MEMBERSTATUS:

				return true;
		}
		
		return parent::is_filtercomponent($template_id);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERPRIVILEGES:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_MEMBERPRIVILEGES:

				return __('Privileges', 'ure-popprocessors');

			case GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERTAGS:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_MEMBERTAGS:

				return __('Tags', 'ure-popprocessors');

			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_MEMBERSTATUS:

				return __('Status', 'ure-popprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		// Set the inputs and labels
		switch ($template_id) {
		
			case GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERPRIVILEGES:
				
				return new GD_URE_FormInput_MemberPrivileges($options);
			
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_MEMBERPRIVILEGES:
		
				return new GD_URE_FormInput_FilterMemberPrivileges($options);

			case GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERTAGS:
				
				return new GD_URE_FormInput_MemberTags($options);
			
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_MEMBERTAGS:
		
				return new GD_URE_FormInput_FilterMemberTags($options);

			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_MEMBERSTATUS:
			
				return new GD_URE_FormInput_MultiMemberStatus($options);
		}
		
		return parent::get_input($template_id, $atts);
	}

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);;
	
		switch ($template_id) {

			case GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERPRIVILEGES:
				
				$ret[] = array('key' => 'value', 'field' => 'memberprivileges');
				break;

			case GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERTAGS:
				
				$ret[] = array('key' => 'value', 'field' => 'membertags');
				break;
		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_ProfileMultiSelectFormComponentInputs();