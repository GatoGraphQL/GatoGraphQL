<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_SIDEBARSECTION_PROJECT', 'project');
define ('GD_SIDEBARSECTION_DISCUSSION', 'discussion');
define ('GD_SIDEBARSECTION_STORY', 'story');
define ('GD_SIDEBARSECTION_ANNOUNCEMENT', 'announcement');
define ('GD_SIDEBARSECTION_FEATURED', 'featured');
define ('GD_SIDEBARSECTION_BLOG', 'blog');

define ('GD_COMPACTSIDEBARSECTION_PROJECT', 'compact-project');
define ('GD_COMPACTSIDEBARSECTION_DISCUSSION', 'compact-discussion');
define ('GD_COMPACTSIDEBARSECTION_STORY', 'compact-story');
define ('GD_COMPACTSIDEBARSECTION_ANNOUNCEMENT', 'compact-announcement');
define ('GD_COMPACTSIDEBARSECTION_FEATURED', 'compact-featured');
define ('GD_COMPACTSIDEBARSECTION_BLOG', 'compact-blog');

class Custom_FullViewSidebarSettings {
	
	public static function get_components($section) {

		$ret = array();

		switch ($section) {

			case GD_SIDEBARSECTION_PROJECT:

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

			case GD_SIDEBARSECTION_BLOG:

				$ret[] = GD_TEMPLATE_LAYOUT_POSTTHUMB_FEATUREDIMAGE;//GD_TEMPLATE_LAYOUTWRAPPER_POSTTHUMB_FEATUREDIMAGE;//GD_TEMPLATE_LAYOUT_POSTTHUMB_ORIGINALFEATUREDIMAGE;
				$ret[] = GD_TEMPLATE_POSTSOCIALMEDIA_POSTWRAPPER;
				$ret[] = GD_TEMPLATE_WIDGET_POST_AUTHORS;
				break;

			case GD_SIDEBARSECTION_STORY:
			case GD_SIDEBARSECTION_FEATURED:

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

			case GD_SIDEBARSECTION_DISCUSSION:

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

			case GD_SIDEBARSECTION_ANNOUNCEMENT:

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
				$ret[] = GD_TEMPLATE_WIDGET_POST_AUTHORS;
				break;


			case GD_COMPACTSIDEBARSECTION_PROJECT:

				// Only if the Volunteering is enabled
				if (POPTHEME_WASSUP_GF_PAGE_VOLUNTEER) {
					$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATUREDIMAGEVOLUNTEER;
				}
				else {
					$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATUREDIMAGE;
				}
				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_PROJECT;
				break;

			case GD_COMPACTSIDEBARSECTION_ANNOUNCEMENT:

				// Only if the Volunteering is enabled
				if (POPTHEME_WASSUP_GF_PAGE_VOLUNTEER) {
					$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATUREDIMAGEVOLUNTEER;
				}
				else {
					$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATUREDIMAGE;
				}
				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_ANNOUNCEMENT;
				break;

			case GD_COMPACTSIDEBARSECTION_STORY:

				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATUREDIMAGE;
				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_STORY;
				break;

			case GD_COMPACTSIDEBARSECTION_DISCUSSION:

				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATUREDIMAGE;
				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_DISCUSSION;
				break;

			case GD_COMPACTSIDEBARSECTION_BLOG:

				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATUREDIMAGE;
				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_BLOG;
				break;

			case GD_COMPACTSIDEBARSECTION_FEATURED:

				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATUREDIMAGE;
				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATURED;
				break;
		}
		
		return $ret;
	}
}