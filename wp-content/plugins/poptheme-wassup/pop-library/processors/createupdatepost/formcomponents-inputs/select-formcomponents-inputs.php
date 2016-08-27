<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_CUP_STATUS', PoP_ServerUtils::get_template_definition('formcomponent-cup-status'));
define ('GD_TEMPLATE_FORMCOMPONENT_LINKACCESS', PoP_ServerUtils::get_template_definition('formcomponent-linkaccess'));

class GD_Template_Processor_CreateUpdatePostSelectFormComponentInputs extends GD_Template_Processor_SelectFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_CUP_STATUS,
			GD_TEMPLATE_FORMCOMPONENT_LINKACCESS,
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_CUP_STATUS:

				return __('Publishing status', 'poptheme-wassup');

			case GD_TEMPLATE_FORMCOMPONENT_LINKACCESS:

				return __('Access type', 'poptheme-wassup');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		// Set the inputs and labels
		switch ($template_id) {
		
			case GD_TEMPLATE_FORMCOMPONENT_CUP_STATUS:

				return new GD_FormInput_ModeratedStatusDescription($options);
		
			case GD_TEMPLATE_FORMCOMPONENT_LINKACCESS:
		
				return new GD_FormInput_LinkAccessDescription($options);
		}
		
		return parent::get_input($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_CUP_STATUS:
				
				$this->append_att($template_id, $atts, 'class', 'form-input-status');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);;
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_CUP_STATUS:
				
				$ret[] = array('key' => 'value', 'field' => 'status');
				break;
		
			case GD_TEMPLATE_FORMCOMPONENT_LINKACCESS:

				$ret[] = array('key' => 'value', 'field' => 'linkaccess');
				break;
		}
		
		return $ret;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_CUP_STATUS:

				$this->add_jsmethod($ret, 'manageStatus');
				break;
		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CreateUpdatePostSelectFormComponentInputs();