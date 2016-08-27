<?php

class PoPTheme_Wassup_VotingProcessors_Utils {

	public static function get_latestvotes_title() {

		// Allow TPPDebate to override this title
		return apply_filters(
			'PoPTheme_Wassup_VotingProcessors_Utils:latestvotes_title',
			__('Latest votes', 'poptheme-wassup-votingprocessors')
		);
	}

	public static function get_whatisyourvote_title($format = '') {

		if ($format == 'lc') {

			$title = __('what is your vote?', 'poptheme-wassup-votingprocessors');
		}
		else {
			$title = __('What is your vote?', 'poptheme-wassup-votingprocessors');
		}

		// Allow TPPDebate to override this title
		return apply_filters(
			'PoPTheme_Wassup_VotingProcessors_Utils:whatisyourvote_title',
			$title,
			$format
		);
	}
}