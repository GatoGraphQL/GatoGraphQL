<?php

/**---------------------------------------------------------------------------------------------------------------
 * Customizations
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('VotingProcessors_Template_Processor_ButtonUtils:singlepost:title', 'votingprocessors_em_createupdateopinionatedvote_singlepost_title');
function votingprocessors_em_createupdateopinionatedvote_singlepost_title($title) {

	if (get_post_type() == EM_POST_TYPE_EVENT) {

		// if (gd_em_single_event_is_past()) {

		return __('After attending this event', 'poptheme-wassup-votingprocessors');
		// }
	}

	return $title;
}

add_filter('VotingProcessors_Template_Processor_ButtonUtils:fullview:title', 'votingprocessors_em_createupdateopinionatedvote_fullview_title');
function votingprocessors_em_createupdateopinionatedvote_fullview_title($title) {

	return sprintf(
		'<span class="pop-fullview-addopinionatedvote-title">%s</span><span class="pop-fullevent-addopinionatedvote-title">%s</span>',
		$title,
		__('After attending this event', 'poptheme-wassup-votingprocessors')
	);
}