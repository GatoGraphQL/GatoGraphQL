<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_HOOK_SCROLLINNER_THUMBNAIL_GRID', 'scrollinner-thumbnail-grid');

class GD_Template_Processor_ScrollInnersBase extends GD_Template_Processor_StructureInnersBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_SCROLL_INNER;
	}

	function get_layout_grid($template_id, $atts) {

		return array(
			'row-items' => 1, 
			'class' => 'col-sm-12'
		);
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		$ret['layout-grid'] = $this->get_att($template_id, $atts, 'layout-grid');

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		$this->add_att($template_id, $atts, 'layout-grid', $this->get_layout_grid($template_id, $atts));
		return parent::init_atts($template_id, $atts);
	}
}
