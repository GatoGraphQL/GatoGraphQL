<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_POPOVER_EVENT', PoP_ServerUtils::get_template_definition('layout-popover-event'));

class GD_EM_Template_Processor_CustomPopoverLayouts extends GD_Template_Processor_PopoverLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_POPOVER_EVENT,
		);
	}

	function get_layout($template_id) {

		switch ($template_id) {
						
			case GD_TEMPLATE_LAYOUT_POPOVER_EVENT:

				return GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_POPOVER;
		}
		
		return parent::get_layout($template_id);
	}

	function get_layout_content($template_id) {

		switch ($template_id) {
						
			case GD_TEMPLATE_LAYOUT_POPOVER_EVENT:

				return GD_TEMPLATE_LAYOUTCALENDAR_CONTENT_POPOVER;
		}
		
		return parent::get_layout_content($template_id);
	}

	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_POPOVER_EVENT:

				// Use no Author popover
				$this->append_att($template_id, $atts, 'class', 'pop-elem');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

	function get_template_path($template_id, $atts) {
	
		switch ($template_id) {
				
			case GD_TEMPLATE_LAYOUT_POPOVER_EVENT:

				return $template_id;
		}
		
		return parent::get_template_path($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CustomPopoverLayouts();