<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_AAL_TEMPLATE_BUTTONWRAPPER_NOTIFICATION_MARKASREAD', PoP_ServerUtils::get_template_definition('aal-buttonwrapper-notification-markasread'));

class GD_AAL_Template_Processor_ButtonWrappers extends GD_Template_Processor_ConditionWrapperBase {

	function get_templates_to_process() {
	
		return array(
			GD_AAL_TEMPLATE_BUTTONWRAPPER_NOTIFICATION_MARKASREAD,
		);
	}

	// function get_failed_layouts($template_id) {

	// 	$ret = parent::get_failed_layouts($template_id);
	
	// 	switch ($template_id) {

	// 		case GD_AAL_TEMPLATE_BUTTONWRAPPER_NOTIFICATION_MARKASREAD:

	// 			$ret[] = GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASREAD;
	// 			break;
	// 	}

	// 	return $ret;
	// }

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_AAL_TEMPLATE_BUTTONWRAPPER_NOTIFICATION_MARKASREAD:

				$ret[] = GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASREAD;
				// $ret[] = GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASUNREAD;
				break;
		}

		return $ret;
	}

	function get_condition_field($template_id) {

		switch ($template_id) {
					
			case GD_AAL_TEMPLATE_BUTTONWRAPPER_NOTIFICATION_MARKASREAD:

				return 'is-status-not-read';
				// return 'is-status-read';
		}

		return parent::get_condition_field($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_AAL_Template_Processor_ButtonWrappers();