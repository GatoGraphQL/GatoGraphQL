<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS', PoP_ServerUtils::get_template_definition('widgetcompact-automatedemails-post-authors'));
define ('GD_TEMPLATE_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO', PoP_ServerUtils::get_template_definition('widgetcompact-automatedemails-postinfo'));

class PoPTheme_Wassup_AE_Template_Processor_Widgets extends GD_Template_Processor_WidgetsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS,
			GD_TEMPLATE_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS:

				$ret[] = GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTAUTHORS;
				break;
			
			case GD_TEMPLATE_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO:

				if (PoPTheme_Wassup_Utils::add_categories()) {
					$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_CATEGORIES;
				}
				if (PoPTheme_Wassup_Utils::add_appliesto()) {
					$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_APPLIESTO;
				}
				$ret[] = GD_TEMPLATE_LAYOUT_PUBLISHED;
				break;
		}
		
		return $ret;
	}

	function get_menu_title($template_id, $atts) {

		$titles = array(
			GD_TEMPLATE_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS => __('Author(s)', 'pop-coreprocessors'),
			GD_TEMPLATE_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO => __('Post', 'poptheme-wassup'),
		);

		return $titles[$template_id];
	}
	function get_fontawesome($template_id, $atts) {

		$fontawesomes = array(
			GD_TEMPLATE_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS => 'fa-user',
			GD_TEMPLATE_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO => 'fa-circle',
		);

		return $fontawesomes[$template_id];
	}
	function get_body_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO:

				return 'list-group list-group-sm';
		}

		return parent::get_body_class($template_id, $atts);
	}
	function get_item_wrapper($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO:

				return 'pop-hide-empty list-group-item';
		}

		return parent::get_item_wrapper($template_id, $atts);
	}
	function get_widget_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGETCOMPACT_AUTOMATEDEMAILS_POST_AUTHORS:
			case GD_TEMPLATE_WIDGETCOMPACT_AUTOMATEDEMAILS_POSTINFO:

				return 'panel panel-default panel-sm';
		}

		return parent::get_widget_class($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_AE_Template_Processor_Widgets();