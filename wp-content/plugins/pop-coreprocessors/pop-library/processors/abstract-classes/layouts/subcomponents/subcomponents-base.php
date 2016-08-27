<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_SubcomponentLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_SUBCOMPONENT;
	}

	function get_subcomponent_field($template_id) {
	
		return '';
	}

	function get_layouts($template_id) {

		return array();
	}

	function get_dataloader($template_id) {

		return null;
	}

	function get_subcomponent_modules($template_id) {
	
		return array(
			$this->get_subcomponent_field($template_id) => array(
				'modules' => $this->get_layouts($template_id),
				'dataloader' => $this->get_dataloader($template_id),
			)
		);
	}

	function is_individual($template_id, $atts) {
	
		return true;
	}

	function get_html_tag($template_id, $atts) {
	
		return 'div';
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;
	
		$ret['subcomponent-field'] = $this->get_subcomponent_field($template_id);
		if ($layouts = $this->get_layouts($template_id)) {
			
			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['layouts'] = $layouts;
			foreach ($layouts as $layout) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$layout] = $gd_template_processor_manager->get_processor($layout)->get_settings_id($layout);
			}
		}
		$ret['individual'] = $this->is_individual($template_id, $atts);
		$ret['html-tag'] = $this->get_html_tag($template_id, $atts);
		
		return $ret;
	}
}