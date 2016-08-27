<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BUTTONWRAPPER_OPINIONATEDVOTEVIEW', PoP_ServerUtils::get_template_definition('buttonwrapper-opinionatedvoteview'));

class VotingProcessors_Template_Processor_ButtonWrappers extends GD_Template_Processor_ConditionWrapperBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BUTTONWRAPPER_OPINIONATEDVOTEVIEW,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_BUTTONWRAPPER_OPINIONATEDVOTEVIEW:

				$ret[] = GD_TEMPLATE_BUTTON_OPINIONATEDVOTEVIEW;
				break;
		}

		return $ret;
	}

	function get_condition_field($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_BUTTONWRAPPER_OPINIONATEDVOTEVIEW:

				return 'published';
		}

		return parent::get_condition_field($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_ButtonWrappers();