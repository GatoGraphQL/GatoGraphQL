<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_EVENT', PoP_ServerUtils::get_template_definition('vertical-sidebar-single-event'));
define ('GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_PASTEVENT', PoP_ServerUtils::get_template_definition('vertical-sidebar-single-pastevent'));

class GD_EM_Template_Processor_CustomVerticalSingleSidebars extends GD_Template_Processor_SidebarsBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_EVENT,
			GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_PASTEVENT,
		);
	}

	function get_inner_template($template_id) {

		$sidebarinners = array(
			GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_EVENT => GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_EVENT,
			GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_PASTEVENT => GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_PASTEVENT,
		);

		if ($inner = $sidebarinners[$template_id]) {
			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_EVENT:
			case GD_TEMPLATE_VERTICALSIDEBAR_SINGLE_PASTEVENT:
			
				$this->append_att($template_id, $atts, 'class', 'vertical');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CustomVerticalSingleSidebars();