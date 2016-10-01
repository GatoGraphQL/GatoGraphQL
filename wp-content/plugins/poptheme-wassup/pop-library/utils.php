<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_Utils {

	public static function get_defaultformat_by_screen($screen) {

		$format = '';
		switch ($screen) {

			// The screens below need no formatting, since they have only 1 available format anyway
			// case POP_SCREEN_ABOUTUS:
			// case POP_SCREEN_ACCOUNT:
			// case POP_SCREEN_INFORMATIONPAGE:
			// case POP_SCREEN_MYPROFILE:
			// case POP_SCREEN_SINGLE:
			// case POP_SCREEN_AUTHOR:
			case POP_SCREEN_SECTION:
			case POP_SCREEN_AUTHORSECTION:
			case POP_SCREEN_SINGLESECTION:
			case POP_SCREEN_TAGSECTION:
			case POP_SCREEN_HOMESECTION:

				$format = GD_TEMPLATEFORMAT_SIMPLEVIEW;
				break;

			case POP_SCREEN_SECTIONCALENDAR:
			case POP_SCREEN_AUTHORSECTIONCALENDAR:
			case POP_SCREEN_TAGSECTIONCALENDAR:

				$format = GD_TEMPLATEFORMAT_CALENDAR;
				break;

			case POP_SCREEN_MYCONTENT:
			case POP_SCREEN_MYHIGHLIGHTS:

				$format = GD_TEMPLATEFORMAT_TABLE;
				break;
				
			case POP_SCREEN_NOTIFICATIONS:
			case POP_SCREEN_TAGS:
			case POP_SCREEN_HIGHLIGHTS:
			case POP_SCREEN_SINGLEHIGHLIGHTS:

				$format = GD_TEMPLATEFORMAT_LIST;
				break;
				
			case POP_SCREEN_USERS:
			case POP_SCREEN_AUTHORUSERS:
			case POP_SCREEN_SINGLEUSERS:

				$format = GD_TEMPLATEFORMAT_DETAILS;
				break;
		}

		return apply_filters(
			'PoPTheme_Wassup_Utils:defaultformat_by_screen',
			$format,
			$screen
		);
	}

	// public static function add_simpleview() {

	// 	// For backward compatibility
	// 	return apply_filters('PoPTheme_Wassup_Utils:add_simpleview', true);
	// }

	public static function get_webpostsection_cats() {

		return apply_filters('wassup_webpostsection_cats', array());
	}

	public static function add_appliesto() {

		// Add the "Applies To" box if the filter adding all the values has been defined
		return apply_filters('PoPTheme_Wassup_Utils:add_appliesto', false) && has_filter('wassup_appliesto');
	}

	public static function add_sections() {

		return apply_filters('PoPTheme_Wassup_Utils:add_sections', true);
	}

	public static function add_categories() {

		// By default, do not add the categories
		return apply_filters('PoPTheme_Wassup_Utils:add_categories', false) && has_filter('wassup_categories');
	}

	public static function add_author_widget_details() {

		return apply_filters('PoPTheme_Wassup_Utils:add_author_widget_details', false);
	}

	public static function add_categories_to_widget() {

		// If not using categories in general, then of course no need to add them to the widget
		return self::add_categories() && apply_filters('PoPTheme_Wassup_Utils:add_categories_to_widget', false);
	}

	public static function add_link_accesstype() {

		return apply_filters('PoPTheme_Wassup_Utils:add_link_accesstype', false);
	}

	public static function get_addcontent_target() {

		// By default, create new content in the Addons pageSection
		return apply_filters('PoPTheme_Wassup_Utils:addcontent_target', GD_URLPARAM_TARGET_ADDONS);
	}

	public static function feed_simpleview_lazyload() {

		return apply_filters('PoPTheme_Wassup_Utils:feed_simpleview_lazyload', true);
	}

	public static function feed_details_lazyload() {

		return apply_filters('PoPTheme_Wassup_Utils:feed_details_lazyload', false);
	}

	public static function author_fulldescription() {

		// Show the author profile's description in the body? or as a widget?
		// Default: as a widget
		return apply_filters('PoPTheme_Wassup_Utils:author_fulldescription', false);
	}

	public static function add_background_menu() {

		// Allow the background to not have the menu. Needed for GetPoP
		return apply_filters('PoPTheme_Wassup_Utils:add_background_menu', false);
	}

	public static function get_welcome_title($add_tagline = false) {

		$welcometitle = sprintf(
			__('Welcome to %s!', 'poptheme-wassup'),
			get_bloginfo('name')
		);
		if ($add_tagline) {
			$welcometitle = sprintf(
				'%s<br/><small>%s</small>',
				$welcometitle,
				get_bloginfo('description')
			);
		}

		// Allow Organik Fundraising to override the welcome title
		return apply_filters('PoPTheme_Wassup_Utils:welcome_title', $welcometitle);
	}
}