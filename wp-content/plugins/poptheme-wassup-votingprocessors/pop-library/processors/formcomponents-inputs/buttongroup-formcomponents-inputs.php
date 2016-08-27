<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_BUTTONGROUP_STANCE', PoP_ServerUtils::get_template_definition('formcomponent-buttongroup-stance'));

class VotingProcessors_Template_Processor_ButtonGroupFormComponentInputs extends GD_Template_Processor_ButtonGroupFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_BUTTONGROUP_STANCE
		);
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		// Set the inputs and labels
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_BUTTONGROUP_STANCE:

				return new GD_FormInput_Stance($options);
		}
		
		return parent::get_input($template_id, $atts);
	}

	// function get_inputbtn_classes($template_id, $atts) {

	// 	$ret = parent::get_inputbtn_classes($template_id, $atts);
	
	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_FORMCOMPONENT_BUTTONGROUP_STANCE:
				
	// 			$ret['pro'] = 'btn-success';
	// 			$ret['against'] = 'btn-danger';
	// 			$ret['neutral'] = 'btn-info';
	// 			break;
	// 	}
		
	// 	return $ret;
	// }

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);;
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_BUTTONGROUP_STANCE:
				
				$ret[] = array('key' => 'value', 'field' => 'stance');
				break;
		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_ButtonGroupFormComponentInputs();