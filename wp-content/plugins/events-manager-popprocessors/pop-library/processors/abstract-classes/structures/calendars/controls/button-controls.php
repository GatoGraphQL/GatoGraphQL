<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CALENDARBUTTONCONTROL_CALENDARPREV', PoP_ServerUtils::get_template_definition('calendarbuttoncontrol-calendarprev'));
define ('GD_TEMPLATE_CALENDARBUTTONCONTROL_CALENDARNEXT', PoP_ServerUtils::get_template_definition('calendarbuttoncontrol-calendarnext'));

class GD_Template_Processor_CalendarButtonControls extends GD_Template_Processor_ButtonControlsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CALENDARBUTTONCONTROL_CALENDARPREV,
			GD_TEMPLATE_CALENDARBUTTONCONTROL_CALENDARNEXT,
		);
	}

	function get_label($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CALENDARBUTTONCONTROL_CALENDARPREV:

				return __('Previous month', 'em-popprocessors');

			case GD_TEMPLATE_CALENDARBUTTONCONTROL_CALENDARNEXT:

				return __('Next month', 'em-popprocessors');
		}

		return parent::get_label($template_id, $atts);
	}
	function get_icon($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CALENDARBUTTONCONTROL_CALENDARPREV:

				return 'glyphicon-chevron-left';

			case GD_TEMPLATE_CALENDARBUTTONCONTROL_CALENDARNEXT:

				return 'glyphicon-chevron-right';
		}

		return parent::get_icon($template_id);
	}
	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CALENDARBUTTONCONTROL_CALENDARPREV:
			case GD_TEMPLATE_CALENDARBUTTONCONTROL_CALENDARNEXT:

				$classes = array(
					GD_TEMPLATE_CALENDARBUTTONCONTROL_CALENDARPREV => 'calendar-prev',
					GD_TEMPLATE_CALENDARBUTTONCONTROL_CALENDARNEXT => 'calendar-next'
				);
				$class = $classes[$template_id];

				$this->append_att($template_id, $atts, 'class', $class . ' fetchmore-btn-disable');
				// $calendar_target = $this->get_att($template_id, $atts, 'calendar-target');
				// $this->merge_att($template_id, $atts, 'params', array(
				// 	'data-target' => $calendar_target
				// ));
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
	function get_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CALENDARBUTTONCONTROL_CALENDARPREV:
			case GD_TEMPLATE_CALENDARBUTTONCONTROL_CALENDARNEXT:

				return null;
		}

		return parent::get_text($template_id, $atts);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_CALENDARBUTTONCONTROL_CALENDARPREV:
				$this->add_jsmethod($ret, 'controlCalendarPrev');
				break;

			case GD_TEMPLATE_CALENDARBUTTONCONTROL_CALENDARNEXT:
				$this->add_jsmethod($ret, 'controlCalendarNext');
				break;
		}
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CalendarButtonControls();