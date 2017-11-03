<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_WIDGET_MENU_ABOUT', PoP_TemplateIDUtils::get_template_definition('widget-menu-about'));

class GD_Custom_Template_Processor_MenuWidgets extends GD_Template_Processor_WidgetsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_WIDGET_MENU_ABOUT,		
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_MENU_ABOUT:

				$ret[] = GD_TEMPLATE_LAYOUT_MENU_INDENT;
				break;
		}

		return $ret;
	}

	function get_menu_title($template_id, $atts) {

		$menu = __('Section links', 'poptheme-wassup');

		$titles = array(
			GD_TEMPLATE_WIDGET_MENU_ABOUT => $menu,
		);

		return $titles[$template_id];
	}
	function get_fontawesome($template_id, $atts) {

		$menu = 'fa-sitemap';

		$fontawesomes = array(
			GD_TEMPLATE_WIDGET_MENU_ABOUT => $menu,
		);

		return $fontawesomes[$template_id];
	}

	function get_body_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_MENU_ABOUT:

				return 'panel-body';
		}

		return parent::get_body_class($template_id, $atts);
	}
	function get_item_wrapper($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_MENU_ABOUT:

				return '';
		}

		return parent::get_item_wrapper($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_MenuWidgets();