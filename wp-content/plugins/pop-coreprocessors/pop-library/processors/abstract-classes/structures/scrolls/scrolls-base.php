<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ScrollsBase extends GD_Template_Processor_StructuresBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_SCROLL;
	}

	protected function get_description($template_id, $atts) {
	
		return null;
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		if ($description = $this->get_att($template_id, $atts, 'description')) {
			$ret[GD_JS_DESCRIPTION/*'description'*/] = $description;
		}
		if ($inner_class = $this->get_att($template_id, $atts, 'inner-class')) {
			$ret[GD_JS_CLASSES/*'classes'*/]['inner'] = $inner_class;
		}
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		$this->add_att($template_id, $atts, 'description', $this->get_description($template_id, $atts));		
		return parent::init_atts($template_id, $atts);
	}
}
