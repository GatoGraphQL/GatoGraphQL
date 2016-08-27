<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class Wassup_EM_Template_SettingsProcessor extends GD_Template_SettingsProcessorBase {

	function get_checkpoints($hierarchy) {

		$ret = array();

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			return array(
				POPTHEME_WASSUP_EM_PAGE_ADDEVENT => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_STATIC),
				POPTHEME_WASSUP_EM_PAGE_ADDEVENTLINK => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_STATIC),
				POPTHEME_WASSUP_EM_PAGE_MYEVENTS => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),
				POPTHEME_WASSUP_EM_PAGE_MYPASTEVENTS => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),
				POPTHEME_WASSUP_EM_PAGE_EDITEVENT => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_CANEDIT),
				POPTHEME_WASSUP_EM_PAGE_EDITEVENTLINK => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_CANEDIT),
			);
		}
	
		return parent::get_checkpoints($hierarchy);
	}

	function get_page_blockgroups($hierarchy, $include_common = true) {

		$ret = array();

		// Page or Blocks inserted in Home
		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE || $hierarchy == GD_SETTINGS_HIERARCHY_HOME) {

			$pageblockgroups = array(
				POPTHEME_WASSUP_EM_PAGE_EVENTS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_EVENTS,
				POPTHEME_WASSUP_EM_PAGE_PASTEVENTS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_PASTEVENTS,
				POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR => GD_TEMPLATE_BLOCKGROUP_TABPANEL_EVENTSCALENDAR,
				POPTHEME_WASSUP_EM_PAGE_MYEVENTS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYEVENTS,
				POPTHEME_WASSUP_EM_PAGE_MYPASTEVENTS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYPASTEVENTS,
			);
			foreach ($pageblockgroups as $page => $blockgroup) {
				
				// Also Default
				$ret[$page]['blockgroups']['default'] = $blockgroup;
			}
		}

		// Author page blocks
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_AUTHOR) {

			$pageblockgroups = array(
				POPTHEME_WASSUP_EM_PAGE_EVENTS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHOREVENTS,
				POPTHEME_WASSUP_EM_PAGE_PASTEVENTS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORPASTEVENTS,
				POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR => GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHOREVENTSCALENDAR,
			);
			foreach ($pageblockgroups as $page => $blockgroup) {
				
				// Also Default
				$ret[$page]['blockgroups']['default'] = $blockgroup;
			}
		}

		return $ret;
	}

	function get_page_blocks($hierarchy, $include_common = true) {

		$ret = array();

		// $include_common: used to tell if we include also the common blocks in the response.
		// These common blocks are needed to produce the dataload-source of, eg, the Navigator Blocks, even
		// when first loading the website on an Author or Single page. Without the Navigator blocks placed in
		// common, then we can't get their dataload-source.
		// However, when generating the cache (file generator.php) these are not needed, so then skip them
		// Common blocks
		if ($include_common) {

			// Default
			$pageblocks_allothers = array(

				// Modals
				POP_EM_POPPROCESSORS_PAGE_ADDLOCATION => GD_TEMPLATE_BLOCK_CREATELOCATION,
				POP_EM_POPPROCESSORS_PAGE_LOCATIONSMAP => GD_TEMPLATE_BLOCK_LOCATIONSMAP,

				// Add Content
				POPTHEME_WASSUP_EM_PAGE_ADDEVENT => GD_TEMPLATE_BLOCK_EVENT_CREATE,
				POPTHEME_WASSUP_EM_PAGE_ADDEVENTLINK => GD_TEMPLATE_BLOCK_EVENTLINK_CREATE,

				// My Content
				POPTHEME_WASSUP_EM_PAGE_EDITEVENT => GD_TEMPLATE_BLOCK_EVENT_UPDATE,
				POPTHEME_WASSUP_EM_PAGE_EDITEVENTLINK => GD_TEMPLATE_BLOCK_EVENTLINK_UPDATE,

				// Others
				POP_EM_POPPROCESSORS_PAGE_LOCATIONS  => GD_TEMPLATE_BLOCK_LOCATIONS_SCROLL,
			);
			foreach ($pageblocks_allothers as $page => $block) {
				$ret[$page]['blocks']['default'] = $block;
			}

			// Actions 
			$pageactions = array(
				POP_EM_POPPROCESSORS_PAGE_ADDLOCATION => GD_TEMPLATE_ACTION_CREATELOCATION,
				POPTHEME_WASSUP_EM_PAGE_ADDEVENT => GD_TEMPLATE_ACTION_EVENT_CREATE,
				POPTHEME_WASSUP_EM_PAGE_ADDEVENTLINK => GD_TEMPLATE_ACTION_EVENTLINK_CREATE,
				POPTHEME_WASSUP_EM_PAGE_EDITEVENT => GD_TEMPLATE_ACTION_EVENT_UPDATE,
				POPTHEME_WASSUP_EM_PAGE_EDITEVENTLINK => GD_TEMPLATE_ACTION_EVENTLINK_UPDATE,
			);
			foreach ($pageactions as $page => $action) {
				$ret[$page]['action'] = $action;
			}
		}

		// Blocks in the homepage that need to access a PAGE dataload_source
		if ($hierarchy == GD_SETTINGS_HIERARCHY_HOME || $hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			$default_format_section = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_SECTION);
			$default_format_sectioncalendar = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_SECTIONCALENDAR);
			$default_format_mycontent = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_MYCONTENT);

			$pageblocks_typeahead = array(
				POPTHEME_WASSUP_EM_PAGE_EVENTS  => GD_TEMPLATE_BLOCK_EVENTS_TYPEAHEAD,
				POPTHEME_WASSUP_EM_PAGE_PASTEVENTS  => GD_TEMPLATE_BLOCK_PASTEVENTS_TYPEAHEAD,
				POP_EM_POPPROCESSORS_PAGE_LOCATIONS  => GD_TEMPLATE_BLOCK_LOCATIONS_TYPEAHEAD,
			);
			foreach ($pageblocks_typeahead as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_TYPEAHEAD] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_TYPEAHEAD) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_details = array(
				POPTHEME_WASSUP_EM_PAGE_EVENTS  => GD_TEMPLATE_BLOCK_EVENTS_SCROLL_DETAILS,
				POPTHEME_WASSUP_EM_PAGE_PASTEVENTS  => GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_DETAILS,
			);
			foreach ($pageblocks_details as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_DETAILS] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_DETAILS) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_simpleview = array(
				POPTHEME_WASSUP_EM_PAGE_EVENTS  => GD_TEMPLATE_BLOCK_EVENTS_SCROLL_SIMPLEVIEW,
				POPTHEME_WASSUP_EM_PAGE_PASTEVENTS  => GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_SIMPLEVIEW,
			);
			foreach ($pageblocks_simpleview as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_SIMPLEVIEW] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_SIMPLEVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_fullview = array(
				POPTHEME_WASSUP_EM_PAGE_EVENTS  => GD_TEMPLATE_BLOCK_EVENTS_SCROLL_FULLVIEW,
				POPTHEME_WASSUP_EM_PAGE_PASTEVENTS  => GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_FULLVIEW,
			);
			foreach ($pageblocks_fullview as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_FULLVIEW] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_FULLVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_thumbnail = array(
				POPTHEME_WASSUP_EM_PAGE_EVENTS  => GD_TEMPLATE_BLOCK_EVENTS_SCROLL_THUMBNAIL,
				POPTHEME_WASSUP_EM_PAGE_PASTEVENTS  => GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_THUMBNAIL,
			);
			foreach ($pageblocks_thumbnail as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_THUMBNAIL] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_THUMBNAIL) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_list = array(
				POPTHEME_WASSUP_EM_PAGE_EVENTS  => GD_TEMPLATE_BLOCK_EVENTS_SCROLL_LIST,
				POPTHEME_WASSUP_EM_PAGE_PASTEVENTS  => GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_LIST,
			);
			foreach ($pageblocks_list as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_LIST] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_LIST) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_map = array(
				POPTHEME_WASSUP_EM_PAGE_EVENTS  => GD_TEMPLATE_BLOCK_EVENTS_SCROLLMAP,
				POPTHEME_WASSUP_EM_PAGE_PASTEVENTS  => GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLLMAP,
			);
			foreach ($pageblocks_map as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_MAP] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_MAP) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_navigator = array(						
				POPTHEME_WASSUP_EM_PAGE_EVENTS  => GD_TEMPLATE_BLOCK_EVENTS_SCROLL_NAVIGATOR,
				POPTHEME_WASSUP_EM_PAGE_PASTEVENTS  => GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_NAVIGATOR,
			);
			foreach ($pageblocks_navigator as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_NAVIGATOR] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_NAVIGATOR) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_addons = array(						
				POPTHEME_WASSUP_EM_PAGE_EVENTS  => GD_TEMPLATE_BLOCK_EVENTS_SCROLL_ADDONS,
				POPTHEME_WASSUP_EM_PAGE_PASTEVENTS  => GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_ADDONS,
			);
			foreach ($pageblocks_addons as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_ADDONS] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_ADDONS) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_carousels = array(
				POPTHEME_WASSUP_EM_PAGE_EVENTS  => GD_TEMPLATE_BLOCK_EVENTS_CAROUSEL,
			);
			foreach ($pageblocks_carousels as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_CAROUSEL] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_CAROUSEL) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			
			$pageblocks_calendar = array(
				POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR  => GD_TEMPLATE_BLOCK_EVENTSCALENDAR_CALENDAR,
			);
			foreach ($pageblocks_calendar as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_CALENDAR] = $block;

				if ($default_format_sectioncalendar == GD_TEMPLATEFORMAT_CALENDAR) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_calendar_map = array(
				POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR  => GD_TEMPLATE_BLOCK_EVENTSCALENDAR_CALENDARMAP,
			);
			foreach ($pageblocks_calendar_map as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_MAP] = $block;

				if ($default_format_sectioncalendar == GD_TEMPLATEFORMAT_MAP) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_navigator = array(						
				POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR  => GD_TEMPLATE_BLOCK_EVENTSCALENDAR_CALENDAR_NAVIGATOR,
			);
			foreach ($pageblocks_navigator as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_NAVIGATOR] = $block;

				if ($default_format_sectioncalendar == GD_TEMPLATEFORMAT_NAVIGATOR) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_addons = array(						
				POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR  => GD_TEMPLATE_BLOCK_EVENTSCALENDAR_CALENDAR_ADDONS,
			);
			foreach ($pageblocks_addons as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_ADDONS] = $block;

				if ($default_format_sectioncalendar == GD_TEMPLATEFORMAT_ADDONS) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			
			$pageblocks_mycontent = array(
				POPTHEME_WASSUP_EM_PAGE_MYEVENTS => GD_TEMPLATE_BLOCK_MYEVENTS_TABLE_EDIT,
				POPTHEME_WASSUP_EM_PAGE_MYPASTEVENTS => GD_TEMPLATE_BLOCK_MYPASTEVENTS_TABLE_EDIT,
			);
			foreach ($pageblocks_mycontent as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_TABLE] = $block;

				if ($default_format_mycontent == GD_TEMPLATEFORMAT_TABLE) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_mycontent_simpleviewpreviews = array(
				POPTHEME_WASSUP_EM_PAGE_MYEVENTS => GD_TEMPLATE_BLOCK_MYEVENTS_SCROLL_SIMPLEVIEWPREVIEW,
				POPTHEME_WASSUP_EM_PAGE_MYPASTEVENTS => GD_TEMPLATE_BLOCK_MYPASTEVENTS_SCROLL_SIMPLEVIEWPREVIEW,
			);
			foreach ($pageblocks_mycontent_simpleviewpreviews as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_SIMPLEVIEW] = $block;

				if ($default_format_mycontent == GD_TEMPLATEFORMAT_SIMPLEVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_mycontent_fullviewpreviews = array(
				POPTHEME_WASSUP_EM_PAGE_MYEVENTS => GD_TEMPLATE_BLOCK_MYEVENTS_SCROLL_FULLVIEWPREVIEW,
				POPTHEME_WASSUP_EM_PAGE_MYPASTEVENTS => GD_TEMPLATE_BLOCK_MYPASTEVENTS_SCROLL_FULLVIEWPREVIEW,
			);
			foreach ($pageblocks_mycontent_fullviewpreviews as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_FULLVIEW] = $block;

				if ($default_format_mycontent == GD_TEMPLATEFORMAT_FULLVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
		}

		// Author page blocks
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_AUTHOR) {

			$default_format_authorsection = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_AUTHORSECTION);
			$default_format_authorsectioncalendar = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_AUTHORSECTIONCALENDAR);
			
			$pageblocks_details = array(
				POPTHEME_WASSUP_EM_PAGE_EVENTS  => GD_TEMPLATE_BLOCK_AUTHOREVENTS_SCROLL_DETAILS,
				POPTHEME_WASSUP_EM_PAGE_PASTEVENTS  => GD_TEMPLATE_BLOCK_AUTHORPASTEVENTS_SCROLL_DETAILS,
			);
			foreach ($pageblocks_details as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_DETAILS] = $block;

				if ($default_format_authorsection == GD_TEMPLATEFORMAT_DETAILS) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_simpleview = array(
				POPTHEME_WASSUP_EM_PAGE_EVENTS  => GD_TEMPLATE_BLOCK_AUTHOREVENTS_SCROLL_SIMPLEVIEW,
				POPTHEME_WASSUP_EM_PAGE_PASTEVENTS  => GD_TEMPLATE_BLOCK_AUTHORPASTEVENTS_SCROLL_SIMPLEVIEW,
			);
			foreach ($pageblocks_simpleview as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_SIMPLEVIEW] = $block;

				if ($default_format_authorsection == GD_TEMPLATEFORMAT_SIMPLEVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_fullview = array(
				POPTHEME_WASSUP_EM_PAGE_EVENTS  => GD_TEMPLATE_BLOCK_AUTHOREVENTS_SCROLL_FULLVIEW,
				POPTHEME_WASSUP_EM_PAGE_PASTEVENTS  => GD_TEMPLATE_BLOCK_AUTHORPASTEVENTS_SCROLL_FULLVIEW,
			);
			foreach ($pageblocks_fullview as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_FULLVIEW] = $block;

				if ($default_format_authorsection == GD_TEMPLATEFORMAT_FULLVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_thumbnail = array(
				POPTHEME_WASSUP_EM_PAGE_EVENTS  => GD_TEMPLATE_BLOCK_AUTHOREVENTS_SCROLL_THUMBNAIL,
				POPTHEME_WASSUP_EM_PAGE_PASTEVENTS  => GD_TEMPLATE_BLOCK_AUTHORPASTEVENTS_SCROLL_THUMBNAIL,
			);
			foreach ($pageblocks_thumbnail as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_THUMBNAIL] = $block;

				if ($default_format_authorsection == GD_TEMPLATEFORMAT_THUMBNAIL) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_list = array(
				POPTHEME_WASSUP_EM_PAGE_EVENTS  => GD_TEMPLATE_BLOCK_AUTHOREVENTS_SCROLL_LIST,
				POPTHEME_WASSUP_EM_PAGE_PASTEVENTS  => GD_TEMPLATE_BLOCK_AUTHORPASTEVENTS_SCROLL_LIST,
			);
			foreach ($pageblocks_list as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_LIST] = $block;

				if ($default_format_authorsection == GD_TEMPLATEFORMAT_LIST) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_map = array(
				POPTHEME_WASSUP_EM_PAGE_EVENTS  => GD_TEMPLATE_BLOCK_AUTHOREVENTS_SCROLLMAP,
				POPTHEME_WASSUP_EM_PAGE_PASTEVENTS  => GD_TEMPLATE_BLOCK_AUTHORPASTEVENTS_SCROLLMAP,
			);
			foreach ($pageblocks_map as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_MAP] = $block;

				if ($default_format_authorsection == GD_TEMPLATEFORMAT_MAP) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_carousels = array(
				POPTHEME_WASSUP_EM_PAGE_EVENTS  => GD_TEMPLATE_BLOCK_AUTHOREVENTS_CAROUSEL,
			);
			foreach ($pageblocks_carousels as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_CAROUSEL] = $block;

				if ($default_format_authorsection == GD_TEMPLATEFORMAT_CAROUSEL) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}

			$pageblocks_calendar = array(
				POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR  => GD_TEMPLATE_BLOCK_AUTHOREVENTSCALENDAR_CALENDAR,
			);
			foreach ($pageblocks_calendar as $page => $block) {
				
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_CALENDAR] = $block;

				if ($default_format_authorsectioncalendar == GD_TEMPLATEFORMAT_CALENDAR) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_calendarmap = array(
				POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR  => GD_TEMPLATE_BLOCK_AUTHOREVENTSCALENDAR_CALENDARMAP,
			);
			foreach ($pageblocks_calendarmap as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_MAP] = $block;

				if ($default_format_authorsectioncalendar == GD_TEMPLATEFORMAT_MAP) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
		}

		return $ret;	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_EM_Template_SettingsProcessor();
