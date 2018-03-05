<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_AutomatedEmails_AAL_Hooks {

	function __construct() {

		add_filter(
			'PoP_AutomatedEmails_Frontend_ResourceLoader_Utils:automatedemail-pages',
			array($this, 'get_automatedemail_pages')
		);
	}

	function get_automatedemail_pages($pages) {

		$pages[] = POPTHEME_WASSUP_AUTOMATEDEMAILS_PAGE_LATESTNOTIFICATIONS_DAILY;
		return $pages;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_AutomatedEmails_AAL_Hooks();
