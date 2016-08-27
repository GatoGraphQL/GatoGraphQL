<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_FORMCOMPONENT_CUP_ISCOMMUNITY', PoP_ServerUtils::get_template_definition('ure-cup-iscommunity'));
define ('GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERSTATUS', PoP_ServerUtils::get_template_definition('ure-formcomponent-memberstatus'));

class GD_URE_Template_Processor_SelectFormComponentInputs extends GD_Template_Processor_SelectFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_FORMCOMPONENT_CUP_ISCOMMUNITY,
			GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERSTATUS,
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_URE_TEMPLATE_FORMCOMPONENT_CUP_ISCOMMUNITY:
				
				return __('Does your organization accept members?', 'ure-popprocessors');

			case GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERSTATUS:
		
				return __('Status', 'ure-popprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function is_mandatory($template_id, $atts) {

		switch ($template_id) {

			case GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERSTATUS:
				
				return true;
		}
		
		return parent::is_mandatory($template_id, $atts);
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		// Set the inputs and labels
		switch ($template_id) {
		
			case GD_URE_TEMPLATE_FORMCOMPONENT_CUP_ISCOMMUNITY:
			
				return new GD_FormInput_YesNo($options);
		
			case GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERSTATUS:
			
				return new GD_URE_FormInput_MemberStatus($options);
		}
		
		return parent::get_input($template_id, $atts);
	}

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);;
	
		switch ($template_id) {

			case GD_URE_TEMPLATE_FORMCOMPONENT_CUP_ISCOMMUNITY:
				
				$ret[] = array('key' => 'value', 'field' => 'is-community-string');
				break;

			case GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERSTATUS:
				
				$ret[] = array('key' => 'value', 'field' => 'memberstatus');
				break;
		}
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {

			case GD_URE_TEMPLATE_FORMCOMPONENT_CUP_ISCOMMUNITY:

				if (!$this->load_itemobject_value($template_id, $atts)) {
					
					// Make it a Community by default
					$this->add_att($template_id, $atts, 'selected', true);
				}
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_SelectFormComponentInputs();