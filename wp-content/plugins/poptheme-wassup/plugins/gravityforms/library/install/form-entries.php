<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Preferences
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_GF_Install_FormEntries {

	function __construct() {

		add_action(
			'PoP:system-install', 
			array($this, 'system_install_newsletter')
		);
	}

	function system_install_newsletter() {

		// Newsletter Entries can defined to be added on system install by PoP version
		if (GD_Template_Helper_GFForm::get_newsletter_form_id() && POPTHEME_WASSUP_GF_SYSTEMINSTALL_NEWSLETTER_ENTRIES) {

			foreach (POPTHEME_WASSUP_GF_SYSTEMINSTALL_NEWSLETTER_ENTRIES as $version => $entries) {

				// Is the PoP version the right one?
				if ($version == pop_version()) {

					// Add all entries. Documentation in https://www.gravityhelp.com/documentation/article/api-functions/#add_entries
					GFAPI::add_entries($entries, GD_Template_Helper_GFForm::get_newsletter_form_id());
				}
			}
		}
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_GF_Install_FormEntries();