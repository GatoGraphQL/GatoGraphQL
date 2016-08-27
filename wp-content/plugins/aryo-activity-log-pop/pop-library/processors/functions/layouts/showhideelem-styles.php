<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_SHOWELEMSTYLES', PoP_ServerUtils::get_template_definition('layout-marknotificationasread-showelemstyles'));
define ('GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_HIDEELEMSTYLES', PoP_ServerUtils::get_template_definition('layout-marknotificationasread-hideelemstyles'));
define ('GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWELEMSTYLES', PoP_ServerUtils::get_template_definition('layout-marknotificationasunread-showelemstyles'));
define ('GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_HIDEELEMSTYLES', PoP_ServerUtils::get_template_definition('layout-marknotificationasunread-hideelemstyles'));

class GD_AAL_Template_Processor_ShowHideElemStyleLayouts extends GD_Template_Processor_StylesLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_SHOWELEMSTYLES,
			GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_HIDEELEMSTYLES,
			GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWELEMSTYLES,
			GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_HIDEELEMSTYLES,
		);
	}

	function get_elem_target($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_SHOWELEMSTYLES:
			case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_HIDEELEMSTYLES:

				return '.preview.notification-layout .pop-functional.'.AAL_CLASS_NOTIFICATION_MARKASREAD;

			case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWELEMSTYLES:
			case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_HIDEELEMSTYLES:

				return '.preview.notification-layout .pop-functional.'.AAL_CLASS_NOTIFICATION_MARKASUNREAD;
		}

		return parent::get_elem_target($template_id, $atts);
	}
	
	function get_elem_styles($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_SHOWELEMSTYLES:
			case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWELEMSTYLES:

				return array(
					'display' => 'block'
				);

			case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_HIDEELEMSTYLES:
			case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_HIDEELEMSTYLES:

				return array(
					'display' => 'none'
				);
		}

		return parent::get_elem_styles($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_AAL_Template_Processor_ShowHideElemStyleLayouts();