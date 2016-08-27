<?php

/**---------------------------------------------------------------------------------------------------------------
 * navigation.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_navigation_menu_item_icon', 'popwassup_votingprocessors_navigation_menu_item_icon', 10, 3); 
function popwassup_votingprocessors_navigation_menu_item_icon($icon, $menu_item_object_id, $html = true) {

	switch ($menu_item_object_id) {

		case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_MYOPINIONATEDVOTES:
		case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_ADDOPINIONATEDVOTE:
		case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_EDITOPINIONATEDVOTE:
		case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_ADDOREDITOPINIONATEDVOTE:
		case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES:
		case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_BYORGANIZATIONS:
		case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_BYINDIVIDUALS:

			$fontawesome = 'fa-commenting-o';
			break;

		case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO:
		case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO_GENERAL:
		case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO_ARTICLE:

			$fontawesome = 'fa-thumbs-o-up';
			break;

		case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST:
		case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST_GENERAL:
		case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST_ARTICLE:

			$fontawesome = 'fa-thumbs-o-down';
			break;

		case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL:
		case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL_GENERAL:
		case POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL_ARTICLE:

			$fontawesome = 'fa-hand-peace-o';
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
