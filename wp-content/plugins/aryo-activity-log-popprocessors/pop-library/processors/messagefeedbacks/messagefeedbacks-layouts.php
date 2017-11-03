<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_NOTIFICATIONS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-notifications'));

class GD_AAL_Template_Processor_CustomListMessageFeedbackLayouts extends GD_Template_Processor_ListMessageFeedbackLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_NOTIFICATIONS,
		);
	}

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_NOTIFICATIONS:

				$name = __('notification', 'aal-popprocessors');
				$names = __('notifications', 'aal-popprocessors');
				break;
		}

		$ret['noresults'] = sprintf(
			__('No %s found.', 'aal-popprocessors'),
			$names
		);
		$ret['nomore'] = sprintf(
			__('No more %s found.', 'aal-popprocessors'),
			$names
		);

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_AAL_Template_Processor_CustomListMessageFeedbackLayouts();