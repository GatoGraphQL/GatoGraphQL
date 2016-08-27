<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ControlGroupsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_CONTROLGROUP;
	}
	
	function init_atts($template_id, &$atts) {

		if ($blocktarget = $this->get_att($template_id, $atts, 'block-target')) {

			foreach ($this->get_modules($template_id) as $module) {
					
				$this->add_att($module, $atts, 'block-target', $blocktarget);
			}
		}

		$this->append_att($template_id, $atts, 'class', 'pop-hidden-print');

		return parent::init_atts($template_id, $atts);
	}
}