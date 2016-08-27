<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CarouselInnersBase extends GD_Template_Processor_StructureInnersBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_CAROUSEL_INNER;
	}

	function get_layout_grid($template_id, $atts) {

		return array(
			'divider' => 1
		);
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		$ret['layout-grid'] = $this->get_layout_grid($template_id, $atts);

		return $ret;
	}
}
