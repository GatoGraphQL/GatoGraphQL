<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('AAL_POPPROCESSORS_TEMPLATE_ANCHORCONTROL_NOTIFICATIONS', PoP_TemplateIDUtils::get_template_definition('aal-popprocessors-buttoncontrol-notifications'));
define ('AAL_POPPROCESSORS_TEMPLATE_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD', PoP_TemplateIDUtils::get_template_definition('aal-popprocessors-buttoncontrol-notifications-markallasread'));

class AAL_PoPProcessors_Template_Processor_AnchorControls extends GD_Template_Processor_AnchorControlsBase {

	function get_templates_to_process() {
	
		return array(
			AAL_POPPROCESSORS_TEMPLATE_ANCHORCONTROL_NOTIFICATIONS,
			AAL_POPPROCESSORS_TEMPLATE_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD,
		);
	}

	function get_label($template_id, $atts) {

		switch ($template_id) {

			case AAL_POPPROCESSORS_TEMPLATE_ANCHORCONTROL_NOTIFICATIONS:

				return __('View all', 'aal-popprocessors');

			case AAL_POPPROCESSORS_TEMPLATE_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD:

				return __('Mark all as read', 'aal-popprocessors');
		}

		return parent::get_label($template_id, $atts);
	}
	function get_fontawesome($template_id, $atts) {

		switch ($template_id) {

			case AAL_POPPROCESSORS_TEMPLATE_ANCHORCONTROL_NOTIFICATIONS:

				return gd_navigation_menu_item(POP_AAL_PAGE_NOTIFICATIONS, false);

			case AAL_POPPROCESSORS_TEMPLATE_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD:

				return gd_navigation_menu_item(POP_AAL_PAGE_NOTIFICATIONS_MARKALLASREAD, false);
		}

		return parent::get_fontawesome($template_id, $atts);
	}
	function get_href($template_id, $atts) {

		switch ($template_id) {

			case AAL_POPPROCESSORS_TEMPLATE_ANCHORCONTROL_NOTIFICATIONS:
			case AAL_POPPROCESSORS_TEMPLATE_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD:

				$pages = array(
					AAL_POPPROCESSORS_TEMPLATE_ANCHORCONTROL_NOTIFICATIONS => POP_AAL_PAGE_NOTIFICATIONS,
					AAL_POPPROCESSORS_TEMPLATE_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD => POP_AAL_PAGE_NOTIFICATIONS_MARKALLASREAD,
				);
				$page = $pages[$template_id];
				
				return get_permalink($page);
		}

		return parent::get_href($template_id, $atts);
	}
	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case AAL_POPPROCESSORS_TEMPLATE_ANCHORCONTROL_NOTIFICATIONS:
			case AAL_POPPROCESSORS_TEMPLATE_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD:

				$this->append_att($template_id, $atts, 'class', 'btn btn-link btn-compact');
				break;
		}

		switch ($template_id) {
				
			case AAL_POPPROCESSORS_TEMPLATE_ANCHORCONTROL_NOTIFICATIONS_MARKALLASREAD:

				// Only if the user is logged in on any one domain
				$this->append_att($template_id, $atts, 'class', 'visible-loggedin-anydomain');	
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new AAL_PoPProcessors_Template_Processor_AnchorControls();