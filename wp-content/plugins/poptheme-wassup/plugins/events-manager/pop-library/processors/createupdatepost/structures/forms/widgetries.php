<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_WIDGET_FORM_EVENTDETAILS', PoP_TemplateIDUtils::get_template_definition('widget-form-event-details'));
define ('GD_TEMPLATE_WIDGET_FORM_EVENTLINKDETAILS', PoP_TemplateIDUtils::get_template_definition('widget-form-eventlink-details'));

class GD_EM_Custom_Template_Processor_FormWidgets extends GD_Template_Processor_WidgetsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_WIDGET_FORM_EVENTDETAILS,		
			GD_TEMPLATE_WIDGET_FORM_EVENTLINKDETAILS,		
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_FORM_EVENTDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_EVENTLINKDETAILS:

				if (PoPTheme_Wassup_Utils::add_categories()) {
					$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_CATEGORIES;
				}
				if (PoPTheme_Wassup_Utils::add_appliesto()) {
					$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_APPLIESTO;					
				}
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_DATERANGETIMEPICKER;
				$ret[] = GD_EM_TEMPLATE_FORMCOMPONENTGROUP_TYPEAHEADMAP;

				// Only if the Volunteering is enabled
				if (defined('POP_GENERICFORMS_PAGE_VOLUNTEER')) {
					$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_VOLUNTEERSNEEDED_SELECT;
				}

				// Comment Leo 16/01/2016: There's no need to ask for the LinkAccess since we don't show it anyway
				// if ($template_id == GD_TEMPLATE_WIDGET_FORM_EVENTLINKDETAILS) {
				// 	$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_LINKACCESS;
				// }
				break;
		}

		return $ret;
	}

	function get_menu_title($template_id, $atts) {

		$titles = array(
			GD_TEMPLATE_WIDGET_FORM_EVENTDETAILS => __('Event details', 'poptheme-wassup'),	
			GD_TEMPLATE_WIDGET_FORM_EVENTLINKDETAILS => __('Event link details', 'poptheme-wassup'),	
		);

		return $titles[$template_id];
	}

	function get_widget_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_FORM_EVENTDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_EVENTLINKDETAILS:

				if ($class = $this->get_general_att($atts, 'form-widget-class')) {
					return $class;
				}

				return 'panel panel-info';
		}

		return parent::get_widget_class($template_id, $atts);
	}

	function get_body_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_FORM_EVENTDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_EVENTLINKDETAILS:

				return 'panel-body';
		}

		return parent::get_body_class($template_id, $atts);
	}
	function get_item_wrapper($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_FORM_EVENTDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_EVENTLINKDETAILS:

				return '';
		}

		return parent::get_item_wrapper($template_id, $atts);
	}

	function is_collapsible_open($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_FORM_EVENTDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_EVENTLINKDETAILS:

				// Have the widgets open in the Addons
				if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {
					return true;
				}
				break;
		}

		return parent::is_collapsible_open($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_FORM_EVENTDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_EVENTLINKDETAILS:

				// Typeahead map: make it small
				$this->add_att(GD_EM_TEMPLATE_FORMCOMPONENT_TYPEAHEADMAP, $atts, 'wrapper-class', '');
				$this->add_att(GD_EM_TEMPLATE_FORMCOMPONENT_TYPEAHEADMAP, $atts, 'map-class', 'spacing-bottom-md');
				$this->add_att(GD_EM_TEMPLATE_FORMCOMPONENT_TYPEAHEADMAP, $atts, 'typeahead-class', '');
				$this->add_att(GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS, $atts, 'alert-class', 'alert-sm alert-info fade');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Custom_Template_Processor_FormWidgets();