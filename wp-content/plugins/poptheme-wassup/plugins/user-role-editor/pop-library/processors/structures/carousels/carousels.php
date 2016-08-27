<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CAROUSEL_AUTHORMEMBERS', PoP_ServerUtils::get_template_definition('carousel-authormembers'));

class GD_URE_Template_Processor_CustomCarousels extends GD_Template_Processor_CarouselsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CAROUSEL_AUTHORMEMBERS,
		);
	}

	function init_atts($template_id, &$atts) {
			
		switch ($template_id) {

			case GD_TEMPLATE_CAROUSEL_AUTHORMEMBERS:

				$this->append_att($template_id, $atts, 'class', 'slide');
				$this->append_att($template_id, $atts, 'class', 'widget widget-info');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

	function get_inner_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSEL_AUTHORMEMBERS:

				return GD_TEMPLATE_CAROUSELINNER_AUTHORMEMBERS;
		}

		return parent::get_inner_template($template_id);
	}

	function get_mode($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSEL_AUTHORMEMBERS:

				return 'static';
		}

		return parent::get_mode($template_id, $atts);
	}


	function get_controls_top_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSEL_AUTHORMEMBERS:

				return GD_TEMPLATE_CAROUSELCONTROLS_AUTHORMEMBERS;
		}

		return parent::get_controls_top_template($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CustomCarousels();