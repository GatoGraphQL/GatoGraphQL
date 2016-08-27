<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_SIDEBARSECTION_FARM', 'farm');
define ('GD_COMPACTSIDEBARSECTION_FARM', 'compact-farm');

class OP_FullViewSidebarSettings {
	
	public static function get_components($section) {

		$ret = array();

		switch ($section) {

			case GD_SIDEBARSECTION_FARM:

				$ret[] = GD_TEMPLATE_LAYOUT_POSTTHUMB_FEATUREDIMAGE;//GD_TEMPLATE_LAYOUTWRAPPER_POSTTHUMB_FEATUREDIMAGE;//GD_TEMPLATE_LAYOUT_POSTTHUMB_ORIGINALFEATUREDIMAGE;				
				$ret[] = GD_TEMPLATE_POSTSOCIALMEDIA_POSTWRAPPER;
				// Only if the Volunteering is enabled
				if (POPTHEME_WASSUP_GF_PAGE_VOLUNTEER) {
					$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_BIG;
				}
				if (PoPTheme_Wassup_Utils::add_categories_to_widget()) {
					$ret[] = GD_TEMPLATE_WIDGET_CATEGORIES;
				}
				if (PoPTheme_Wassup_Utils::add_appliesto()) {
					$ret[] = GD_TEMPLATE_WIDGET_APPLIESTO;
				}
				$ret[] = GD_TEMPLATE_EM_WIDGET_POSTLOCATIONSMAP;
				$ret[] = GD_TEMPLATE_WIDGET_POST_AUTHORS;
				break;

			case GD_COMPACTSIDEBARSECTION_FARM:

				// Only if the Volunteering is enabled
				if (POPTHEME_WASSUP_GF_PAGE_VOLUNTEER) {
					$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATUREDIMAGEVOLUNTEER;
				}
				else {
					$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATUREDIMAGE;
				}
				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_FARM;
				break;
		}
		
		return $ret;
	}
}