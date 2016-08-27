<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_AAL_TEMPLATE_BUTTON_NOTIFICATIONPREVIEWLINK', PoP_ServerUtils::get_template_definition('aal-button-notificationpreviewlink'));
define ('GD_AAL_TEMPLATE_BUTTON_USERVIEW', PoP_ServerUtils::get_template_definition('aal-button-userview'));
define ('GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASREAD', PoP_ServerUtils::get_template_definition('aal-button-notification-markasread'));
define ('GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASUNREAD', PoP_ServerUtils::get_template_definition('aal-button-notification-markasunread'));

class AAL_PoPProcessors_Template_Processor_Buttons extends GD_Template_Processor_ButtonsBase {

	function get_templates_to_process() {
	
		return array(
			GD_AAL_TEMPLATE_BUTTON_NOTIFICATIONPREVIEWLINK,
			GD_AAL_TEMPLATE_BUTTON_USERVIEW,
			GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASREAD,
			GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASUNREAD,
		);
	}

	function get_buttoninner_template($template_id) {

		switch ($template_id) {

			case GD_AAL_TEMPLATE_BUTTON_NOTIFICATIONPREVIEWLINK:

				return GD_AAL_TEMPLATE_BUTTONINNER_NOTIFICATIONPREVIEWLINK;

			case GD_AAL_TEMPLATE_BUTTON_USERVIEW:

				return GD_AAL_TEMPLATE_BUTTONINNER_USERVIEW;

			case GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASREAD:

				return GD_AAL_TEMPLATE_BUTTONINNER_NOTIFICATION_MARKASREAD;

			case GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASUNREAD:

				return GD_AAL_TEMPLATE_BUTTONINNER_NOTIFICATION_MARKASUNREAD;
		}

		return parent::get_buttoninner_template($template_id);
	}

	function get_url_field($template_id) {
		
		switch ($template_id) {

			case GD_AAL_TEMPLATE_BUTTON_NOTIFICATIONPREVIEWLINK:

				return 'url';

			case GD_AAL_TEMPLATE_BUTTON_USERVIEW:

				return 'user-url';

			case GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASREAD:

				return 'mark-as-read-url';

			case GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASUNREAD:

				return 'mark-as-unread-url';
		}

		return parent::get_url_field($template_id);
	}

	function show_tooltip($template_id, $atts) {
		
		switch ($template_id) {

			case GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASREAD:
			case GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASUNREAD:

				return true;
		}

		return parent::show_tooltip($template_id, $atts);
	}

	function get_title($template_id) {
		
		switch ($template_id) {

			case GD_AAL_TEMPLATE_BUTTON_USERVIEW:

				return __('View', 'aal-popprocessors');

			case GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASREAD:

				return __('Mark as read', 'aal-popprocessors');

			case GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASUNREAD:

				return __('Mark as unread', 'aal-popprocessors');
		}
		
		return parent::get_title($template_id);
	}

	function get_btn_class($template_id, $atts) {

		$ret = parent::get_btn_class($template_id, $atts);

		switch ($template_id) {

			case GD_AAL_TEMPLATE_BUTTON_NOTIFICATIONPREVIEWLINK:

				$ret .= ' url';
				break;

			case GD_AAL_TEMPLATE_BUTTON_USERVIEW:

				$ret .= ' btn btn-xs btn-default';
				break;

			case GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASREAD:
			case GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASUNREAD:

				$ret .= ' btn btn-xs btn-link';
				break;
		}

		switch ($template_id) {
					
			case GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASREAD:
				
				$ret .= ' pop-functional '.AAL_CLASS_NOTIFICATION_MARKASREAD;
				break;
					
			case GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASUNREAD:
				
				$ret .= ' pop-functional '.AAL_CLASS_NOTIFICATION_MARKASUNREAD;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new AAL_PoPProcessors_Template_Processor_Buttons();