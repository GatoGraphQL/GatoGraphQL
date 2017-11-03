<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_WIDGETCOMPACT_GENERICINFO', PoP_TemplateIDUtils::get_template_definition('widgetcompact-genericinfo'));

class GD_Custom_Template_Processor_UserWidgets extends GD_Template_Processor_WidgetsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_WIDGETCOMPACT_GENERICINFO,	
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_WIDGETCOMPACT_GENERICINFO:

				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_USERSIDEBARLOCATIONS;
				$ret[] = GD_TEMPLATE_QUICKLINKGROUP_USERCOMPACT;
				break;
		}

		return $ret;
	}

	function get_menu_title($template_id, $atts) {

		$titles = array(
			GD_TEMPLATE_WIDGETCOMPACT_GENERICINFO => __('User', 'poptheme-wassup'),
		);

		return $titles[$template_id];
	}
	function get_fontawesome($template_id, $atts) {

		$fontawesomes = array(
			GD_TEMPLATE_WIDGETCOMPACT_GENERICINFO => gd_navigation_menu_item(POP_WPAPI_PAGE_ALLUSERS, false),
		);

		return $fontawesomes[$template_id];
	}

	function get_body_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGETCOMPACT_GENERICINFO:

				return 'list-group list-group-sm';
		}

		return parent::get_body_class($template_id, $atts);
	}
	function get_item_wrapper($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGETCOMPACT_GENERICINFO:

				return 'pop-hide-empty list-group-item';
		}

		return parent::get_item_wrapper($template_id, $atts);
	}
	function get_widget_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGETCOMPACT_GENERICINFO:

				// return 'panel panel-info panel-sm';
				return 'panel panel-default panel-sm';
		}

		return parent::get_widget_class($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_UserWidgets();