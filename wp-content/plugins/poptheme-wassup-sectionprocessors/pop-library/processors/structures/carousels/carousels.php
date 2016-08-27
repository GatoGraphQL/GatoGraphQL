<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CAROUSEL_BLOG', PoP_ServerUtils::get_template_definition('carousel-blog'));
define ('GD_TEMPLATE_CAROUSEL_FEATURED', PoP_ServerUtils::get_template_definition('carousel-featured'));

class GD_Custom_Template_Processor_Carousels extends GD_Template_Processor_CarouselsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CAROUSEL_BLOG,
			GD_TEMPLATE_CAROUSEL_FEATURED,
		);
	}

	function init_atts($template_id, &$atts) {
			
		switch ($template_id) {

			case GD_TEMPLATE_CAROUSEL_BLOG:
			case GD_TEMPLATE_CAROUSEL_FEATURED:

				$this->append_att($template_id, $atts, 'class', 'slide');
				$this->append_att($template_id, $atts, 'class', 'widget widget-info');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

	function get_inner_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSEL_BLOG:

				return GD_TEMPLATE_CAROUSELINNER_BLOG;

			case GD_TEMPLATE_CAROUSEL_FEATURED:

				return GD_TEMPLATE_CAROUSELINNER_FEATURED;
		}

		return parent::get_inner_template($template_id);
	}

	function get_mode($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSEL_BLOG:
			case GD_TEMPLATE_CAROUSEL_FEATURED:

				return 'static';
		}

		return parent::get_mode($template_id, $atts);
	}


	function get_controls_top_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSEL_BLOG:

				return GD_TEMPLATE_CAROUSELCONTROLS_BLOG;

			case GD_TEMPLATE_CAROUSEL_FEATURED:

				return GD_TEMPLATE_CAROUSELCONTROLS_FEATURED;
		}

		return parent::get_controls_top_template($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_Carousels();