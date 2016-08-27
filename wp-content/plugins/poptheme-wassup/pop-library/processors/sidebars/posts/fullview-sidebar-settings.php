<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_SIDEBARSECTION_GENERIC', 'generic');
define ('GD_SIDEBARSECTION_WEBPOSTLINK', 'webpostlink');
define ('GD_SIDEBARSECTION_HIGHLIGHT', 'highlight');
define ('GD_SIDEBARSECTION_WEBPOST', 'webpost');
define ('GD_COMPACTSIDEBARSECTION_GENERIC', 'compact-generic');
define ('GD_COMPACTSIDEBARSECTION_WEBPOSTLINK', 'compact-webpostlink');
define ('GD_COMPACTSIDEBARSECTION_HIGHLIGHT', 'compact-highlight');
define ('GD_COMPACTSIDEBARSECTION_WEBPOST', 'compact-webpost');

class FullViewSidebarSettings {
	
	public static function get_components($section) {

		$ret = array();

		switch ($section) {

			case GD_SIDEBARSECTION_GENERIC:

				$ret[] = GD_TEMPLATE_LAYOUT_POSTTHUMB_FEATUREDIMAGE;//GD_TEMPLATE_LAYOUTWRAPPER_POSTTHUMB_FEATUREDIMAGE;//GD_TEMPLATE_LAYOUT_POSTTHUMB_ORIGINALFEATUREDIMAGE;
				$ret[] = GD_TEMPLATE_POSTSOCIALMEDIA_POSTWRAPPER;
				$ret[] = GD_TEMPLATE_WIDGET_POST_AUTHORS;
				break;

			case GD_SIDEBARSECTION_HIGHLIGHT:

				$ret[] = GD_TEMPLATE_SUBJUGATEDPOSTSOCIALMEDIA_POSTWRAPPER;
				$ret[] = GD_TEMPLATE_WIDGET_POST_AUTHORS;
				break;

			case GD_SIDEBARSECTION_WEBPOSTLINK:

				$ret[] = GD_TEMPLATE_LAYOUT_POSTTHUMB_FEATUREDIMAGE;//GD_TEMPLATE_LAYOUTWRAPPER_POSTTHUMB_FEATUREDIMAGE;//GD_TEMPLATE_LAYOUT_POSTTHUMB_ORIGINALFEATUREDIMAGE;
				$ret[] = GD_TEMPLATE_POSTSOCIALMEDIA_POSTWRAPPER;
				if (PoPTheme_Wassup_Utils::add_categories_to_widget()) {
					$ret[] = GD_TEMPLATE_WIDGET_CATEGORIES;
				}
				if (PoPTheme_Wassup_Utils::add_appliesto()) {
					$ret[] = GD_TEMPLATE_WIDGET_APPLIESTO;
				}
				$ret[] = GD_TEMPLATE_WIDGET_LINK_ACCESS;
				$ret[] = GD_TEMPLATE_WIDGET_POST_AUTHORS;
				break;

			case GD_SIDEBARSECTION_WEBPOST:

				$ret[] = GD_TEMPLATE_LAYOUT_POSTTHUMB_FEATUREDIMAGE;//GD_TEMPLATE_LAYOUTWRAPPER_POSTTHUMB_FEATUREDIMAGE;//GD_TEMPLATE_LAYOUT_POSTTHUMB_ORIGINALFEATUREDIMAGE;
				$ret[] = GD_TEMPLATE_POSTSOCIALMEDIA_POSTWRAPPER;
				if (PoPTheme_Wassup_Utils::add_categories_to_widget()) {
					$ret[] = GD_TEMPLATE_WIDGET_CATEGORIES;
				}
				if (PoPTheme_Wassup_Utils::add_appliesto()) {
					$ret[] = GD_TEMPLATE_WIDGET_APPLIESTO;
				}
				$ret[] = GD_TEMPLATE_WIDGET_POST_AUTHORS;
				break;

			case GD_COMPACTSIDEBARSECTION_GENERIC:

				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATUREDIMAGE;
				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_GENERIC;
				break;

			case GD_COMPACTSIDEBARSECTION_WEBPOSTLINK:

				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATUREDIMAGE;
				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_LINK;
				break;

			case GD_COMPACTSIDEBARSECTION_WEBPOST:

				// $ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_WEBPOSTLEFT;
				// $ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_WEBPOSTRIGHT;
				// Only if the Volunteering is enabled
				if (POPTHEME_WASSUP_GF_PAGE_VOLUNTEER) {
					$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATUREDIMAGEVOLUNTEER;
				}
				else {
					$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATUREDIMAGE;
				}
				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_WEBPOST;
				break;

			case GD_COMPACTSIDEBARSECTION_HIGHLIGHT:

				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_HIGHLIGHTLEFT;
				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_HIGHLIGHTRIGHT;
				break;
		}
		
		return $ret;
	}
}