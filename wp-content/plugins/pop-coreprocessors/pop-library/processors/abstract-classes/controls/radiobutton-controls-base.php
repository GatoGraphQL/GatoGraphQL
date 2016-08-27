<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_RadioButtonControlsBase extends GD_Template_Processor_ControlsBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_CONTROL_RADIOBUTTON;
	}
}