<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWNOTIFICATION_DETAILS', PoP_TemplateIDUtils::get_template_definition('layout-automatedemails-previewnotification-details'));
define ('GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWNOTIFICATION_LIST', PoP_TemplateIDUtils::get_template_definition('layout-automatedemails-previewnotification-list'));

class GD_Template_Processor_AutomatedEmailsPreviewNotificationLayouts extends GD_Template_Processor_AutomatedEmailsPreviewNotificationLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWNOTIFICATION_DETAILS,
			GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWNOTIFICATION_LIST,
		);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_AutomatedEmailsPreviewNotificationLayouts();