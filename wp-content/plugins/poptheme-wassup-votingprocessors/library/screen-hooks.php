<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_VotingProcessors_ScreenHooks {

	function __construct() {

		add_filter(
			'PoPTheme_Wassup_Utils:defaultformat_by_screen',
			array($this, 'get_defaultformat_by_screen'),
			0,
			2
		);
	}

	function get_defaultformat_by_screen($format, $screen) {

		switch ($screen) {

			case POP_VOTINGPROCESSORS_SCREEN_OPINIONATEDVOTES:
			case POP_VOTINGPROCESSORS_SCREEN_AUTHOROPINIONATEDVOTES:
			case POP_VOTINGPROCESSORS_SCREEN_TAGOPINIONATEDVOTES:
			case POP_VOTINGPROCESSORS_SCREEN_SINGLEOPINIONATEDVOTES:

				return GD_TEMPLATEFORMAT_LIST;

			case POP_VOTINGPROCESSORS_SCREEN_MYOPINIONATEDVOTES:

				return GD_TEMPLATEFORMAT_TABLE;
		}

		return $format;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_VotingProcessors_ScreenHooks();
