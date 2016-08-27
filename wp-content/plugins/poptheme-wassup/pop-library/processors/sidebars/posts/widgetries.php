<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_WIDGET_LINK_ACCESS', PoP_ServerUtils::get_template_definition('widget-link-access'));
define ('GD_TEMPLATE_WIDGET_LINK_CATEGORIES', PoP_ServerUtils::get_template_definition('widget-link-categories'));
define ('GD_TEMPLATE_WIDGET_CATEGORIES', PoP_ServerUtils::get_template_definition('widget-categories'));
define ('GD_TEMPLATE_WIDGET_APPLIESTO', PoP_ServerUtils::get_template_definition('widget-appliesto'));

define ('GD_TEMPLATE_WIDGETCOMPACT_GENERICINFO', PoP_ServerUtils::get_template_definition('widgetcompact-generic-info'));
define ('GD_TEMPLATE_WIDGETCOMPACT_LINKINFO', PoP_ServerUtils::get_template_definition('widgetcompact-link-info'));
define ('GD_TEMPLATE_WIDGETCOMPACT_HIGHLIGHTINFO', PoP_ServerUtils::get_template_definition('widgetcompact-highlight-info'));
define ('GD_TEMPLATE_WIDGETCOMPACT_WEBPOSTINFO', PoP_ServerUtils::get_template_definition('widgetcompact-webpost-info'));

class GD_Template_Processor_CustomPostWidgets extends GD_Template_Processor_WidgetsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_WIDGET_LINK_ACCESS,
			GD_TEMPLATE_WIDGET_LINK_CATEGORIES,
			GD_TEMPLATE_WIDGET_CATEGORIES,
			GD_TEMPLATE_WIDGET_APPLIESTO,
			GD_TEMPLATE_WIDGETCOMPACT_GENERICINFO,	
			GD_TEMPLATE_WIDGETCOMPACT_LINKINFO,	
			GD_TEMPLATE_WIDGETCOMPACT_HIGHLIGHTINFO,	
			GD_TEMPLATE_WIDGETCOMPACT_WEBPOSTINFO,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_LINK_ACCESS:

				$ret[] = GD_TEMPLATE_LAYOUT_LINK_ACCESS;
				break;

			case GD_TEMPLATE_WIDGET_LINK_CATEGORIES:

				$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_LINK_CATEGORIES;
				break;

			case GD_TEMPLATE_WIDGET_CATEGORIES:

				$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_CATEGORIES;
				break;

			case GD_TEMPLATE_WIDGET_APPLIESTO:

				$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_APPLIESTO;
				break;

			case GD_TEMPLATE_WIDGETCOMPACT_GENERICINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_HIGHLIGHTINFO:

				$ret[] = GD_TEMPLATE_LAYOUT_WIDGETPUBLISHED;
				break;
				
			case GD_TEMPLATE_WIDGETCOMPACT_WEBPOSTINFO:

				$ret[] = GD_TEMPLATE_LAYOUT_WIDGETPUBLISHED;
				if (PoPTheme_Wassup_Utils::add_categories()) {
					$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_CATEGORIES;
				}
				if (PoPTheme_Wassup_Utils::add_appliesto()) {
					$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_APPLIESTO;
				}
				break;

			case GD_TEMPLATE_WIDGET_LINKINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_LINKINFO:

				if (PoPTheme_Wassup_Utils::add_categories()) {
					$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_CATEGORIES;
				}
				$ret[] = GD_TEMPLATE_LAYOUT_LINK_ACCESS;
				$ret[] = GD_TEMPLATE_LAYOUT_WIDGETPUBLISHED;
				break;
		}

		return $ret;
	}

	function get_menu_title($template_id, $atts) {

		$titles = array(
			GD_TEMPLATE_WIDGET_LINK_ACCESS => __('Access type', 'poptheme-wassup'),
			GD_TEMPLATE_WIDGET_LINK_CATEGORIES => __('Categories', 'poptheme-wassup'),
			GD_TEMPLATE_WIDGET_CATEGORIES => __('Categories', 'poptheme-wassup'),
			GD_TEMPLATE_WIDGET_APPLIESTO => __('Applies to', 'poptheme-wassup'),
			GD_TEMPLATE_WIDGETCOMPACT_GENERICINFO => __('Post', 'poptheme-wassup'),
			GD_TEMPLATE_WIDGETCOMPACT_LINKINFO => __('Link', 'poptheme-wassup'),
			GD_TEMPLATE_WIDGETCOMPACT_HIGHLIGHTINFO => __('Highlight', 'poptheme-wassup'),
			GD_TEMPLATE_WIDGETCOMPACT_WEBPOSTINFO => __('Post', 'poptheme-wassup'),
		);

		return $titles[$template_id];
	}
	function get_fontawesome($template_id, $atts) {

		$fontawesomes = array(
			GD_TEMPLATE_WIDGET_LINK_ACCESS => 'fa-link',
			GD_TEMPLATE_WIDGET_LINK_CATEGORIES => 'fa-info-circle',
			GD_TEMPLATE_WIDGET_CATEGORIES => 'fa-info-circle',
			GD_TEMPLATE_WIDGET_APPLIESTO => 'fa-info-circle',
			GD_TEMPLATE_WIDGETCOMPACT_GENERICINFO => 'fa-info-circle',
			GD_TEMPLATE_WIDGETCOMPACT_LINKINFO => 'fa-link',
			GD_TEMPLATE_WIDGETCOMPACT_HIGHLIGHTINFO => 'fa-bullseye',
			// GD_TEMPLATE_WIDGETCOMPACT_WEBPOSTINFO => 'fa-flash',
			GD_TEMPLATE_WIDGETCOMPACT_WEBPOSTINFO => 'fa-circle',
		);

		return $fontawesomes[$template_id];
	}

	function get_body_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGETCOMPACT_GENERICINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_LINKINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_HIGHLIGHTINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_WEBPOSTINFO:

				return 'list-group list-group-sm';
		}

		return parent::get_body_class($template_id, $atts);
	}
	function get_item_wrapper($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGETCOMPACT_GENERICINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_LINKINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_HIGHLIGHTINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_WEBPOSTINFO:

				return 'pop-hide-empty list-group-item';
		}

		return parent::get_item_wrapper($template_id, $atts);
	}
	function get_widget_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGETCOMPACT_GENERICINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_LINKINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_HIGHLIGHTINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_WEBPOSTINFO:

				return 'panel panel-default panel-sm';
		}

		return parent::get_widget_class($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomPostWidgets();