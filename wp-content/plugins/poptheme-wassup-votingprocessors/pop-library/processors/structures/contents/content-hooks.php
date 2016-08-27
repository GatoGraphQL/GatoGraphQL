<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_VotingProcessors_ContentHooks {

	function __construct() {

		// add_filter(
		// 	// 'GD_Template_Processor_SingleContentInners:userpostinteraction_layouts', 
		// 	'Wassup_Template_Processor_MultipleComponentLayouts:userpostinteraction_layouts', 
		// 	array($this, 'userpostinteraction_layouts'), 
		// 	10, 
		// 	2
		// );
		add_filter(
			'GD_Template_Processor_Contents:inner_template', 
			array($this, 'content_inner'), 
			10, 
			2
		);
	}

	function content_inner($inner, $template_id) {

		if ($template_id == GD_TEMPLATE_CONTENT_USERPOSTINTERACTION) {

			switch (gd_get_the_main_category()) {

				// OpinionatedVoteds: it has a different set-up
				case POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES:
					
					return GD_TEMPLATE_CONTENTINNER_USEROPINIONATEDVOTEPOSTINTERACTION;
			}
		}
		elseif ($template_id == GD_TEMPLATE_CONTENT_SINGLE) {

			switch (gd_get_the_main_category()) {

				// OpinionatedVoteds: it has a different set-up
				case POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES:
					
					return GD_TEMPLATE_CONTENTINNER_OPINIONATEDVOTESINGLE;
			}
		}
		
		return $inner;
	}

	// function userpostinteraction_layouts($layouts, $template_id) {

	// 	$addchanges = array(
	// 		GD_TEMPLATE_MULTICOMPONENT_USERPOSTINTERACTION,
	// 		// GD_TEMPLATE_CONTENTINNER_USERPOSTINTERACTION,
	// 	);
	// 	if (in_array($template_id, $addchanges)) {

	// 		// Replace the user post interaction with the one for TPP Debate website: no comments, only Highlights and OpinionatedVoteds
	// 		$replace = array(
	// 			VOTINGPROCESSORS_TEMPLATE_LAYOUTWRAPPER_USERPOSTINTERACTION,
	// 		);
	// 		array_splice($layouts, array_search(GD_TEMPLATE_LAYOUTWRAPPER_USERPOSTINTERACTION, $layouts), 1, $replace);

	// 		// No need for Comments
	// 		array_splice($layouts, array_search(GD_TEMPLATE_LAZYSUBCOMPONENT_POSTCOMMENTS, $layouts), 1);
	// 	}
		
	// 	return $layouts;
	// }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_VotingProcessors_ContentHooks();
