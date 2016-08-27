<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ControlButtonGroupsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_CONTROLBUTTONGROUP;
	}

	function init_atts($template_id, &$atts) {

		// Pass the needed control-target atts down the line		
		if ($control_target = $this->get_att($template_id, $atts, 'control-target')) {
				
			foreach ($this->get_modules($template_id) as $module) {
				$this->add_att($module, $atts, 'control-target', $control_target);
			}
		}

		if ($blocktarget = $this->get_att($template_id, $atts, 'block-target')) {

			foreach ($this->get_modules($template_id) as $module) {
					
				$this->add_att($module, $atts, 'block-target', $blocktarget);
			}
		}

		$this->append_att($template_id, $atts, 'class', 'pop-hidden-print');		
		return parent::init_atts($template_id, $atts);
	}
}