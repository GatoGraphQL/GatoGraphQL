<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_SidebarInnersBase extends GD_Template_Processor_ContentSingleInnersBase {

	function get_wrapper_class($template_id) {
	
		return '';
	}
	function get_widgetwrapper_class($template_id) {
	
		return '';
	}

	function init_atts($template_id, &$atts) {
	
		if ($wrapper_class = $this->get_wrapper_class($template_id)) {
			$this->append_att($template_id, $atts, 'class', $wrapper_class);
		}
		if ($widgetwrapper_class = $this->get_widgetwrapper_class($template_id)) {
			foreach ($this->get_layouts($template_id) as $layout) {
				$this->merge_att($layout, $atts, 'classes', array(
					$widgetwrapper_class
				));
			}
		}

		return parent::init_atts($template_id, $atts);
	}
}