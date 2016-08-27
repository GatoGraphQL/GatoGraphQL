<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class VotingProcessors_Template_Processor_ButtonUtils {

	public static function get_singlepost_addopinionatedvote_title() {

		// Allow Events to have a different title
		return apply_filters(
			'VotingProcessors_Template_Processor_ButtonUtils:singlepost:title',
			__('After reading this information', 'poptheme-wassup-votingprocessors')
		);
	}

	public static function get_fullview_addopinionatedvote_title() {

		// Allow Events to have a different title
		return apply_filters(
			'VotingProcessors_Template_Processor_ButtonUtils:fullview:title',
			__('After reading this information', 'poptheme-wassup-votingprocessors')
		);
	}
}