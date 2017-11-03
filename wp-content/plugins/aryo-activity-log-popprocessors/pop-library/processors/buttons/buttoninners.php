<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_AAL_TEMPLATE_BUTTONINNER_NOTIFICATIONPREVIEWLINK', PoP_TemplateIDUtils::get_template_definition('aal-buttoninner-notificationpreviewlink'));
define ('GD_AAL_TEMPLATE_BUTTONINNER_USERVIEW', PoP_TemplateIDUtils::get_template_definition('aal-buttoninner-userview'));
define ('GD_AAL_TEMPLATE_BUTTONINNER_NOTIFICATION_MARKASREAD', PoP_TemplateIDUtils::get_template_definition('aal-buttoninner-notification-markasread'));
define ('GD_AAL_TEMPLATE_BUTTONINNER_NOTIFICATION_MARKASUNREAD', PoP_TemplateIDUtils::get_template_definition('aal-buttoninner-notification-markasunread'));

class AAL_PoPProcessors_Template_Processor_ButtonInners extends GD_Template_Processor_ButtonInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_AAL_TEMPLATE_BUTTONINNER_NOTIFICATIONPREVIEWLINK,
			GD_AAL_TEMPLATE_BUTTONINNER_USERVIEW,
			GD_AAL_TEMPLATE_BUTTONINNER_NOTIFICATION_MARKASREAD,
			GD_AAL_TEMPLATE_BUTTONINNER_NOTIFICATION_MARKASUNREAD,
		);
	}

	function get_fontawesome($template_id, $atts) {
		
		switch ($template_id) {

			case GD_AAL_TEMPLATE_BUTTONINNER_USERVIEW:

				return 'fa-fw fa-eye';

			case GD_AAL_TEMPLATE_BUTTONINNER_NOTIFICATION_MARKASREAD:

				return 'fa-fw fa-circle-o';

			case GD_AAL_TEMPLATE_BUTTONINNER_NOTIFICATION_MARKASUNREAD:

				return 'fa-fw fa-circle';
		}

		return parent::get_fontawesome($template_id, $atts);
	}

	function get_btn_title($template_id) {
		
		switch ($template_id) {

			case GD_AAL_TEMPLATE_BUTTONINNER_USERVIEW:

				return __('View', 'aal-popprocessors');
		}

		return parent::get_btn_title($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new AAL_PoPProcessors_Template_Processor_ButtonInners();