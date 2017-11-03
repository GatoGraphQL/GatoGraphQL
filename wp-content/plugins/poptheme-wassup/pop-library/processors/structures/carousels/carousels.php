<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CAROUSEL_USERS', PoP_TemplateIDUtils::get_template_definition('carousel-users'));
// define ('GD_TEMPLATE_CAROUSEL_LATESTCOMMENTS_HOME', 'carousel-latestcomments-home');

class GD_Template_Processor_CustomCarousels extends GD_Template_Processor_CarouselsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CAROUSEL_USERS,
			// GD_TEMPLATE_CAROUSEL_LATESTCOMMENTS_HOME,
		);
	}

	function init_atts($template_id, &$atts) {
			
		switch ($template_id) {

			case GD_TEMPLATE_CAROUSEL_USERS:
			// case GD_TEMPLATE_CAROUSEL_LATESTCOMMENTS_HOME:

				$this->append_att($template_id, $atts, 'class', 'slide');
				$this->append_att($template_id, $atts, 'class', 'widget widget-info');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

	function get_inner_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSEL_USERS:

				return GD_TEMPLATE_CAROUSELINNER_USERS;

			// case GD_TEMPLATE_CAROUSEL_LATESTCOMMENTS_HOME:

			// 	return GD_TEMPLATE_CAROUSELINNER_LATESTCOMMENTS_HOME;
		}

		return parent::get_inner_template($template_id);
	}

	function get_mode($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSEL_USERS:
			// case GD_TEMPLATE_CAROUSEL_LATESTCOMMENTS_HOME:

				return 'static';
		}

		return parent::get_mode($template_id, $atts);
	}


	function get_controls_top_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSEL_USERS:

				return GD_TEMPLATE_CAROUSELCONTROLS_USERS;

			// case GD_TEMPLATE_CAROUSEL_LATESTCOMMENTS_HOME:

			// 	return GD_TEMPLATE_CAROUSELCONTROLS_LATESTCOMMENTS_HOME;

		}

		return parent::get_controls_top_template($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomCarousels();