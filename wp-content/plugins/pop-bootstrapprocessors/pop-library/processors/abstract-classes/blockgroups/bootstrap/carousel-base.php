<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CarouselBlockGroupsBase extends GD_Template_Processor_DefaultActivePanelBootstrapJavascriptBlockGroupsBase {

	protected function get_block_extension_templates($template_id) {

		$ret = parent::get_block_extension_templates($template_id);
		$ret[] = GD_TEMPLATESOURCE_BLOCKGROUP_CAROUSEL;
		return $ret;
	}

	function get_panel_header_type($template_id) {

		return 'indicators';
	}

	// function init_atts($template_id, &$atts) {
	
	// 	$this->add_att(GD_TEMPLATE_CAROUSEL_INDICATORS, $atts, 'carousel-template', $template_id);
	// 	return parent::init_atts($template_id, $atts);
	// }

	function get_carousel_class($template_id) {

		return 'slide';
	}
	function get_carousel_params($template_id) {

		return array(
			'data-interval' => false,
			'data-wrap' => false,
			'data-ride' => 'carousel'
		);
	}

	function get_panelactive_class($template_id) {

		return 'active';
	}

	function get_bootstrapcomponent_type($template_id) {

		return 'carousel';
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		if ($carousel_class = $this->get_carousel_class($template_id)) {
		
			$ret[GD_JS_CLASSES/*'classes'*/]['carousel'] = $carousel_class;
		}
		if ($carousel_params = $this->get_carousel_params($template_id)) {
		
			$ret['carousel-params'] = $carousel_params;
		}
				
		return $ret;
	}
}