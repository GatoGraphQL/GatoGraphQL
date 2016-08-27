<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUTWRAPPER_FARM_CATEGORIES', PoP_ServerUtils::get_template_definition('layoutwrapper-farm-categories'));

class OP_Template_Processor_WidgetWrappers extends GD_Template_Processor_ConditionWrapperBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUTWRAPPER_FARM_CATEGORIES,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUTWRAPPER_FARM_CATEGORIES:

				$ret[] = GD_TEMPLATE_LAYOUT_FARM_CATEGORIES;
				break;
		}

		return $ret;
	}

	function get_conditionfailed_layouts($template_id) {

		$ret = parent::get_conditionfailed_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUTWRAPPER_FARM_CATEGORIES:

				$ret[] = GD_CUSTOM_TEMPLATE_MESSAGE_NOCATEGORIES;
				break;
		}

		return $ret;
	}

	function get_condition_field($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_LAYOUTWRAPPER_FARM_CATEGORIES:

				return 'has-farmcategories';
		}

		return parent::get_condition_field($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_WidgetWrappers();