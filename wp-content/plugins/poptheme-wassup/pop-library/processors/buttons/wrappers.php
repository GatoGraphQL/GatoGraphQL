<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BUTTONWRAPPER_HIGHLIGHTVIEW', PoP_TemplateIDUtils::get_template_definition('buttonwrapper-highlightview'));

class Wassup_Template_Processor_ButtonWrappers extends GD_Template_Processor_ConditionWrapperBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BUTTONWRAPPER_HIGHLIGHTVIEW,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_BUTTONWRAPPER_HIGHLIGHTVIEW:

				$ret[] = GD_TEMPLATE_BUTTON_HIGHLIGHTVIEW;
				break;
		}

		return $ret;
	}

	function get_condition_field($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_BUTTONWRAPPER_HIGHLIGHTVIEW:

				return 'published';
		}

		return parent::get_condition_field($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_Template_Processor_ButtonWrappers();