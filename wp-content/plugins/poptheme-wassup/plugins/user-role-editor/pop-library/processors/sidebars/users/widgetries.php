<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_WIDGETCOMPACT_ORGANIZATIONINFO', PoP_TemplateIDUtils::get_template_definition('widgetcompact-organization-info'));
define ('GD_TEMPLATE_WIDGETCOMPACT_INDIVIDUALINFO', PoP_TemplateIDUtils::get_template_definition('widgetcompact-individual-info'));

class GD_URE_Custom_Template_Processor_UserWidgets extends GD_Template_Processor_WidgetsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_WIDGETCOMPACT_ORGANIZATIONINFO,
			GD_TEMPLATE_WIDGETCOMPACT_INDIVIDUALINFO,	
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_WIDGETCOMPACT_ORGANIZATIONINFO:

				$ret[] = GD_URE_TEMPLATE_LAYOUTWRAPPER_PROFILEORGANIZATION_DETAILS;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_USERSIDEBARLOCATIONS;
				$ret[] = GD_TEMPLATE_QUICKLINKGROUP_USERCOMPACT;
				break;

			case GD_TEMPLATE_WIDGETCOMPACT_INDIVIDUALINFO:

				$ret[] = GD_URE_TEMPLATE_LAYOUTWRAPPER_PROFILEINDIVIDUAL_DETAILS;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_USERSIDEBARLOCATIONS;
				$ret[] = GD_TEMPLATE_QUICKLINKGROUP_USERCOMPACT;
				break;
		}

		return $ret;
	}

	function get_menu_title($template_id, $atts) {

		$titles = array(
			GD_TEMPLATE_WIDGETCOMPACT_ORGANIZATIONINFO => __('Organization', 'poptheme-wassup'),
			GD_TEMPLATE_WIDGETCOMPACT_INDIVIDUALINFO => __('Individual', 'poptheme-wassup'),
		);

		return $titles[$template_id];
	}
	function get_fontawesome($template_id, $atts) {

		$fontawesomes = array(
			GD_TEMPLATE_WIDGETCOMPACT_ORGANIZATIONINFO => gd_navigation_menu_item(POP_URE_POPPROCESSORS_PAGE_ORGANIZATIONS, false),
			GD_TEMPLATE_WIDGETCOMPACT_INDIVIDUALINFO => gd_navigation_menu_item(POP_URE_POPPROCESSORS_PAGE_INDIVIDUALS, false),
		);

		return $fontawesomes[$template_id];
	}

	function get_body_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGETCOMPACT_ORGANIZATIONINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_INDIVIDUALINFO:

				return 'list-group list-group-sm';
		}

		return parent::get_body_class($template_id, $atts);
	}
	function get_item_wrapper($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGETCOMPACT_ORGANIZATIONINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_INDIVIDUALINFO:

				return 'pop-hide-empty list-group-item';
		}

		return parent::get_item_wrapper($template_id, $atts);
	}
	function get_widget_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGETCOMPACT_ORGANIZATIONINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_INDIVIDUALINFO:

				// return 'panel panel-info panel-sm';
				return 'panel panel-default panel-sm';
		}

		return parent::get_widget_class($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Custom_Template_Processor_UserWidgets();