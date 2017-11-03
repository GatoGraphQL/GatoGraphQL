<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_DETAILS', PoP_TemplateIDUtils::get_template_definition('layout-previewnotification-details'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_LIST', PoP_TemplateIDUtils::get_template_definition('layout-previewnotification-list'));

class GD_Template_Processor_PreviewNotificationLayouts extends GD_Template_Processor_PreviewNotificationLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_DETAILS,
			GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_LIST,
		);
	}	
	
	function get_user_avatar_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_DETAILS:

				return GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR60;
		}

		return parent::get_user_avatar_template($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_PreviewNotificationLayouts();