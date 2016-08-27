<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CAROUSELCONTROLS_USERS', PoP_ServerUtils::get_template_definition('carouselcontrols-users'));
// define ('GD_TEMPLATE_CAROUSELCONTROLS_LATESTCOMMENTS_HOME', 'carouselcontrols-latestcomments-home');

class GD_Template_Processor_CustomCarouselControls extends GD_Template_Processor_CarouselControlsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CAROUSELCONTROLS_USERS,
			// GD_TEMPLATE_CAROUSELCONTROLS_LATESTCOMMENTS_HOME,
		);
	}

	function get_control_class($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELCONTROLS_USERS:
			// case GD_TEMPLATE_CAROUSELCONTROLS_LATESTCOMMENTS_HOME:

				// return 'btn btn-success';
				return 'btn btn-link btn-compact';
		}

		return parent::get_control_class($template_id);
	}

	function get_title_class($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELCONTROLS_USERS:
			// case GD_TEMPLATE_CAROUSELCONTROLS_LATESTCOMMENTS_HOME:

				// return 'btn btn-success';
				return 'btn btn-link btn-compact';
		}

		return parent::get_title_class($template_id);
	}
	function get_title($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELCONTROLS_USERS:

				return gd_navigation_menu_item(POP_WPAPI_PAGE_ALLUSERS, true).__('Users', 'poptheme-wassup');

			// case GD_TEMPLATE_CAROUSELCONTROLS_LATESTCOMMENTS_HOME:

			// 	return gd_navigation_menu_item(POP_WPAPI_PAGE_COMMENTS, true).__('Latest Comments', 'poptheme-wassup');
		}

		return parent::get_title($template_id);
	}
	protected function get_title_link($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELCONTROLS_USERS:

				return get_permalink(POP_WPAPI_PAGE_ALLUSERS);
		}

		return parent::get_title_link($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomCarouselControls();