<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MULTIPLENOTIFICATION_DETAILS', PoP_ServerUtils::get_template_definition('layout-multiplenotification-details'));
define ('GD_TEMPLATE_LAYOUT_MULTIPLENOTIFICATION_LIST', PoP_ServerUtils::get_template_definition('layout-multiplenotification-list'));

class GD_Template_Processor_MultipleNotificationLayouts extends GD_Template_Processor_MultipleNotificationLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MULTIPLENOTIFICATION_DETAILS,
			GD_TEMPLATE_LAYOUT_MULTIPLENOTIFICATION_LIST,
		);
	}

	function get_default_layout($template_id) {

		$defaults = array(
			GD_TEMPLATE_LAYOUT_MULTIPLENOTIFICATION_DETAILS => GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_DETAILS,
			GD_TEMPLATE_LAYOUT_MULTIPLENOTIFICATION_LIST => GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_LIST,
		);

		if ($default = $defaults[$template_id]) {

			return $default;
		}

		return parent::get_default_layout($template_id);
	}

	function get_multiple_layouts($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MULTIPLENOTIFICATION_DETAILS:

				return apply_filters(
					'GD_Template_Processor_MultipleNotificationLayouts:layouts:details',
					array()
				);

			case GD_TEMPLATE_LAYOUT_MULTIPLENOTIFICATION_LIST:

				return apply_filters(
					'GD_Template_Processor_MultipleNotificationLayouts:layouts:list',
					array()
				);
		}

		return parent::get_multiple_layouts($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_MultipleNotificationLayouts();