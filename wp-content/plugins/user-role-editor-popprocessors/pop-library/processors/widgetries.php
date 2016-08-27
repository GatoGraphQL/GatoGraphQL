<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_WIDGET_COMMUNITIES', PoP_ServerUtils::get_template_definition('ure-widget-communities'));
define ('GD_URE_TEMPLATE_WIDGETCOMPACT_COMMUNITIES', PoP_ServerUtils::get_template_definition('ure-widgetcompact-communities'));

class GD_URE_Template_Processor_Widgets extends GD_Template_Processor_WidgetsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_WIDGET_COMMUNITIES,
			GD_URE_TEMPLATE_WIDGETCOMPACT_COMMUNITIES,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_URE_TEMPLATE_WIDGET_COMMUNITIES:
			case GD_URE_TEMPLATE_WIDGETCOMPACT_COMMUNITIES:

				$ret[] = GD_URE_TEMPLATE_LAYOUT_COMMUNITIES;
				break;
		}

		return $ret;
	}

	function get_menu_title($template_id, $atts) {

		$titles = array(
			GD_URE_TEMPLATE_WIDGET_COMMUNITIES => __('Member of', 'ure-popprocessors'),
			GD_URE_TEMPLATE_WIDGETCOMPACT_COMMUNITIES => __('Member of', 'ure-popprocessors'),
		);

		return $titles[$template_id];
	}
	function get_fontawesome($template_id, $atts) {

		$fontawesomes = array(
			GD_URE_TEMPLATE_WIDGET_COMMUNITIES => 'fa-users',
			GD_URE_TEMPLATE_WIDGETCOMPACT_COMMUNITIES => 'fa-users',

		);

		return $fontawesomes[$template_id];
	}
	function get_body_class($template_id, $atts) {

		switch ($template_id) {

			case GD_URE_TEMPLATE_WIDGET_COMMUNITIES:

				return 'list-group';
				
			case GD_URE_TEMPLATE_WIDGETCOMPACT_COMMUNITIES:

				return 'list-group list-group-sm';
		}

		return parent::get_body_class($template_id, $atts);
	}
	function get_item_wrapper($template_id, $atts) {

		switch ($template_id) {

			case GD_URE_TEMPLATE_WIDGET_COMMUNITIES:
			case GD_URE_TEMPLATE_WIDGETCOMPACT_COMMUNITIES:

				return 'list-group-item';
		}

		return parent::get_item_wrapper($template_id, $atts);
	}
	function get_widget_class($template_id, $atts) {

		switch ($template_id) {

			case GD_URE_TEMPLATE_WIDGETCOMPACT_COMMUNITIES:

				// return 'panel panel-info panel-sm';
				return 'panel panel-default panel-sm';
		}

		return parent::get_widget_class($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_Widgets();