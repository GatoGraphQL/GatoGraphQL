<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// define ('GD_TEMPLATE_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS', PoP_TemplateIDUtils::get_template_definition('widgetcompact-automatedemails-post-authors'));
define ('GD_TEMPLATE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO', PoP_TemplateIDUtils::get_template_definition('em-widgetcompact-automatedemails-eventinfo'));

class PoP_ThemeWassup_EM_AE_Template_Processor_Widgets extends GD_Template_Processor_WidgetsBase {

	function get_templates_to_process() {
	
		return array(
			// GD_TEMPLATE_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS,
			GD_TEMPLATE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			// case GD_TEMPLATE_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS:

			// 	$ret[] = GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTAUTHORS;
			// 	break;
			
			case GD_TEMPLATE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO:

				// if (PoPTheme_Wassup_Utils::add_categories()) {
				// 	$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_CATEGORIES;
				// }
				// if (PoPTheme_Wassup_Utils::add_appliesto()) {
				// 	$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_APPLIESTO;
				// }
				$ret[] = GD_TEMPLATE_EM_LAYOUT_DATETIME;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS;
				$ret[] = GD_TEMPLATE_QUICKLINKGROUP_EVENTBOTTOM;
				break;
		}
		
		return $ret;
	}

	function get_menu_title($template_id, $atts) {

		$titles = array(
			// GD_TEMPLATE_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS => __('Author(s)', 'pop-coreprocessors'),
			GD_TEMPLATE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO => __('Event', 'poptheme-wassup'),
		);

		return $titles[$template_id];
	}
	function get_fontawesome($template_id, $atts) {

		$fontawesomes = array(
			// GD_TEMPLATE_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS => 'fa-user',
			GD_TEMPLATE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO => 'fa-calendar',
		);

		return $fontawesomes[$template_id];
	}
	function get_body_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO:

				return 'list-group list-group-sm';
		}

		return parent::get_body_class($template_id, $atts);
	}
	function get_item_wrapper($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO:

				return 'pop-hide-empty list-group-item';
		}

		return parent::get_item_wrapper($template_id, $atts);
	}
	function get_widget_class($template_id, $atts) {

		switch ($template_id) {

			// case GD_TEMPLATE_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS:
			case GD_TEMPLATE_EM_WIDGETCOMPACT_AUTOMATEDEMAILS_EVENTINFO:

				return 'panel panel-default panel-sm';
		}

		return parent::get_widget_class($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ThemeWassup_EM_AE_Template_Processor_Widgets();