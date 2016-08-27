<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_INSTANTANEOUSFILTERINNER_CATEGORIES', PoP_ServerUtils::get_template_definition('instantaneousfilterinner-categories'));
define ('GD_TEMPLATE_INSTANTANEOUSFILTERINNER_SECTIONS', PoP_ServerUtils::get_template_definition('instantaneousfilterinner-sections'));
define ('GD_TEMPLATE_INSTANTANEOUSFILTERINNER_WEBPOSTSECTIONS', PoP_ServerUtils::get_template_definition('instantaneousfilterinner-webpostsections'));

class GD_Template_Processor_InstantaneousSimpleFilterInners extends GD_Template_Processor_InstantaneousSimpleFilterInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_INSTANTANEOUSFILTERINNER_CATEGORIES,
			GD_TEMPLATE_INSTANTANEOUSFILTERINNER_SECTIONS,
			GD_TEMPLATE_INSTANTANEOUSFILTERINNER_WEBPOSTSECTIONS,
		);
	}
	
	function get_filter($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_INSTANTANEOUSFILTERINNER_CATEGORIES:

				return GD_FILTER_CATEGORIES;

			case GD_TEMPLATE_INSTANTANEOUSFILTERINNER_SECTIONS:

				return GD_FILTER_SECTIONS;

			case GD_TEMPLATE_INSTANTANEOUSFILTERINNER_WEBPOSTSECTIONS:

				return GD_FILTER_WEBPOSTSECTIONS;
		}

		return parent::get_filter($template_id);
	}

	function get_trigger_internaltarget($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_INSTANTANEOUSFILTERINNER_CATEGORIES:
			case GD_TEMPLATE_INSTANTANEOUSFILTERINNER_SECTIONS:
			case GD_TEMPLATE_INSTANTANEOUSFILTERINNER_WEBPOSTSECTIONS:

				// Trigger when clicking on the labels inside the btn-group
				return '.pop-filterinner-instantaneous > label > input';
		}

		return parent::get_trigger_internaltarget($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_INSTANTANEOUSFILTERINNER_CATEGORIES:
			case GD_TEMPLATE_INSTANTANEOUSFILTERINNER_SECTIONS:
			case GD_TEMPLATE_INSTANTANEOUSFILTERINNER_WEBPOSTSECTIONS:

				$btngroups = array(
					GD_TEMPLATE_INSTANTANEOUSFILTERINNER_CATEGORIES => GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_CATEGORIES,
					GD_TEMPLATE_INSTANTANEOUSFILTERINNER_SECTIONS => GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_SECTIONS,
					GD_TEMPLATE_INSTANTANEOUSFILTERINNER_WEBPOSTSECTIONS => GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_WEBPOSTSECTIONS,
				);
				$btngroup = $btngroups[$template_id];

				// Add class so we can find the element to be clicked to submit the form
				$this->append_att($btngroup, $atts, 'class', 'pop-filterinner-instantaneous');

				// Add justified style to the btn-group
				$this->append_att($btngroup, $atts, 'class', 'btn-group-justified');

				// Make it also small
				$this->add_att($btngroup, $atts, 'btn-class', 'btn btn-default btn-sm');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_InstantaneousSimpleFilterInners();