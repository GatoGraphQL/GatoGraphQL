<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_EM_WIDGET_POSTLOCATIONSMAP', PoP_ServerUtils::get_template_definition('em-widget-postlocationsmap'));
define ('GD_TEMPLATE_EM_WIDGET_USERLOCATIONSMAP', PoP_ServerUtils::get_template_definition('em-widget-userlocationsmap'));
define ('GD_TEMPLATE_EM_WIDGET_DATETIMEDOWNLOADLINKS', PoP_ServerUtils::get_template_definition('em-widget-datetimedownloadlinks'));
define ('GD_TEMPLATE_EM_WIDGET_DATETIME', PoP_ServerUtils::get_template_definition('em-widget-datetime'));
define ('GD_TEMPLATE_EM_WIDGETCOMPACT_EVENTINFO', PoP_ServerUtils::get_template_definition('em-widgetcompact-eventinfo'));
define ('GD_TEMPLATE_EM_WIDGETCOMPACT_PASTEVENTINFO', PoP_ServerUtils::get_template_definition('em-widgetcompact-pasteventinfo'));

class GD_EM_Template_Processor_SidebarComponents extends GD_Template_Processor_WidgetsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_EM_WIDGET_POSTLOCATIONSMAP,
			GD_TEMPLATE_EM_WIDGET_USERLOCATIONSMAP,
			GD_TEMPLATE_EM_WIDGET_DATETIMEDOWNLOADLINKS,
			GD_TEMPLATE_EM_WIDGET_DATETIME,
			GD_TEMPLATE_EM_WIDGETCOMPACT_EVENTINFO,
			GD_TEMPLATE_EM_WIDGETCOMPACT_PASTEVENTINFO,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {
		
			case GD_TEMPLATE_EM_WIDGET_DATETIMEDOWNLOADLINKS:

				$ret[] = GD_TEMPLATE_EM_LAYOUT_DATETIMEDOWNLOADLINKS;
				break;

			case GD_TEMPLATE_EM_WIDGET_DATETIME:

				$ret[] = GD_TEMPLATE_EM_LAYOUT_DATETIME;
				break;
			
			case GD_TEMPLATE_EM_WIDGET_POSTLOCATIONSMAP:

				$ret[] = GD_TEMPLATE_EM_LAYOUTWRAPPER_POSTLOCATIONSMAP;
				break;
			
			case GD_TEMPLATE_EM_WIDGET_USERLOCATIONSMAP:

				$ret[] = GD_TEMPLATE_EM_LAYOUTWRAPPER_USERLOCATIONSMAP;
				break;
			
			case GD_TEMPLATE_EM_WIDGETCOMPACT_EVENTINFO:

				if (PoPTheme_Wassup_Utils::add_categories()) {
					$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_CATEGORIES;
				}
				if (PoPTheme_Wassup_Utils::add_appliesto()) {
					$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_APPLIESTO;
				}
				$ret[] = GD_TEMPLATE_EM_LAYOUT_DATETIMEDOWNLOADLINKS;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS;
				break;
			
			case GD_TEMPLATE_EM_WIDGETCOMPACT_PASTEVENTINFO:

				if (PoPTheme_Wassup_Utils::add_categories()) {
					$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_CATEGORIES;
				}
				if (PoPTheme_Wassup_Utils::add_appliesto()) {
					$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_APPLIESTO;
				}
				$ret[] = GD_TEMPLATE_EM_LAYOUT_DATETIMEDOWNLOADLINKS;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS;
				break;
		}
		
		return $ret;
	}

	// function get_layouts($template_id) {

	// 	$ret = parent::get_layouts($template_id);

	// 	$sidebarcomponent_inners = array(
	// 		GD_TEMPLATE_EM_WIDGET_POSTLOCATIONSMAP => GD_TEMPLATE_EM_LAYOUTWRAPPER_POSTLOCATIONSMAP,
	// 		GD_TEMPLATE_EM_WIDGET_USERLOCATIONSMAP => GD_TEMPLATE_EM_LAYOUTWRAPPER_USERLOCATIONSMAP,
	// 		GD_TEMPLATE_EM_WIDGET_DATETIMEDOWNLOADLINKS => GD_TEMPLATE_EM_SIDEBARCOMPONENTINNER_DATETIMEDOWNLOADLINKS,
	// 		GD_TEMPLATE_EM_WIDGET_DATETIME => GD_TEMPLATE_EM_SIDEBARCOMPONENTINNER_DATETIME,
	// 		GD_TEMPLATE_EM_WIDGETCOMPACT_EVENTINFO => GD_TEMPLATE_EM_SIDEBARCOMPONENTINNER_EVENTINFO,
	// 	);

	// 	if ($sidebarcomponent_inner = $sidebarcomponent_inners[$template_id]) {

	// 		$ret[] = $sidebarcomponent_inner;
	// 	}
		
	// 	return $ret;
	// }

	function get_menu_title($template_id, $atts) {

		$titles = array(
			GD_TEMPLATE_EM_WIDGET_POSTLOCATIONSMAP => __('Location(s)', 'poptheme-wassup'),
			GD_TEMPLATE_EM_WIDGET_USERLOCATIONSMAP => __('Location(s)', 'poptheme-wassup'),
			GD_TEMPLATE_EM_WIDGET_DATETIMEDOWNLOADLINKS => __('Date/Time', 'poptheme-wassup'),
			GD_TEMPLATE_EM_WIDGET_DATETIME => __('Date/Time', 'poptheme-wassup'),
			GD_TEMPLATE_EM_WIDGETCOMPACT_EVENTINFO => __('Event', 'poptheme-wassup'),
			GD_TEMPLATE_EM_WIDGETCOMPACT_PASTEVENTINFO => __('Past Event', 'poptheme-wassup'),
		);

		return $titles[$template_id];
	}
	function get_fontawesome($template_id, $atts) {

		$fontawesomes = array(
			GD_TEMPLATE_EM_WIDGET_POSTLOCATIONSMAP => 'fa-map-marker',
			GD_TEMPLATE_EM_WIDGET_USERLOCATIONSMAP => 'fa-map-marker',
			GD_TEMPLATE_EM_WIDGET_DATETIMEDOWNLOADLINKS => 'fa-calendar',
			GD_TEMPLATE_EM_WIDGET_DATETIME => 'fa-calendar',
			GD_TEMPLATE_EM_WIDGETCOMPACT_EVENTINFO => 'fa-calendar',
			GD_TEMPLATE_EM_WIDGETCOMPACT_PASTEVENTINFO => 'fa-calendar',
		);

		return $fontawesomes[$template_id];
	}
	function get_body_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_EM_WIDGET_POSTLOCATIONSMAP:
			case GD_TEMPLATE_EM_WIDGET_USERLOCATIONSMAP:
			case GD_TEMPLATE_EM_WIDGET_DATETIMEDOWNLOADLINKS:
			case GD_TEMPLATE_EM_WIDGET_DATETIME:

				return 'list-group';

			case GD_TEMPLATE_EM_WIDGETCOMPACT_EVENTINFO:
			case GD_TEMPLATE_EM_WIDGETCOMPACT_PASTEVENTINFO:

				return 'list-group list-group-sm';
		}

		return parent::get_body_class($template_id, $atts);
	}
	function get_item_wrapper($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_EM_WIDGET_POSTLOCATIONSMAP:
			case GD_TEMPLATE_EM_WIDGET_USERLOCATIONSMAP:
			case GD_TEMPLATE_EM_WIDGET_DATETIMEDOWNLOADLINKS:
			case GD_TEMPLATE_EM_WIDGET_DATETIME:

				return 'list-group-item';

			case GD_TEMPLATE_EM_WIDGETCOMPACT_EVENTINFO:
			case GD_TEMPLATE_EM_WIDGETCOMPACT_PASTEVENTINFO:

				return 'pop-hide-empty list-group-item';
		}

		return parent::get_item_wrapper($template_id, $atts);
	}
	function get_widget_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_EM_WIDGETCOMPACT_EVENTINFO:
			case GD_TEMPLATE_EM_WIDGETCOMPACT_PASTEVENTINFO:

				// return 'panel panel-info panel-sm';
				return 'panel panel-default panel-sm';
		}

		return parent::get_widget_class($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_SidebarComponents();