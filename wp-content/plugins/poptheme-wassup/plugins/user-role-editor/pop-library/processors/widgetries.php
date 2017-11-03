<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_WIDGET_PROFILEORGANIZATION_DETAILS', PoP_TemplateIDUtils::get_template_definition('ure-widget-profileorganization-details'));
define ('GD_URE_TEMPLATE_WIDGET_PROFILEINDIVIDUAL_DETAILS', PoP_TemplateIDUtils::get_template_definition('ure-widget-profileindividual-details'));

class GD_URE_Custom_Template_Processor_Widgets extends GD_Template_Processor_WidgetsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_WIDGET_PROFILEORGANIZATION_DETAILS,
			GD_URE_TEMPLATE_WIDGET_PROFILEINDIVIDUAL_DETAILS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_URE_TEMPLATE_WIDGET_PROFILEINDIVIDUAL_DETAILS:

				$ret[] = GD_URE_TEMPLATE_LAYOUTWRAPPER_PROFILEINDIVIDUAL_DETAILS;
				break;

			case GD_URE_TEMPLATE_WIDGET_PROFILEORGANIZATION_DETAILS:

				$ret[] = GD_URE_TEMPLATE_LAYOUTWRAPPER_PROFILEORGANIZATION_DETAILS;
				break;
		}

		return $ret;
	}

	function get_menu_title($template_id, $atts) {

		$titles = array(
			GD_URE_TEMPLATE_WIDGET_PROFILEORGANIZATION_DETAILS => __('Details', 'poptheme-wassup'),
			GD_URE_TEMPLATE_WIDGET_PROFILEINDIVIDUAL_DETAILS => __('Details', 'poptheme-wassup'),
		);

		return $titles[$template_id];
	}
	function get_fontawesome($template_id, $atts) {

		$fontawesomes = array(
			GD_URE_TEMPLATE_WIDGET_PROFILEORGANIZATION_DETAILS => 'fa-info-circle',
			GD_URE_TEMPLATE_WIDGET_PROFILEINDIVIDUAL_DETAILS => 'fa-info-circle',

		);

		return $fontawesomes[$template_id];
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Custom_Template_Processor_Widgets();