<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MULTICOMPONENT_EVENT_DATELOCATION', PoP_ServerUtils::get_template_definition('multicomponent-event-datelocation'));
define ('GD_TEMPLATE_MULTICOMPONENT_LOCATIONVOLUNTEER', PoP_ServerUtils::get_template_definition('multicomponent-locationvolunteer'));

class GD_EM_Template_Processor_EventMultipleComponents extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MULTICOMPONENT_EVENT_DATELOCATION,
			GD_TEMPLATE_MULTICOMPONENT_LOCATIONVOLUNTEER,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_MULTICOMPONENT_EVENT_DATELOCATION:

				$ret[] = GD_TEMPLATE_EM_LAYOUT_DATETIMEDOWNLOADLINKS;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_LOCATIONVOLUNTEER:

				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS;
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY;
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_MULTICOMPONENT_EVENT_DATELOCATION:
			case GD_TEMPLATE_MULTICOMPONENT_LOCATIONVOLUNTEER:

				$classes = array(
					GD_TEMPLATE_MULTICOMPONENT_EVENT_DATELOCATION => 'event-datelocation',
					GD_TEMPLATE_MULTICOMPONENT_LOCATIONVOLUNTEER => 'locationvolunteer',
				);

				$this->append_att($template_id, $atts, 'class', $classes[$template_id]);
				$this->append_att(GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POSTLOCATIONS, $atts, 'btn-class', 'btn btn-link btn-nopadding');
				$this->append_att(GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY, $atts, 'class', 'btn-nopadding');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_EventMultipleComponents();