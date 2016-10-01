<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CAROUSELCONTROLS_OPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('carouselcontrols-opinionatedvotes'));
define ('GD_TEMPLATE_CAROUSELCONTROLS_OPINIONATEDVOTES_BYORGANIZATIONS', PoP_ServerUtils::get_template_definition('carouselcontrols-opinionatedvotes-byorganizations'));
define ('GD_TEMPLATE_CAROUSELCONTROLS_OPINIONATEDVOTES_BYINDIVIDUALS', PoP_ServerUtils::get_template_definition('carouselcontrols-opinionatedvotes-byindividuals'));
define ('GD_TEMPLATE_CAROUSELCONTROLS_AUTHOROPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('carouselcontrols-authoropinionatedvotes'));
define ('GD_TEMPLATE_CAROUSELCONTROLS_TAGOPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('carouselcontrols-tagopinionatedvotes'));

class VotingProcessors_Template_Processor_CustomCarouselControls extends GD_Template_Processor_CarouselControlsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CAROUSELCONTROLS_OPINIONATEDVOTES,
			GD_TEMPLATE_CAROUSELCONTROLS_OPINIONATEDVOTES_BYORGANIZATIONS,
			GD_TEMPLATE_CAROUSELCONTROLS_OPINIONATEDVOTES_BYINDIVIDUALS,
			GD_TEMPLATE_CAROUSELCONTROLS_AUTHOROPINIONATEDVOTES,
			GD_TEMPLATE_CAROUSELCONTROLS_TAGOPINIONATEDVOTES,
		);
	}

	function get_control_class($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELCONTROLS_OPINIONATEDVOTES:
			case GD_TEMPLATE_CAROUSELCONTROLS_OPINIONATEDVOTES_BYORGANIZATIONS:
			case GD_TEMPLATE_CAROUSELCONTROLS_OPINIONATEDVOTES_BYINDIVIDUALS:
			case GD_TEMPLATE_CAROUSELCONTROLS_AUTHOROPINIONATEDVOTES:
			case GD_TEMPLATE_CAROUSELCONTROLS_TAGOPINIONATEDVOTES:

				return 'btn btn-link btn-compact';
		}

		return parent::get_control_class($template_id);
	}

	function get_target($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELCONTROLS_OPINIONATEDVOTES:
			case GD_TEMPLATE_CAROUSELCONTROLS_OPINIONATEDVOTES_BYORGANIZATIONS:
			case GD_TEMPLATE_CAROUSELCONTROLS_OPINIONATEDVOTES_BYINDIVIDUALS:
			case GD_TEMPLATE_CAROUSELCONTROLS_AUTHOROPINIONATEDVOTES:
			case GD_TEMPLATE_CAROUSELCONTROLS_TAGOPINIONATEDVOTES:

				return GD_URLPARAM_TARGET_QUICKVIEW;
		}

		return parent::get_target($template_id, $atts);
	}
	function get_title_class($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELCONTROLS_OPINIONATEDVOTES:
			case GD_TEMPLATE_CAROUSELCONTROLS_OPINIONATEDVOTES_BYORGANIZATIONS:
			case GD_TEMPLATE_CAROUSELCONTROLS_OPINIONATEDVOTES_BYINDIVIDUALS:
			case GD_TEMPLATE_CAROUSELCONTROLS_AUTHOROPINIONATEDVOTES:
			case GD_TEMPLATE_CAROUSELCONTROLS_TAGOPINIONATEDVOTES:

				return 'btn btn-link btn-compact';
		}

		return parent::get_title_class($template_id);
	}
	function get_title($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELCONTROLS_OPINIONATEDVOTES:

				return get_the_title(POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES);

			case GD_TEMPLATE_CAROUSELCONTROLS_OPINIONATEDVOTES_BYORGANIZATIONS:

				return gd_navigation_menu_item(POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES, true).__('By organizations', 'poptheme-wassup-votingprocessors');

			case GD_TEMPLATE_CAROUSELCONTROLS_OPINIONATEDVOTES_BYINDIVIDUALS:

				return gd_navigation_menu_item(POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES, true).__('By individuals', 'poptheme-wassup-votingprocessors');

			case GD_TEMPLATE_CAROUSELCONTROLS_AUTHOROPINIONATEDVOTES:

				global $author;
				
				// Allow URE to override adding "+Members"
				$name = apply_filters(
					'VotingProcessors_Template_Processor_CustomCarouselControls:authoropinionatedvotes:title',
					get_the_author_meta('display_name', $author)
				);
				// return sprintf(
				// 	__('By %s', 'poptheme-wassup-votingprocessors'),
				// 	$name
				// );
				return sprintf(
					__('%s by %s', 'poptheme-wassup-votingprocessors'),
					// get_the_title(POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES),
					gd_navigation_menu_item(POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES, true).PoPTheme_Wassup_VotingProcessors_Utils::get_latestvotes_title(),//__('Latest thoughts on TPP', 'poptheme-wassup-votingprocessors'),
					$name
				);

			case GD_TEMPLATE_CAROUSELCONTROLS_TAGOPINIONATEDVOTES:

				$name = single_tag_title('', false);
				return sprintf(
					__('%s tagged with “%s”', 'poptheme-wassup-votingprocessors'),
					gd_navigation_menu_item(POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES, true).PoPTheme_Wassup_VotingProcessors_Utils::get_latestvotes_title(),
					$name
				);
		}

		return parent::get_title($template_id);
	}
	protected function get_title_link($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELCONTROLS_OPINIONATEDVOTES:

				return get_permalink(POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES);

			case GD_TEMPLATE_CAROUSELCONTROLS_OPINIONATEDVOTES_BYORGANIZATIONS:

				return get_permalink(POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_BYORGANIZATIONS);

			case GD_TEMPLATE_CAROUSELCONTROLS_OPINIONATEDVOTES_BYINDIVIDUALS:

				return get_permalink(POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_BYINDIVIDUALS);

			case GD_TEMPLATE_CAROUSELCONTROLS_AUTHOROPINIONATEDVOTES:

				global $author, $gd_template_settingsmanager;
				$url = get_author_posts_url($author);
				$page_ids = array(
					GD_TEMPLATE_CAROUSELCONTROLS_AUTHOROPINIONATEDVOTES => POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES,
				);
				
				// Allow URE to override adding "+Members" param
				return apply_filters(
					'VotingProcessors_Template_Processor_CustomCarouselControls:authoropinionatedvotes:titlelink',
					GD_TemplateManager_Utils::add_tab($url, $page_ids[$template_id])
				);

			case GD_TEMPLATE_CAROUSELCONTROLS_TAGOPINIONATEDVOTES:

				global $gd_template_settingsmanager;
				$url = get_tag_link(get_queried_object_id());
				$page_ids = array(
					GD_TEMPLATE_CAROUSELCONTROLS_TAGOPINIONATEDVOTES => POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES,
				);
				
				return apply_filters(
					'VotingProcessors_Template_Processor_CustomCarouselControls:tagopinionatedvotes:titlelink',
					GD_TemplateManager_Utils::add_tab($url, $page_ids[$template_id])
				);
		}

		return parent::get_title_link($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_CustomCarouselControls();