<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CAROUSELCONTROLS_BLOG', PoP_TemplateIDUtils::get_template_definition('carouselcontrols-blog'));
define ('GD_TEMPLATE_CAROUSELCONTROLS_FEATURED', PoP_TemplateIDUtils::get_template_definition('carouselcontrols-featured'));

class GD_Custom_Template_Processor_CarouselControls extends GD_Template_Processor_CarouselControlsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CAROUSELCONTROLS_BLOG,
			GD_TEMPLATE_CAROUSELCONTROLS_FEATURED,
		);
	}

	function get_control_class($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELCONTROLS_BLOG:
			case GD_TEMPLATE_CAROUSELCONTROLS_FEATURED:

				return 'btn btn-link btn-compact';
		}

		return parent::get_control_class($template_id);
	}

	function get_title_class($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELCONTROLS_BLOG:
			case GD_TEMPLATE_CAROUSELCONTROLS_FEATURED:

				return 'btn btn-link btn-compact';
		}

		return parent::get_title_class($template_id);
	}
	function get_title($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELCONTROLS_BLOG:

				return gd_navigation_menu_item(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG, true).__('From our blog', 'poptheme-wassup-sectionprocessors');
		
			case GD_TEMPLATE_CAROUSELCONTROLS_FEATURED:
		
				return get_the_title(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FEATURED);
		}

		return parent::get_title($template_id);
	}
	protected function get_title_link($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELCONTROLS_BLOG:

				return get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG);

			case GD_TEMPLATE_CAROUSELCONTROLS_FEATURED:

				return get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FEATURED);
		}

		return parent::get_title_link($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_CarouselControls();