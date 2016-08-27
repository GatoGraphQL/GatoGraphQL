<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_SIDEBARSECTION_ORGANIZATION', 'organization');
define ('GD_SIDEBARSECTION_INDIVIDUAL', 'individual');

define ('GD_COMPACTSIDEBARSECTION_ORGANIZATION', 'compact-organization');
define ('GD_COMPACTSIDEBARSECTION_INDIVIDUAL', 'compact-individual');

class URE_FullUserSidebarSettings {
	
	public static function get_components($section) {

		$ret = array();

		// $vars = GD_TemplateManager_Utils::get_vars();
		// $fetching_quickview = $vars['fetching-json-quickview'];

		switch ($section) {

			case GD_SIDEBARSECTION_ORGANIZATION:
			case GD_SIDEBARSECTION_INDIVIDUAL:

				// $ret[] = GD_TEMPLATE_WIDGET_AUTHOR_AVATARORIGINAL;
				$ret[] = GD_TEMPLATE_LAYOUT_AUTHOR_USERPHOTO;
				// Don't add for the quickview since we can't open a modal on top of a modal
				if (!$fetching_quickview) {
					$ret[] = GD_TEMPLATE_USERSOCIALMEDIA;
				}
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL;
				if (PoPTheme_Wassup_Utils::add_author_widget_details()) {				
					$ret[] = GD_TEMPLATE_WIDGETWRAPPER_AUTHOR_CONTACT;
					$ret[] = GD_TEMPLATE_EM_WIDGET_USERLOCATIONSMAP;
				}
				$ret = apply_filters('gd_template:sidebar_author:components', $ret, $section);
				break;

			case GD_COMPACTSIDEBARSECTION_ORGANIZATION:

				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_AVATAR;
				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_ORGANIZATION;
				break;

			case GD_COMPACTSIDEBARSECTION_INDIVIDUAL:

				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_AVATAR;
				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_INDIVIDUAL;
				break;
		}
		
		return $ret;
	}
}