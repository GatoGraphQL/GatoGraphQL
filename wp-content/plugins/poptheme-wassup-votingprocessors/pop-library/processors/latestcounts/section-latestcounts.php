<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LATESTCOUNT_OPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('latestcount-opinionatedvotes'));
define ('GD_TEMPLATE_LATESTCOUNT_AUTHOR_OPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('latestcount-author-opinionatedvotes'));
define ('GD_TEMPLATE_LATESTCOUNT_SINGLE_OPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('latestcount-single-opinionatedvotes'));

define ('GD_TEMPLATE_LATESTCOUNT_OPINIONATEDVOTES_PRO', PoP_ServerUtils::get_template_definition('latestcount-opinionatedvotes-pro'));
define ('GD_TEMPLATE_LATESTCOUNT_AUTHOR_OPINIONATEDVOTES_PRO', PoP_ServerUtils::get_template_definition('latestcount-author-opinionatedvotes-pro'));
define ('GD_TEMPLATE_LATESTCOUNT_SINGLE_OPINIONATEDVOTES_PRO', PoP_ServerUtils::get_template_definition('latestcount-single-opinionatedvotes-pro'));

define ('GD_TEMPLATE_LATESTCOUNT_OPINIONATEDVOTES_AGAINST', PoP_ServerUtils::get_template_definition('latestcount-opinionatedvotes-against'));
define ('GD_TEMPLATE_LATESTCOUNT_AUTHOR_OPINIONATEDVOTES_AGAINST', PoP_ServerUtils::get_template_definition('latestcount-author-opinionatedvotes-against'));
define ('GD_TEMPLATE_LATESTCOUNT_SINGLE_OPINIONATEDVOTES_AGAINST', PoP_ServerUtils::get_template_definition('latestcount-single-opinionatedvotes-against'));

define ('GD_TEMPLATE_LATESTCOUNT_OPINIONATEDVOTES_NEUTRAL', PoP_ServerUtils::get_template_definition('latestcount-opinionatedvotes-neutral'));
define ('GD_TEMPLATE_LATESTCOUNT_AUTHOR_OPINIONATEDVOTES_NEUTRAL', PoP_ServerUtils::get_template_definition('latestcount-author-opinionatedvotes-neutral'));
define ('GD_TEMPLATE_LATESTCOUNT_SINGLE_OPINIONATEDVOTES_NEUTRAL', PoP_ServerUtils::get_template_definition('latestcount-single-opinionatedvotes-neutral'));

class PoPThemeWassup_VotingProcessors_Template_Processor_SectionLatestCounts extends GD_Template_Processor_SectionLatestCountsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LATESTCOUNT_OPINIONATEDVOTES,
			GD_TEMPLATE_LATESTCOUNT_AUTHOR_OPINIONATEDVOTES,
			GD_TEMPLATE_LATESTCOUNT_SINGLE_OPINIONATEDVOTES,
			GD_TEMPLATE_LATESTCOUNT_OPINIONATEDVOTES_PRO,
			GD_TEMPLATE_LATESTCOUNT_AUTHOR_OPINIONATEDVOTES_PRO,
			GD_TEMPLATE_LATESTCOUNT_SINGLE_OPINIONATEDVOTES_PRO,
			GD_TEMPLATE_LATESTCOUNT_OPINIONATEDVOTES_AGAINST,
			GD_TEMPLATE_LATESTCOUNT_AUTHOR_OPINIONATEDVOTES_AGAINST,
			GD_TEMPLATE_LATESTCOUNT_SINGLE_OPINIONATEDVOTES_AGAINST,
			GD_TEMPLATE_LATESTCOUNT_OPINIONATEDVOTES_NEUTRAL,
			GD_TEMPLATE_LATESTCOUNT_AUTHOR_OPINIONATEDVOTES_NEUTRAL,
			GD_TEMPLATE_LATESTCOUNT_SINGLE_OPINIONATEDVOTES_NEUTRAL,
		);
	}

	function get_object_name($template_id, $atts) {
	
		switch ($template_id) {
			
			case GD_TEMPLATE_LATESTCOUNT_OPINIONATEDVOTES:
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_OPINIONATEDVOTES:
			case GD_TEMPLATE_LATESTCOUNT_SINGLE_OPINIONATEDVOTES:
			case GD_TEMPLATE_LATESTCOUNT_OPINIONATEDVOTES_PRO:
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_OPINIONATEDVOTES_PRO:
			case GD_TEMPLATE_LATESTCOUNT_SINGLE_OPINIONATEDVOTES_PRO:
			case GD_TEMPLATE_LATESTCOUNT_OPINIONATEDVOTES_AGAINST:
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_OPINIONATEDVOTES_AGAINST:
			case GD_TEMPLATE_LATESTCOUNT_SINGLE_OPINIONATEDVOTES_AGAINST:
			case GD_TEMPLATE_LATESTCOUNT_OPINIONATEDVOTES_NEUTRAL:
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_OPINIONATEDVOTES_NEUTRAL:
			case GD_TEMPLATE_LATESTCOUNT_SINGLE_OPINIONATEDVOTES_NEUTRAL:
				
				return gd_get_categoryname(POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES, 'lc');
		}
	
		return parent::get_object_names($template_id, $atts);
	}

	function get_object_names($template_id, $atts) {
	
		switch ($template_id) {
			
			case GD_TEMPLATE_LATESTCOUNT_OPINIONATEDVOTES:
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_OPINIONATEDVOTES:
			case GD_TEMPLATE_LATESTCOUNT_SINGLE_OPINIONATEDVOTES:
			case GD_TEMPLATE_LATESTCOUNT_OPINIONATEDVOTES_PRO:
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_OPINIONATEDVOTES_PRO:
			case GD_TEMPLATE_LATESTCOUNT_SINGLE_OPINIONATEDVOTES_PRO:
			case GD_TEMPLATE_LATESTCOUNT_OPINIONATEDVOTES_AGAINST:
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_OPINIONATEDVOTES_AGAINST:
			case GD_TEMPLATE_LATESTCOUNT_SINGLE_OPINIONATEDVOTES_AGAINST:
			case GD_TEMPLATE_LATESTCOUNT_OPINIONATEDVOTES_NEUTRAL:
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_OPINIONATEDVOTES_NEUTRAL:
			case GD_TEMPLATE_LATESTCOUNT_SINGLE_OPINIONATEDVOTES_NEUTRAL:
				
				return gd_get_categoryname(POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES, 'plural-lc');
		}
	
		return parent::get_object_names($template_id, $atts);
	}

	function get_section_classes($template_id, $atts) {

		$ret = parent::get_section_classes($template_id, $atts);

		switch ($template_id) {
			
			case GD_TEMPLATE_LATESTCOUNT_OPINIONATEDVOTES:
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_OPINIONATEDVOTES:
			case GD_TEMPLATE_LATESTCOUNT_SINGLE_OPINIONATEDVOTES:
				
				$ret[] = 'post-'.POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES;
				$ret[] = 'post-'.POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_PRO;
				$ret[] = 'post-'.POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_AGAINST;
				$ret[] = 'post-'.POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_NEUTRAL;
				break;

			case GD_TEMPLATE_LATESTCOUNT_OPINIONATEDVOTES_PRO:
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_OPINIONATEDVOTES_PRO:
			case GD_TEMPLATE_LATESTCOUNT_SINGLE_OPINIONATEDVOTES_PRO:
				
				$ret[] = 'post-'.POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES;
				$ret[] = 'post-'.POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_PRO;
				break;

			case GD_TEMPLATE_LATESTCOUNT_OPINIONATEDVOTES_AGAINST:
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_OPINIONATEDVOTES_AGAINST:
			case GD_TEMPLATE_LATESTCOUNT_SINGLE_OPINIONATEDVOTES_AGAINST:
				
				$ret[] = 'post-'.POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES;
				$ret[] = 'post-'.POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_AGAINST;
				break;

			case GD_TEMPLATE_LATESTCOUNT_OPINIONATEDVOTES_NEUTRAL:
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_OPINIONATEDVOTES_NEUTRAL:
			case GD_TEMPLATE_LATESTCOUNT_SINGLE_OPINIONATEDVOTES_NEUTRAL:
				
				$ret[] = 'post-'.POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES;
				$ret[] = 'post-'.POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_NEUTRAL;
				break;
		}
	
		return $ret;
	}

	function is_author($template_id, $atts) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_OPINIONATEDVOTES:
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_OPINIONATEDVOTES_PRO:
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_OPINIONATEDVOTES_AGAINST:
			case GD_TEMPLATE_LATESTCOUNT_AUTHOR_OPINIONATEDVOTES_NEUTRAL:
				
				return true;
		}
	
		return parent::is_author($template_id, $atts);
	}

	function is_single($template_id, $atts) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LATESTCOUNT_SINGLE_OPINIONATEDVOTES:
			case GD_TEMPLATE_LATESTCOUNT_SINGLE_OPINIONATEDVOTES_PRO:
			case GD_TEMPLATE_LATESTCOUNT_SINGLE_OPINIONATEDVOTES_AGAINST:
			case GD_TEMPLATE_LATESTCOUNT_SINGLE_OPINIONATEDVOTES_NEUTRAL:
				
				return true;
		}
	
		return parent::is_single($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPThemeWassup_VotingProcessors_Template_Processor_SectionLatestCounts();