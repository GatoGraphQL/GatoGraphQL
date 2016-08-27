<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_COREPROCESSORS_CAROUSELMODE_STATIC', 'static');
define ('POP_COREPROCESSORS_CAROUSELMODE_AUTOMATIC', 'automatic');

// class GD_Template_Processor_CarouselsBase extends GD_Template_Processor_LayoutGridStructuresBase {
class GD_Template_Processor_CarouselsBase extends GD_Template_Processor_StructuresBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_CAROUSEL;
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		if ($controls_bottom = $this->get_controls_bottom_template($template_id)) {
			
			$ret[] = $controls_bottom;
		}
		if ($controls_top = $this->get_controls_top_template($template_id)) {
			
			$ret[] = $controls_top;
		}
	
		// Slideshow with indicators?
		if ($indicators = $this->get_layout_indicators($template_id)) {

			$ret[] = $indicators;
		}
		
		return $ret;
	}	

	function get_layout_indicators($template_id) {

		return null;
	}
	function get_mode($template_id, $atts) {

		return POP_COREPROCESSORS_CAROUSELMODE_STATIC;
	}
	// function show_controls($template_id, $atts) {

	// 	return false;
	// }
	function get_controls_bottom_template($template_id) {

		return null;
	}
	function get_controls_top_template($template_id) {

		return null;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		if ($this->get_mode($template_id, $atts) == POP_COREPROCESSORS_CAROUSELMODE_STATIC) {
			$this->add_jsmethod($ret, 'carouselStatic');
		}
		elseif ($this->get_mode($template_id, $atts) == POP_COREPROCESSORS_CAROUSELMODE_AUTOMATIC) {
			$this->add_jsmethod($ret, 'carouselAutomatic');
		}

		return $ret;
	}
	
	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		$ret['mode'] = $this->get_mode($template_id, $atts);

		if ($controls_bottom = $this->get_controls_bottom_template($template_id)) {
					
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['controls-bottom'] = $gd_template_processor_manager->get_processor($controls_bottom)->get_settings_id($controls_bottom);
		}
		if ($controls_top = $this->get_controls_top_template($template_id)) {
			
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['controls-top'] = $gd_template_processor_manager->get_processor($controls_top)->get_settings_id($controls_top);
		}

		if ($indicators = $this->get_layout_indicators($template_id)) {
			
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['carousel-indicators'] = $gd_template_processor_manager->get_processor($indicators)->get_settings_id($indicators);
		}

		return $ret;
	}
	
	function init_atts($template_id, &$atts) {
	
		if ($controls_bottom = $this->get_controls_bottom_template($template_id)) {

			$this->add_att($controls_bottom, $atts, 'carousel-template', $template_id);
		}
		if ($controls_top = $this->get_controls_top_template($template_id)) {
			
			$this->add_att($controls_top, $atts, 'carousel-template', $template_id);
		}
		if ($indicators = $this->get_layout_indicators($template_id)) {

			$this->add_att($indicators, $atts, 'carousel-template', $template_id);
		}

		return parent::init_atts($template_id, $atts);
	}
}
