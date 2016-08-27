<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY', PoP_ServerUtils::get_template_definition('buttonwrapper-userpostactivity'));

class GD_Template_Processor_FeedButtonWrappers extends GD_Template_Processor_ConditionWrapperBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY:

				$ret[] = GD_TEMPLATE_BUTTON_TOGGLEUSERPOSTACTIVITY;
				break;
		}

		return $ret;
	}

	function get_condition_field($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY:

				return 'has-userpostactivity';
		}

		return parent::get_condition_field($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY:

				$this->append_att($template_id, $atts, 'class', 'pop-collapse-btn');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_FeedButtonWrappers();