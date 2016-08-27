<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CAROUSELCONTROLS_AUTHORMEMBERS', PoP_ServerUtils::get_template_definition('carouselcontrols-members'));

class GD_URE_Template_Processor_CustomCarouselControls extends GD_Template_Processor_CarouselControlsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CAROUSELCONTROLS_AUTHORMEMBERS,
		);
	}

	function get_control_class($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELCONTROLS_AUTHORMEMBERS:

				return 'btn btn-link btn-compact';
		}

		return parent::get_control_class($template_id);
	}

	function get_title_class($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELCONTROLS_AUTHORMEMBERS:

				return 'btn btn-link btn-compact';
		}

		return parent::get_title_class($template_id);
	}
	function get_title($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELCONTROLS_AUTHORMEMBERS:

				return gd_navigation_menu_item(POP_WPAPI_PAGE_ALLUSERS, true).__('Members', 'poptheme-wassup');
		}

		return parent::get_title($template_id);
	}
	protected function get_title_link($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELCONTROLS_AUTHORMEMBERS:

				global $author, $gd_template_settingsmanager;
				$url = get_author_posts_url($author);
				$page_ids = array(
					GD_TEMPLATE_CAROUSELCONTROLS_AUTHORMEMBERS => POP_URE_POPPROCESSORS_PAGE_MEMBERS,
				);
				return GD_TemplateManager_Utils::add_tab($url, $page_ids[$template_id]);
		}

		return parent::get_title_link($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CustomCarouselControls();