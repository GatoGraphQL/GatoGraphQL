<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_AutomatedEmails_Hooks {

	function __construct() {

		add_filter(
			'PoP_AutomatedEmails_Operator:automatedemail:header',
			array($this, 'get_automatedemail_header')
		);
		add_filter(
			'PoP_EmailSender_Utils:init:headers',
			array($this, 'init_headers')
		);
		add_filter(
			'PoP_AutomatedEmails_Frontend_ResourceLoader_Utils:automatedemail-pages',
			array($this, 'get_automatedemail_pages')
		);
	}

	function get_automatedemail_pages($pages) {

		$pages[] = POPTHEME_WASSUP_AUTOMATEDEMAILS_PAGE_LATESTPOSTS_WEEKLY;
		$pages[] = POPTHEME_WASSUP_AUTOMATEDEMAILS_PAGE_SINGLEPOST_SPECIAL;
		return $pages;
	}

	function get_automatedemail_header($header) {

		return 'newsletter';
	}

	function init_headers($headers) {

		$headers['newsletter'] = sprintf(
			"From: %s <%s>\r\n", 
			PoP_EmailSender_Utils::get_from_name(), 
			PoP_GenericForms_EmailSender_Utils::get_newsletter_email()
		).sprintf(
			"Content-Type: %s; charset=\"%s\"\n",
			PoP_EmailSender_Utils::get_contenttype(),
			PoP_EmailSender_Utils::get_charset()
		);
		return $headers;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_AutomatedEmails_Hooks();
