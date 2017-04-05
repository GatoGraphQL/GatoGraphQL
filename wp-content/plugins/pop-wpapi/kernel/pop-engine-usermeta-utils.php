<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_TemplateManager_UserMetaUtils {

	public static function save_user_meta() {

		// Function used to save information on the user access. In particular, we need the last access time, for the Notifications
		// Can do it only if the page is stateful
		if (GD_TemplateManager_Utils::page_requires_user_state()) {
			if (is_user_logged_in()) {
				GD_MetaManager::update_user_meta(get_current_user_id(), POP_METAKEY_USER_LASTACCESS, POP_CONSTANT_CURRENTTIMESTAMP/*current_time('timestamp')*/, true);
			}
		}
	}
}
