<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_StructuresBase extends GD_Template_ProcessorBase {

	function get_inner_template($template_id) {

		return null;
	}
	
	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		// Sometimes there's no inner. Eg: GD_TEMPLATE_CONTENT_ADDCONTENTFAQ
		if ($inner = $this->get_inner_template($template_id)) {
			$ret[] = $inner;
		}
		
		return $ret;
	}
	
	function add_fetched_data($template_id, $atts) {
	
		return true;
	}
	
	function get_template_configuration($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = parent::get_template_configuration($template_id, $atts);

		if ($inner = $this->get_inner_template($template_id)) {

			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['inner'] = $gd_template_processor_manager->get_processor($inner)->get_settings_id($inner);

			// Add 'pop-merge' + inside template classes, so the processBlock knows where to insert the new html code
			if ($this->add_fetched_data($template_id, $atts)) {
				
				$ret['class-merge'] = $inner . ' pop-merge';
			}
		}
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		// Make the inner template callback updatable
		if ($this->add_fetched_data($template_id, $atts)) {

			if ($inner = $this->get_inner_template($template_id)) {
				$this->add_att($inner, $atts, 'template-cb', true);
			}
		}

		// Artificial property added to identify the template when adding template-resources
		$this->add_att($template_id, $atts, 'resourceloader', 'structure');

		return parent::init_atts($template_id, $atts);
	}
}
