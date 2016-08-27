<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_WIDGET_FARM_CATEGORIES', PoP_ServerUtils::get_template_definition('widget-farm-categories'));
define ('GD_TEMPLATE_WIDGETCOMPACT_FARMINFO', PoP_ServerUtils::get_template_definition('widgetcompact-farm-info'));

class OP_Template_Processor_PostWidgets extends GD_Template_Processor_WidgetsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_WIDGET_FARM_CATEGORIES,
			GD_TEMPLATE_WIDGETCOMPACT_FARMINFO,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_FARM_CATEGORIES:

				$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_FARM_CATEGORIES;
				break;

			case GD_TEMPLATE_WIDGETCOMPACT_FARMINFO:

				if (PoPTheme_Wassup_Utils::add_categories()) {
					$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_CATEGORIES;
				}
				if (PoPTheme_Wassup_Utils::add_appliesto()) {
					$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_APPLIESTO;
				}
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS;
				break;
		}

		return $ret;
	}

	function get_menu_title($template_id, $atts) {

		$categories = __('Categories', 'poptheme-wassup-organikprocessors');

		$titles = array(
			GD_TEMPLATE_WIDGET_FARM_CATEGORIES => $categories,
			GD_TEMPLATE_WIDGETCOMPACT_FARMINFO => __('Farm', 'poptheme-wassup-organikprocessors'),
		);

		return $titles[$template_id];
	}
	function get_fontawesome($template_id, $atts) {

		$categories = 'fa-info-circle';
		$fontawesomes = array(
			GD_TEMPLATE_WIDGET_FARM_CATEGORIES => $categories,
			GD_TEMPLATE_WIDGETCOMPACT_FARMINFO => gd_navigation_menu_item(POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS, false),
		);

		return $fontawesomes[$template_id];
	}

	function get_body_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGETCOMPACT_FARMINFO:

				return 'list-group list-group-sm';
		}

		return parent::get_body_class($template_id, $atts);
	}
	function get_item_wrapper($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGETCOMPACT_FARMINFO:

				return 'pop-hide-empty list-group-item';
		}

		return parent::get_item_wrapper($template_id, $atts);
	}
	function get_widget_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGETCOMPACT_FARMINFO:

				return 'panel panel-default panel-sm';
		}

		return parent::get_widget_class($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_PostWidgets();