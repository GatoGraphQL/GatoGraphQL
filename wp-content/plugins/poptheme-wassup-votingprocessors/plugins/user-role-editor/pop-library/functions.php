<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * User Role Editor plugin Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('VotingProcessors_Template_Processor_CustomCarouselControls:authoropinionatedvotes:titlelink', 'gd_ure_add_source_param_pagesections');

add_filter('VotingProcessors_Template_Processor_CustomCarouselControls:authoropinionatedvotes:title', 'gd_votingprocessors_ure_titlemembers');
function gd_votingprocessors_ure_titlemembers($title) {

	global $author;
	if (gd_ure_is_community($author)) {

		$vars = GD_TemplateManager_Utils::get_vars();
		if ($vars['source'] == GD_URE_URLPARAM_CONTENTSOURCE_COMMUNITY) {
			
			$title .= __(' + Members', 'poptheme-wassup-votingprocessors');
		}
	}

	return $title;
}