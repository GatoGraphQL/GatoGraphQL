<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BUTTONWRAPPER_POSTVIEW', PoP_TemplateIDUtils::get_template_definition('buttonwrapper-postview'));
define ('GD_TEMPLATE_BUTTONWRAPPER_POSTPREVIEW', PoP_TemplateIDUtils::get_template_definition('buttonwrapper-postpreview'));
define ('GD_TEMPLATE_BUTTONWRAPPER_POSTPERMALINK', PoP_TemplateIDUtils::get_template_definition('buttonwrapper-postpermalink'));

class GD_Template_Processor_ButtonWrappers extends GD_Template_Processor_ConditionWrapperBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BUTTONWRAPPER_POSTVIEW,
			GD_TEMPLATE_BUTTONWRAPPER_POSTPREVIEW,
			GD_TEMPLATE_BUTTONWRAPPER_POSTPERMALINK,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_BUTTONWRAPPER_POSTVIEW:

				$ret[] = GD_TEMPLATE_BUTTON_POSTVIEW;
				break;

			case GD_TEMPLATE_BUTTONWRAPPER_POSTPREVIEW:

				$ret[] = GD_TEMPLATE_BUTTON_POSTPREVIEW;
				break;

			case GD_TEMPLATE_BUTTONWRAPPER_POSTPERMALINK:

				$ret[] = GD_TEMPLATE_BUTTON_POSTPERMALINK;
				break;
		}

		return $ret;
	}

	function get_condition_field($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_BUTTONWRAPPER_POSTVIEW:
			case GD_TEMPLATE_BUTTONWRAPPER_POSTPERMALINK:

				return 'published';

			case GD_TEMPLATE_BUTTONWRAPPER_POSTPREVIEW:

				return 'not-published';
		}

		return parent::get_condition_field($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_ButtonWrappers();