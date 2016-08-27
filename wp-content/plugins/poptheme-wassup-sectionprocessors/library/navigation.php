<?php

/**---------------------------------------------------------------------------------------------------------------
 * navigation.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_navigation_menu_item_icon', 'popwassup_wassup_navigation_menu_item_icon', 10, 3); 
function popwassup_wassup_navigation_menu_item_icon($icon, $menu_item_object_id, $html = true) {

	switch ($menu_item_object_id) {

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FEATURED:
		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDFEATURED:
		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITFEATURED:

			$fontawesome = 'fa-star';
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG:

			$fontawesome = 'fa-pencil-square';
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_PROJECTS:
		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYPROJECTS:
		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDPROJECT:
		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITPROJECT:

			$fontawesome = 'fa-briefcase';
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES:
		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYSTORIES:
		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDSTORY:
		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITSTORY:

			$fontawesome = 'fa-camera-retro';
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS:
		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYDISCUSSIONS:
		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDDISCUSSION:
		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITDISCUSSION:

			$fontawesome = 'fa-comment';
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS:
		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYANNOUNCEMENTS:
		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDANNOUNCEMENT:
		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITANNOUNCEMENT:

			$fontawesome = 'fa-bullhorn';
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDPROJECTLINK:
		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITPROJECTLINK:
		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDSTORYLINK:
		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITSTORYLINK:
		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDDISCUSSIONLINK:
		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITDISCUSSIONLINK:
		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDANNOUNCEMENTLINK:
		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITANNOUNCEMENTLINK:

			$fontawesome = 'fa-link';
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT:
		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_HOWTOUSEWEBSITE:
		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_CONTENTGUIDELINES:

			$fontawesome = 'fa-info-circle';
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_OURMISSION:

			$fontawesome = 'fa-rocket';
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_WHOWEARE:

			$fontawesome = 'fa-smile-o';
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_OURSTORY:

			$fontawesome = 'fa-backward';
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_INTHEMEDIA:

			$fontawesome = 'fa-video-camera';
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_VOLUNTEERWITHUS:

			$fontawesome = 'fa-leaf';
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_SPONSORUS:

			$fontawesome = 'fa-heart-o';
			break;

		case POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_OURSPONSORS:

			$fontawesome = 'fa-trophy';
			break;
	}

	// Important: do not replace the \' below for quotes, otherwise the "Share by email" and "Embed" buttons
	// don't work for pages (eg: http://m3l.localhost/become-a-featured-community/) since the fontawesome icons
	// screw up the data-header attr in the link
	if ($fontawesome) {

		if ($html) {
			return sprintf('<i class=\'fa fa-fw %s\'></i>', $fontawesome);
		}
		return $fontawesome;
	}
	
	return $icon;
}
