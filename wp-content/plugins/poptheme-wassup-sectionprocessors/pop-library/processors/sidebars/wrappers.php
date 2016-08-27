<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUTWRAPPER_PROJECT_CATEGORIES', PoP_ServerUtils::get_template_definition('layoutwrapper-project-categories'));
define ('GD_TEMPLATE_LAYOUTWRAPPER_DISCUSSION_CATEGORIES', PoP_ServerUtils::get_template_definition('layoutwrapper-discussion-categories'));

class GD_Custom_Template_Processor_WidgetWrappers extends GD_Template_Processor_ConditionWrapperBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUTWRAPPER_PROJECT_CATEGORIES,
			GD_TEMPLATE_LAYOUTWRAPPER_DISCUSSION_CATEGORIES,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUTWRAPPER_PROJECT_CATEGORIES:

				$ret[] = GD_TEMPLATE_LAYOUT_PROJECT_CATEGORIES;
				break;

			case GD_TEMPLATE_LAYOUTWRAPPER_DISCUSSION_CATEGORIES:

				$ret[] = GD_TEMPLATE_LAYOUT_DISCUSSION_CATEGORIES;
				break;
		}

		return $ret;
	}

	function get_conditionfailed_layouts($template_id) {

		$ret = parent::get_conditionfailed_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUTWRAPPER_PROJECT_CATEGORIES:
			case GD_TEMPLATE_LAYOUTWRAPPER_DISCUSSION_CATEGORIES:

				$ret[] = GD_CUSTOM_TEMPLATE_MESSAGE_NOCATEGORIES;
				break;
		}

		return $ret;
	}

	function get_condition_field($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_LAYOUTWRAPPER_PROJECT_CATEGORIES:

				return 'has-projectcategories';
					
			case GD_TEMPLATE_LAYOUTWRAPPER_DISCUSSION_CATEGORIES:

				return 'has-discussioncategories';
		}

		return parent::get_condition_field($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_WidgetWrappers();