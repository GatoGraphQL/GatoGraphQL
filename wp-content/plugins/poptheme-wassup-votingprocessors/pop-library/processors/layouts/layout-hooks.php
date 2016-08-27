<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_VotingProcessors_LayoutHooks {

	function __construct() {

		// add_filter(
		// 	'GD_Template_Processor_FullObjectLayoutsBase:footer_templates', 
		// 	array($this, 'single_footers'), 
		// 	10, 
		// 	2
		// );
		// add_filter(
		// 	'GD_Template_Processor_CustomQuicklinkGroups:modules', 
		// 	array($this, 'quicklinks'), 
		// 	10, 
		// 	2
		// );
		add_filter(
			'GD_Template_Processor_CustomPreviewPostLayoutsBase:simpleviewfeed_bottom_layouts', 
			array($this, 'feed_bottom_layouts')
		);
		add_filter(
			'GD_Template_Processor_CustomPreviewPostLayoutsBase:detailsfeed_bottom_layouts', 
			array($this, 'feed_bottom_layouts')
		);
		add_filter(
			'GD_Template_Processor_CustomPostMultipleSidebarComponents:featuredimage:modules', 
			array($this, 'single_components')
		);
		add_filter(
			'GD_Template_Processor_CustomPostMultipleSidebarComponents:featuredimagevolunteer:modules', 
			array($this, 'single_components')
		);
		add_filter(
			// 'GD_Template_Processor_CustomControlGroups:userpostinteraction:layouts', 
			'Wassup_Template_Processor_UserPostInteractionLayouts:userpostinteraction:layouts', 
			array($this, 'userpostinteraction')
		);

	}

	function userpostinteraction($layouts) {

		// Only if it is not single. In that case, we add the block to directly add the Thought
		// Add the "What do you think about TPP?" before the userpostinteraction layouts
		if (!is_single()) {
			array_unshift($layouts, GD_TEMPLATE_LAZYBUTTONWRAPPER_OPINIONATEDVOTE_CREATEORUPDATE);
		}
		return $layouts;
	}

	function single_components($layouts) {

		// Add the poststance at the end
		$layouts[] = GD_TEMPLATE_BUTTONGROUPWRAPPER_OPINIONATEDVOTECOUNT;
		return $layouts;
	}

	function feed_bottom_layouts($layouts) {

		// Add the poststance at the beginning
		array_unshift($layouts, GD_TEMPLATE_BUTTONGROUPWRAPPER_OPINIONATEDVOTECOUNT);
		return $layouts;
	}

	// function quicklinks($quicklinks, $template_id) {

	// 	// For the TPP website we re-use the MESYM Theme but add the "What do you think about TPP?" module in the single/fullview templates
	// 	$addchanges = array(
	// 		GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOM,
	// 		GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOMVOLUNTEER,
	// 		GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOMEXTENDED,
	// 		GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOMEXTENDEDVOLUNTEER,
	// 	);
	// 	if (in_array($template_id, $addchanges)) {

	// 		// No need for Comments
	// 		array_splice($quicklinks, array_search(GD_TEMPLATE_QUICKLINKBUTTONGROUP_COMMENTS, $quicklinks), 1);
	// 	}

	// 	return $quicklinks;
	// }

	// function single_footers($footers, $template_id) {

	// 	// For the TPP website we re-use the MESYM Theme but add the "What do you think about TPP?" module in the single/fullview templates
	// 	$addchanges = array(
	// 		// From poptheme-wassup
	// 		GD_TEMPLATE_LAYOUT_FULLVIEW_LINK,
	// 		GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_LINK,
	// 		GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_LINK,

	// 		// From poptheme-wassup/plugins/events-manager
	// 		GD_TEMPLATE_LAYOUT_FULLVIEW_EVENT,
	// 		GD_TEMPLATE_LAYOUT_FULLVIEW_PASTEVENT,
	// 		GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_EVENT,
	// 		GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_PASTEVENT,
	// 		GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_EVENT,
	// 		GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_PASTEVENT,

	// 		// From poptheme-wassup-sectionprocessors
	// 		GD_TEMPLATE_LAYOUT_FULLVIEW_ANNOUNCEMENT,
	// 		GD_TEMPLATE_LAYOUT_FULLVIEW_PROJECT,
	// 		GD_TEMPLATE_LAYOUT_FULLVIEW_FEATURED,
	// 		GD_TEMPLATE_LAYOUT_FULLVIEW_DISCUSSION,
	// 		GD_TEMPLATE_LAYOUT_FULLVIEW_STORY,
	// 		GD_TEMPLATE_LAYOUT_FULLVIEW_BLOG,
	// 		GD_TEMPLATE_LAYOUT_FULLVIEW_THOUGHT,
	// 		GD_TEMPLATE_LAYOUT_FULLVIEW_HOMEMESSAGE,
	// 		GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_ANNOUNCEMENT,
	// 		GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_PROJECT,
	// 		GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_DISCUSSION,
	// 		GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_STORY,
	// 		GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_FEATURED,
	// 		GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_ANNOUNCEMENT,
	// 		GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_PROJECT,
	// 		GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_DISCUSSION,
	// 		GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_STORY,
	// 		GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_FEATURED,
	// 	);
	// 	if (in_array($template_id, $addchanges)) {

	// 		// Replace the user post interaction with the one for TPP Debate website: no comments, only Highlights and OpinionatedVoteds
	// 		$replace = array(
	// 			VOTINGPROCESSORS_TEMPLATE_LAYOUTWRAPPER_USERFULLVIEWINTERACTION,
	// 		);
	// 		array_splice($footers, array_search(GD_TEMPLATE_LAYOUTWRAPPER_USERPOSTINTERACTION, $footers), 1, $replace);

	// 		// No need for Comments
	// 		// array_splice($footers, array_search(GD_TEMPLATE_LAZYSUBCOMPONENT_POSTCOMMENTS, $footers), 1);
	// 	}

	// 	return $footers;
	// }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_VotingProcessors_LayoutHooks();
