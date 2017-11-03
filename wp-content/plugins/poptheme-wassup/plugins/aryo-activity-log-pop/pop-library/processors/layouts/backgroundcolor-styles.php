<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_BGCOLORSTYLES', PoP_TemplateIDUtils::get_template_definition('layout-marknotificationasread-bgcolorstyles'));
define ('GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_BGCOLORSTYLES', PoP_TemplateIDUtils::get_template_definition('layout-marknotificationasunread-bgcolorstyles'));
define ('GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_TOPBGCOLORSTYLES', PoP_TemplateIDUtils::get_template_definition('layout-marknotificationasread-topbgcolorstyles'));
define ('GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_TOPBGCOLORSTYLES', PoP_TemplateIDUtils::get_template_definition('layout-marknotificationasunread-topbgcolorstyles'));

class PopThemeWassup_AAL_Template_Processor_BackgroundColorStyleLayouts extends GD_Template_Processor_StylesLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_BGCOLORSTYLES,
			GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_BGCOLORSTYLES,
			GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_TOPBGCOLORSTYLES,
			GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_TOPBGCOLORSTYLES,
		);
	}

	function get_elem_target($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_BGCOLORSTYLES:
			case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_BGCOLORSTYLES:

				return '.preview.notification-layout';

			case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_TOPBGCOLORSTYLES:
			case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_TOPBGCOLORSTYLES:

				return '#ps-top .preview.notification-layout';
		}

		return parent::get_elem_target($template_id, $atts);
	}
	
	function get_elem_styles($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_BGCOLORSTYLES:
			case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_BGCOLORSTYLES:
			case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_TOPBGCOLORSTYLES:
			case GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_TOPBGCOLORSTYLES:

				return array(
					'background-color' => apply_filters(
						'PopThemeWassup_AAL_Template_Processor_BackgroundColorStyleLayouts:bgcolor',
						'transparent',
						$template_id
					)
				);
		}

		return parent::get_elem_styles($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PopThemeWassup_AAL_Template_Processor_BackgroundColorStyleLayouts();