<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CategoriesLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_CATEGORIES;
	}

	function get_data_fields($template_id, $atts) {

		return array(
			$this->get_categories_field($template_id, $atts),
		);
	}

	function get_categories_field($template_id, $atts) {
		
		return null;
	}
	function get_label_class($template_id, $atts) {
		
		return 'label-warning';
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		$ret['categories-field'] = $this->get_categories_field($template_id, $atts);
		$ret[GD_JS_CLASSES/*'classes'*/] = array(				
			'label' => $this->get_label_class($template_id, $atts),
		);

		return $ret;
	}
}