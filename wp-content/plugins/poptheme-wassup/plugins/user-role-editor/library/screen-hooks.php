<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_URE_ScreenHooks {

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

			case POP_URE_SCREEN_MYUSERS:

				return GD_TEMPLATEFORMAT_TABLE;
		}

		return $format;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_URE_ScreenHooks();
