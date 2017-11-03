<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_JOINEDCOMMUNITY_DETAILS', PoP_TemplateIDUtils::get_template_definition('layout-previewnotification-joinedcommunity-details'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_JOINEDCOMMUNITY_LIST', PoP_TemplateIDUtils::get_template_definition('layout-previewnotification-joinedcommunity-list'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_UPDATEDUSERMEMBERSHIP_DETAILS', PoP_TemplateIDUtils::get_template_definition('layout-previewnotification-updatedusermembership-details'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_UPDATEDUSERMEMBERSHIP_LIST', PoP_TemplateIDUtils::get_template_definition('layout-previewnotification-updatedusermembership-list'));

class GD_Template_Processor_CustomPreviewNotificationLayouts extends GD_Template_Processor_PreviewNotificationLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_JOINEDCOMMUNITY_DETAILS,
			GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_JOINEDCOMMUNITY_LIST,
			GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_UPDATEDUSERMEMBERSHIP_DETAILS,
			GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_UPDATEDUSERMEMBERSHIP_LIST,
		);
	}	

	function get_bottom_layouts($template_id) {

		$ret = parent::get_bottom_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_UPDATEDUSERMEMBERSHIP_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_UPDATEDUSERMEMBERSHIP_LIST:

				array_unshift($ret, GD_URE_AAL_TEMPLATE_MULTICOMPONENT_LAYOUTUSER_MEMBERSHIP);
		}

		return $ret;
	}

	function get_quicklinkgroup_bottom($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_JOINEDCOMMUNITY_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_JOINEDCOMMUNITY_LIST:

				return GD_URE_AAL_TEMPLATE_QUICKLINKGROUP_USER_JOINEDCOMMUNITY;
		}

		return parent::get_quicklinkgroup_bottom($template_id);
	}
	function get_user_avatar_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_JOINEDCOMMUNITY_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_UPDATEDUSERMEMBERSHIP_DETAILS:

				return GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR60;
		}

		return parent::get_user_avatar_template($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_UPDATEDUSERMEMBERSHIP_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION_UPDATEDUSERMEMBERSHIP_LIST:

				$this->append_att($template_id, $atts, 'class', 'pop-usermembership');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomPreviewNotificationLayouts();