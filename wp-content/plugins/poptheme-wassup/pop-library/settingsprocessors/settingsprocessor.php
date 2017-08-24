<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_Template_SettingsProcessor extends GD_Template_SettingsProcessorBase {

	function process_pages($hierarchy) {

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			return array(
				POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES,
			);
		}

		return array();
	}

	function silent_document($hierarchy) {

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			return array(
				POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES => true,
				POP_MULTIDOMAIN_PAGE_LOADERS_INITIALIZEDOMAIN => true,
				POP_WPAPI_PAGE_LOADERS_POSTS_FIELDS => true,
				POP_WPAPI_PAGE_LOADERS_USERS_FIELDS => true,
				POP_WPAPI_PAGE_LOADERS_COMMENTS_FIELDS => true,
				POP_WPAPI_PAGE_LOADERS_TAGS_FIELDS => true,
				POP_WPAPI_PAGE_LOADERS_POSTS_LAYOUTS => true,
				POP_WPAPI_PAGE_LOADERS_USERS_LAYOUTS => true,
				POP_WPAPI_PAGE_LOADERS_COMMENTS_LAYOUTS => true,
				POP_WPAPI_PAGE_LOADERS_TAGS_LAYOUTS => true,
				POP_COREPROCESSORS_PAGE_LOGGEDINUSERDATA => true,
				POP_COREPROCESSORS_PAGE_FOLLOWUSER => true,
				POP_COREPROCESSORS_PAGE_UNFOLLOWUSER => true,
				POP_COREPROCESSORS_PAGE_RECOMMENDPOST => true,
				POP_COREPROCESSORS_PAGE_UNRECOMMENDPOST => true,
				POP_COREPROCESSORS_PAGE_SUBSCRIBETOTAG => true,
				POP_COREPROCESSORS_PAGE_UNSUBSCRIBEFROMTAG => true,
				POP_COREPROCESSORS_PAGE_UPVOTEPOST => true,
				POP_COREPROCESSORS_PAGE_UNDOUPVOTEPOST => true,
				POP_COREPROCESSORS_PAGE_DOWNVOTEPOST => true,
				POP_COREPROCESSORS_PAGE_UNDODOWNVOTEPOST => true,
			);
		}

		return parent::silent_document($hierarchy);
	}

	function is_appshell($hierarchy) {

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			return array(
				POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES => true,
				POP_MULTIDOMAIN_PAGE_LOADERS_INITIALIZEDOMAIN => true,
			);
		}

		return parent::is_appshell($hierarchy);
	}

	function is_functional($hierarchy) {

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			return array(
				// Comment Leo 19/03/2017: Allow Google to find these pages
				// POPTHEME_WASSUP_PAGE_ADDCONTENTFAQ => true,
				// POPTHEME_WASSUP_PAGE_ACCOUNTFAQ => true,
				POP_COREPROCESSORS_PAGE_DESCRIPTION => true,
				POP_COREPROCESSORS_PAGE_MAIN => true,
				POP_COREPROCESSORS_PAGE_SUMMARY => true,
				POP_COREPROCESSORS_PAGE_RELATEDCONTENT => true,
				POP_COREPROCESSORS_PAGE_POSTAUTHORS => true,
				POP_COREPROCESSORS_PAGE_FOLLOWERS => true,
				POP_COREPROCESSORS_PAGE_FOLLOWINGUSERS => true,
				POP_COREPROCESSORS_PAGE_SUBSCRIBERS => true,
				POP_COREPROCESSORS_PAGE_RECOMMENDEDPOSTS => true,
				POP_COREPROCESSORS_PAGE_RECOMMENDEDBY => true,
				POP_COREPROCESSORS_PAGE_UPVOTEDBY => true,
				POP_COREPROCESSORS_PAGE_DOWNVOTEDBY => true,
			);
		}

		return parent::is_functional($hierarchy);
	}

	function needs_target_id($hierarchy) {

		$ret = array();

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			return array(
				POPTHEME_WASSUP_PAGE_EDITWEBPOSTLINK => true,
				POPTHEME_WASSUP_PAGE_EDITHIGHLIGHT => true,
				POPTHEME_WASSUP_PAGE_EDITWEBPOST => true,
				POP_COREPROCESSORS_PAGE_FOLLOWUSER => true,
				POP_COREPROCESSORS_PAGE_UNFOLLOWUSER => true,
				POP_COREPROCESSORS_PAGE_RECOMMENDPOST => true,
				POP_COREPROCESSORS_PAGE_UNRECOMMENDPOST => true,
				POP_COREPROCESSORS_PAGE_SUBSCRIBETOTAG => true,
				POP_COREPROCESSORS_PAGE_UNSUBSCRIBEFROMTAG => true,
				POP_COREPROCESSORS_PAGE_UPVOTEPOST => true,
				POP_COREPROCESSORS_PAGE_UNDOUPVOTEPOST => true,
				POP_COREPROCESSORS_PAGE_DOWNVOTEPOST => true,
				POP_COREPROCESSORS_PAGE_UNDODOWNVOTEPOST => true,
			);
		}
	
		return parent::needs_target_id($hierarchy);
	}

	function store_local($hierarchy) {

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			return array(
				POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES => true,
				POP_MULTIDOMAIN_PAGE_LOADERS_INITIALIZEDOMAIN => true,
				POP_WPAPI_PAGE_CHANGEPASSWORDPROFILE => true,
			);
		}

		return parent::store_local($hierarchy);
	}

	function get_checkpoints($hierarchy) {

		$ret = array();

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			return array(
				// POP_WPAPI_PAGE_LOADERS_POSTS_FIELDS => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_NEVERCACHE),
				// POP_WPAPI_PAGE_LOADERS_USERS_FIELDS => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_NEVERCACHE),
				// POP_WPAPI_PAGE_LOADERS_COMMENTS_FIELDS => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_NEVERCACHE),
				// POP_WPAPI_PAGE_LOADERS_TAGS_FIELDS => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_NEVERCACHE),

				POP_WPAPI_PAGE_LOSTPWD => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_NOTLOGGEDIN),
				POP_WPAPI_PAGE_LOSTPWDRESET => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_NOTLOGGEDIN),

				POP_MULTIDOMAIN_PAGE_LOADERS_INITIALIZEDOMAIN => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_DOMAINVALID),//$profile_datafromserver,
				POP_COREPROCESSORS_PAGE_LOGGEDINUSERDATA => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),//$profile_datafromserver,
				POP_WPAPI_PAGE_EDITAVATAR => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),//$profile_datafromserver,
				POP_COREPROCESSORS_PAGE_MYPREFERENCES => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),//$profile_datafromserver,

				POP_WPAPI_PAGE_ADDCOMMENT => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_STATIC),
				POPTHEME_WASSUP_PAGE_ADDWEBPOSTLINK => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_STATIC),
				POPTHEME_WASSUP_PAGE_ADDHIGHLIGHT => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_STATIC),
				POPTHEME_WASSUP_PAGE_ADDWEBPOST => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_STATIC),

				POP_WPAPI_PAGE_MYCONTENT => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),
				// POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_MYRESOURCES => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),
				POPTHEME_WASSUP_PAGE_MYWEBPOSTLINKS => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),
				POPTHEME_WASSUP_PAGE_MYHIGHLIGHTS => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),
				POPTHEME_WASSUP_PAGE_MYWEBPOSTS => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),

				POP_WPAPI_PAGE_EDITPROFILE => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),
				POP_COREPROCESSORS_PAGE_MYPROFILE => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_STATIC),
				// Allow the Change Password checkpoints to be overriden. Eg: by adding only non-WSL users
				POP_WPAPI_PAGE_CHANGEPASSWORDPROFILE => apply_filters(
					'Wassup_Template_SettingsProcessor:checkpoints',
					Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_STATIC),
					$hierarchy,
					POP_WPAPI_PAGE_CHANGEPASSWORDPROFILE
				),

				POPTHEME_WASSUP_PAGE_EDITWEBPOSTLINK => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_CANEDIT),
				POPTHEME_WASSUP_PAGE_EDITHIGHLIGHT => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_CANEDIT),
				POPTHEME_WASSUP_PAGE_EDITWEBPOST => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_CANEDIT),

				POP_COREPROCESSORS_PAGE_FOLLOWUSER => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),
				POP_COREPROCESSORS_PAGE_UNFOLLOWUSER => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),
				POP_COREPROCESSORS_PAGE_RECOMMENDPOST => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),
				POP_COREPROCESSORS_PAGE_UNRECOMMENDPOST => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),
				POP_COREPROCESSORS_PAGE_SUBSCRIBETOTAG => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),
				POP_COREPROCESSORS_PAGE_UNSUBSCRIBEFROMTAG => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),
				POP_COREPROCESSORS_PAGE_UPVOTEPOST => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),
				POP_COREPROCESSORS_PAGE_UNDOUPVOTEPOST => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),
				POP_COREPROCESSORS_PAGE_DOWNVOTEPOST => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),
				POP_COREPROCESSORS_PAGE_UNDODOWNVOTEPOST => Wassup_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER),
			);
		}
	
		return parent::get_checkpoints($hierarchy);
	}

	function get_page_blockgroups($hierarchy, $include_common = true) {

		$ret = array();

		// Common BlockGroups
		if ($include_common) {

			// Default
			$pageblockgroups_allothers = array(

				POP_WPAPI_PAGE_LOGIN => GD_TEMPLATE_BLOCKGROUP_LOGIN,
				POP_MULTIDOMAIN_PAGE_LOADERS_INITIALIZEDOMAIN => GD_TEMPLATE_BLOCKGROUP_INITIALIZEDOMAIN,
				POP_COREPROCESSORS_PAGE_LOGGEDINUSERDATA => GD_TEMPLATE_BLOCKGROUP_LOGGEDINUSERDATA,
				POP_WPAPI_PAGE_ADDCOMMENT => GD_TEMPLATE_BLOCKGROUP_ADDCOMMENT,
				POP_COREPROCESSORS_PAGE_FOLLOWUSER => GD_TEMPLATE_BLOCKGROUP_FOLLOWUSER,
				POP_COREPROCESSORS_PAGE_UNFOLLOWUSER => GD_TEMPLATE_BLOCKGROUP_UNFOLLOWUSER,
				POP_COREPROCESSORS_PAGE_RECOMMENDPOST => GD_TEMPLATE_BLOCKGROUP_RECOMMENDPOST,
				POP_COREPROCESSORS_PAGE_UNRECOMMENDPOST => GD_TEMPLATE_BLOCKGROUP_UNRECOMMENDPOST,
				POP_COREPROCESSORS_PAGE_SUBSCRIBETOTAG => GD_TEMPLATE_BLOCKGROUP_SUBSCRIBETOTAG,
				POP_COREPROCESSORS_PAGE_UNSUBSCRIBEFROMTAG => GD_TEMPLATE_BLOCKGROUP_UNSUBSCRIBEFROMTAG,
				POP_COREPROCESSORS_PAGE_UPVOTEPOST => GD_TEMPLATE_BLOCKGROUP_UPVOTEPOST,
				POP_COREPROCESSORS_PAGE_UNDOUPVOTEPOST => GD_TEMPLATE_BLOCKGROUP_UNDOUPVOTEPOST,
				POP_COREPROCESSORS_PAGE_DOWNVOTEPOST => GD_TEMPLATE_BLOCKGROUP_DOWNVOTEPOST,
				POP_COREPROCESSORS_PAGE_UNDODOWNVOTEPOST => GD_TEMPLATE_BLOCKGROUP_UNDODOWNVOTEPOST,
			);
			foreach ($pageblockgroups_allothers as $page => $blockgroup) {
				$ret[$page]['blockgroups']['default'] = $blockgroup;
			}
		}

		// if ($hierarchy == GD_SETTINGS_HIERARCHY_TAG) {

		// 	$pageblockgroups = array(
		// 		POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGALLCONTENT,
		// 	);
		// 	foreach ($pageblockgroups as $page => $blockgroup) {
				
		// 		// Also Default
		// 		$ret[$page]['blockgroups']['default'] = $blockgroup;
		// 	}
		// }
		// Page or Blocks inserted in Home
		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE || $hierarchy == GD_SETTINGS_HIERARCHY_HOME) {

			// Page blocks: Home counts as a type of Page (it shows the same blocks)
			if ($hierarchy == GD_SETTINGS_HIERARCHY_HOME) {

				$pageblockgroups = array(
					POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCKGROUP_TABPANEL_HOMEALLCONTENT,
				);
				foreach ($pageblockgroups as $page => $blockgroup) {
					
					// Also Default
					$ret[$page]['blockgroups']['default'] = $blockgroup;
				}
			}
			elseif ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

				$pageblockgroups = array(
					POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCKGROUP_TABPANEL_ALLCONTENT,
				);
				foreach ($pageblockgroups as $page => $blockgroup) {
					
					// Also Default
					$ret[$page]['blockgroups']['default'] = $blockgroup;
				}
			}

			$pageblockgroups = array(
				POP_WPAPI_PAGE_SEARCHPOSTS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_SEARCHPOSTS,
				POPTHEME_WASSUP_PAGE_WEBPOSTLINKS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_LINKS,
				POPTHEME_WASSUP_PAGE_HIGHLIGHTS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_HIGHLIGHTS,
				POPTHEME_WASSUP_PAGE_WEBPOSTS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_WEBPOSTS,
				
				POP_WPAPI_PAGE_ALLUSERS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_ALLUSERS,
				POP_WPAPI_PAGE_SEARCHUSERS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_SEARCHUSERS,
				
				POP_WPAPI_PAGE_MYCONTENT => GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYCONTENT,
				POPTHEME_WASSUP_PAGE_MYWEBPOSTLINKS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYLINKS,
				POPTHEME_WASSUP_PAGE_MYHIGHLIGHTS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYHIGHLIGHTS,
				POPTHEME_WASSUP_PAGE_MYWEBPOSTS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_MYWEBPOSTS,
			);
			foreach ($pageblockgroups as $page => $blockgroup) {
				
				// Also Default
				$ret[$page]['blockgroups']['default'] = $blockgroup;
			}
		}

		// Author page blocks
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_AUTHOR) {

			$pageblockgroups = array(
				POP_COREPROCESSORS_PAGE_MAIN => GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORMAINALLCONTENT,
				POP_COREPROCESSORS_PAGE_DESCRIPTION => GD_TEMPLATE_BLOCKGROUP_AUTHORDESCRIPTION,
				POP_COREPROCESSORS_PAGE_SUMMARY => GD_TEMPLATE_BLOCKGROUP_AUTHORSUMMARY,
				POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORALLCONTENT,
				POPTHEME_WASSUP_PAGE_WEBPOSTLINKS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORLINKS,
				// POPTHEME_WASSUP_PAGE_HIGHLIGHTS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORHIGHLIGHTS,
				POPTHEME_WASSUP_PAGE_WEBPOSTS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORWEBPOSTS,
				POP_COREPROCESSORS_PAGE_FOLLOWERS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORFOLLOWERS,
				POP_COREPROCESSORS_PAGE_FOLLOWINGUSERS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORFOLLOWINGUSERS,
				POP_COREPROCESSORS_PAGE_SUBSCRIBEDTO => GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORSUBSCRIBEDTOTAGS,
				POP_COREPROCESSORS_PAGE_RECOMMENDEDPOSTS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORRECOMMENDEDPOSTS,
			);
			foreach ($pageblockgroups as $page => $blockgroup) {
				
				// Also Default
				$ret[$page]['blockgroups']['default'] = $blockgroup;
			}
		}

		// Tag page blocks
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_TAG) {

			$pageblockgroups = array(
				POP_COREPROCESSORS_PAGE_MAIN => GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGMAINALLCONTENT,
				POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGALLCONTENT,
				POPTHEME_WASSUP_PAGE_WEBPOSTLINKS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGLINKS,
				POPTHEME_WASSUP_PAGE_WEBPOSTS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGWEBPOSTS,
				POP_COREPROCESSORS_PAGE_SUBSCRIBERS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGSUBSCRIBERS,
			);
			foreach ($pageblockgroups as $page => $blockgroup) {
				
				// Also Default
				$ret[$page]['blockgroups']['default'] = $blockgroup;
			}
		}

		// Single page blocks
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_SINGLE) {

			$pageblockgroups = array(
				POP_COREPROCESSORS_PAGE_DESCRIPTION => GD_TEMPLATE_BLOCKGROUP_SINGLEPOST,
				POP_COREPROCESSORS_PAGE_RELATEDCONTENT => GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDCONTENT,
				POPTHEME_WASSUP_PAGE_HIGHLIGHTS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERELATEDHIGHLIGHTCONTENT,
				POP_COREPROCESSORS_PAGE_POSTAUTHORS => GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEAUTHORS,
				POP_COREPROCESSORS_PAGE_RECOMMENDEDBY => GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERECOMMENDEDBY,
				POP_COREPROCESSORS_PAGE_UPVOTEDBY => GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEUPVOTEDBY,
				POP_COREPROCESSORS_PAGE_DOWNVOTEDBY => GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEDOWNVOTEDBY,
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
				POPTHEME_WASSUP_PAGE_ACCOUNTFAQ => GD_TEMPLATE_BLOCK_ACCOUNTFAQ,
				POPTHEME_WASSUP_PAGE_ADDCONTENTFAQ => GD_TEMPLATE_BLOCK_ADDCONTENTFAQ,
				POP_COREPROCESSORS_PAGE_INVITENEWUSERS => GD_TEMPLATE_BLOCK_INVITENEWUSERS,

				// Addon pageSection
				POP_WPAPI_PAGE_ADDCOMMENT => GD_TEMPLATE_BLOCK_ADDCOMMENT,

				// Login/out
				POP_WPAPI_PAGE_LOGIN => GD_TEMPLATE_BLOCK_LOGIN,
				POP_WPAPI_PAGE_LOGOUT => GD_TEMPLATE_BLOCK_LOGOUT,
				POP_WPAPI_PAGE_LOSTPWD => GD_TEMPLATE_BLOCK_LOSTPWD,
				POP_WPAPI_PAGE_LOSTPWDRESET => GD_TEMPLATE_BLOCK_LOSTPWDRESET,

				// Add Content
				POPTHEME_WASSUP_PAGE_ADDCONTENT => GD_TEMPLATE_BLOCK_MENU_BODY_ADDCONTENT,
				POPTHEME_WASSUP_PAGE_ADDWEBPOSTLINK => GD_TEMPLATE_BLOCK_WEBPOSTLINK_CREATE,
				POPTHEME_WASSUP_PAGE_ADDHIGHLIGHT => GD_TEMPLATE_BLOCK_HIGHLIGHT_CREATE,
				POPTHEME_WASSUP_PAGE_ADDWEBPOST => GD_TEMPLATE_BLOCK_WEBPOST_CREATE,
				
				// Settings
				POP_COREPROCESSORS_PAGE_SETTINGS => GD_TEMPLATE_BLOCK_SETTINGS,

				// Profile
				POP_MULTIDOMAIN_PAGE_LOADERS_INITIALIZEDOMAIN => null,
				POP_COREPROCESSORS_PAGE_LOGGEDINUSERDATA => null,
				POP_WPAPI_PAGE_EDITAVATAR => GD_TEMPLATE_BLOCK_USERAVATAR_UPDATE,
				POP_WPAPI_PAGE_CHANGEPASSWORDPROFILE => GD_TEMPLATE_BLOCK_USER_CHANGEPASSWORD,
				POP_COREPROCESSORS_PAGE_MYPREFERENCES => GD_TEMPLATE_BLOCK_MYPREFERENCES,
				
				// My Content
				POPTHEME_WASSUP_PAGE_EDITWEBPOSTLINK => GD_TEMPLATE_BLOCK_WEBPOSTLINK_UPDATE,
				POPTHEME_WASSUP_PAGE_EDITHIGHLIGHT => GD_TEMPLATE_BLOCK_HIGHLIGHT_UPDATE,
				POPTHEME_WASSUP_PAGE_EDITWEBPOST => GD_TEMPLATE_BLOCK_WEBPOST_UPDATE,
				
				// Others
				POP_WPAPI_PAGE_COMMENTS => GD_TEMPLATE_BLOCK_COMMENTS_SCROLL,
			);
			foreach ($pageblocks_allothers as $page => $block) {
				$ret[$page]['blocks']['default'] = $block;
			}

			// Actions 
			$pageactions = array(
				POP_COREPROCESSORS_PAGE_INVITENEWUSERS => GD_TEMPLATE_ACTION_INVITENEWUSERS,
				POP_WPAPI_PAGE_ADDCOMMENT => GD_TEMPLATE_ACTION_ADDCOMMENT,
				POP_WPAPI_PAGE_LOGIN => GD_TEMPLATE_ACTION_LOGIN,					
				POP_WPAPI_PAGE_LOSTPWD => GD_TEMPLATE_ACTION_LOSTPWD,
				POP_WPAPI_PAGE_LOSTPWDRESET => GD_TEMPLATE_ACTION_LOSTPWDRESET,
				POP_WPAPI_PAGE_LOGOUT => GD_TEMPLATE_ACTION_LOGOUT,
				POP_WPAPI_PAGE_CHANGEPASSWORDPROFILE => GD_TEMPLATE_ACTION_USER_CHANGEPASSWORD,
				POP_MULTIDOMAIN_PAGE_LOADERS_INITIALIZEDOMAIN => null,
				POP_COREPROCESSORS_PAGE_LOGGEDINUSERDATA => null,
				POP_WPAPI_PAGE_EDITAVATAR => GD_TEMPLATE_ACTION_USERAVATAR_UPDATE,
				POP_COREPROCESSORS_PAGE_MYPREFERENCES => GD_TEMPLATE_ACTION_MYPREFERENCES,
				POPTHEME_WASSUP_PAGE_ADDWEBPOSTLINK => GD_TEMPLATE_ACTION_WEBPOSTLINK_CREATE,
				POPTHEME_WASSUP_PAGE_EDITWEBPOSTLINK => GD_TEMPLATE_ACTION_WEBPOSTLINK_UPDATE,
				POPTHEME_WASSUP_PAGE_ADDHIGHLIGHT => GD_TEMPLATE_ACTION_HIGHLIGHT_CREATE,
				POPTHEME_WASSUP_PAGE_EDITHIGHLIGHT => GD_TEMPLATE_ACTION_HIGHLIGHT_UPDATE,
				POPTHEME_WASSUP_PAGE_ADDWEBPOST => GD_TEMPLATE_ACTION_WEBPOST_CREATE,
				POPTHEME_WASSUP_PAGE_EDITWEBPOST => GD_TEMPLATE_ACTION_WEBPOST_UPDATE,
				POP_COREPROCESSORS_PAGE_SETTINGS => GD_TEMPLATE_ACTION_SETTINGS,
				POP_COREPROCESSORS_PAGE_FOLLOWUSER => GD_TEMPLATE_ACTION_FOLLOWUSER,
				POP_COREPROCESSORS_PAGE_UNFOLLOWUSER => GD_TEMPLATE_ACTION_UNFOLLOWUSER,
				POP_COREPROCESSORS_PAGE_RECOMMENDPOST => GD_TEMPLATE_ACTION_RECOMMENDPOST,
				POP_COREPROCESSORS_PAGE_UNRECOMMENDPOST => GD_TEMPLATE_ACTION_UNRECOMMENDPOST,
				POP_COREPROCESSORS_PAGE_SUBSCRIBETOTAG => GD_TEMPLATE_ACTION_SUBSCRIBETOTAG,
				POP_COREPROCESSORS_PAGE_UNSUBSCRIBEFROMTAG => GD_TEMPLATE_ACTION_UNSUBSCRIBEFROMTAG,
				POP_COREPROCESSORS_PAGE_UPVOTEPOST => GD_TEMPLATE_ACTION_UPVOTEPOST,
				POP_COREPROCESSORS_PAGE_UNDOUPVOTEPOST => GD_TEMPLATE_ACTION_UNDOUPVOTEPOST,
				POP_COREPROCESSORS_PAGE_DOWNVOTEPOST => GD_TEMPLATE_ACTION_DOWNVOTEPOST,
				POP_COREPROCESSORS_PAGE_UNDODOWNVOTEPOST => GD_TEMPLATE_ACTION_UNDODOWNVOTEPOST,
			);
			foreach ($pageactions as $page => $action) {
				$ret[$page]['action'] = $action;
			}
		}

		// Blocks in the homepage that need to access a PAGE dataload_source
		if ($hierarchy == GD_SETTINGS_HIERARCHY_HOME || $hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {
	
			$default_format_section = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_SECTION);
			$default_format_users = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_USERS);
			// $default_format_tags = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_TAGS);

			// Navigator
			$pageblocks_navigator = array(						
				POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_NAVIGATOR,
				POPTHEME_WASSUP_PAGE_WEBPOSTLINKS  => GD_TEMPLATE_BLOCK_LINKS_SCROLL_NAVIGATOR,
				POPTHEME_WASSUP_PAGE_HIGHLIGHTS  => GD_TEMPLATE_BLOCK_HIGHLIGHTS_SCROLL_NAVIGATOR,
				POPTHEME_WASSUP_PAGE_WEBPOSTS  => GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_NAVIGATOR,
				
				POP_WPAPI_PAGE_ALLUSERS => GD_TEMPLATE_BLOCK_ALLUSERS_SCROLL_NAVIGATOR,
			);
			foreach ($pageblocks_navigator as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_NAVIGATOR] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_NAVIGATOR) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}

			// Addons
			$pageblocks_addons = array(						
				POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_ADDONS,
				POPTHEME_WASSUP_PAGE_WEBPOSTLINKS  => GD_TEMPLATE_BLOCK_LINKS_SCROLL_ADDONS,
				POPTHEME_WASSUP_PAGE_HIGHLIGHTS  => GD_TEMPLATE_BLOCK_HIGHLIGHTS_SCROLL_ADDONS,
				POPTHEME_WASSUP_PAGE_WEBPOSTS  => GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_ADDONS,
				
				POP_WPAPI_PAGE_ALLUSERS => GD_TEMPLATE_BLOCK_ALLUSERS_SCROLL_ADDONS,
			);
			foreach ($pageblocks_addons as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_ADDONS] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_ADDONS) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			
			$pageblocks_carousels = array(
				POP_WPAPI_PAGE_ALLUSERS => GD_TEMPLATE_BLOCK_USERS_CAROUSEL,
			);
			foreach ($pageblocks_carousels as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_CAROUSEL] = $block;

				if ($default_format_users == GD_TEMPLATEFORMAT_CAROUSEL) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_thumbnail = array(
				POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_THUMBNAIL,
				POP_WPAPI_PAGE_SEARCHPOSTS => GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_THUMBNAIL,
				POPTHEME_WASSUP_PAGE_WEBPOSTLINKS  => GD_TEMPLATE_BLOCK_LINKS_SCROLL_THUMBNAIL,
				POPTHEME_WASSUP_PAGE_WEBPOSTS  => GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_THUMBNAIL,
			);
			foreach ($pageblocks_thumbnail as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_THUMBNAIL] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_THUMBNAIL) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_list = array(
				POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_LIST,
				POP_WPAPI_PAGE_SEARCHPOSTS => GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_LIST,
				POPTHEME_WASSUP_PAGE_WEBPOSTLINKS  => GD_TEMPLATE_BLOCK_LINKS_SCROLL_LIST,
				POPTHEME_WASSUP_PAGE_WEBPOSTS  => GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_LIST,
			);
			foreach ($pageblocks_list as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_LIST] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_LIST) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}

			// Page blocks: Home counts as a type of Page (it shows the same blocks)
			if ($hierarchy == GD_SETTINGS_HIERARCHY_HOME) {
		
				$default_format_section = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_SECTION);
				
				$pageblocks_details = array(
					POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_DETAILS,
				);
				foreach ($pageblocks_details as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_DETAILS] = $block;

					if ($default_format_section == GD_TEMPLATEFORMAT_DETAILS) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}
				$pageblocks_simpleview = array(
					POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_SIMPLEVIEW,
				);
				foreach ($pageblocks_simpleview as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_SIMPLEVIEW] = $block;

					if ($default_format_section == GD_TEMPLATEFORMAT_SIMPLEVIEW) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}
				$pageblocks_fullview = array(
					POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_FULLVIEW,
				);
				foreach ($pageblocks_fullview as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_FULLVIEW] = $block;

					if ($default_format_section == GD_TEMPLATEFORMAT_FULLVIEW) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}
				$pageblocks_thumbnail = array(
					POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_THUMBNAIL,
				);
				foreach ($pageblocks_thumbnail as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_THUMBNAIL] = $block;

					if ($default_format_section == GD_TEMPLATEFORMAT_THUMBNAIL) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}
				$pageblocks_list = array(
					POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCK_HOMEALLCONTENT_SCROLL_LIST,
				);
				foreach ($pageblocks_list as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_LIST] = $block;

					if ($default_format_section == GD_TEMPLATEFORMAT_LIST) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}
			}

			elseif ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

				$default_format_section = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_SECTION);
				$default_format_users = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_USERS);
				$default_format_tags = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_TAGS);
				$default_format_highlights = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_HIGHLIGHTS);
				$default_format_mycontent = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_MYCONTENT);
				$default_format_myhighlights = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_MYHIGHLIGHTS);
				
				$pageblocks_common = array(
					POP_MULTIDOMAIN_PAGE_EXTERNAL => GD_TEMPLATE_BLOCK_EXTERNAL,
				);
				foreach ($pageblocks_common as $page => $block) {
				
					// Also Default
					$ret[$page]['blocks']['default'] = $block;
				}

				$pageblocks_updatedata = array(
					POP_WPAPI_PAGE_LOADERS_POSTS_FIELDS => GD_TEMPLATE_BLOCK_DATAQUERY_ALLCONTENT_CONTENT_UPDATEDATA,
					POP_WPAPI_PAGE_LOADERS_USERS_FIELDS => GD_TEMPLATE_BLOCK_DATAQUERY_ALLUSERS_CONTENT_UPDATEDATA,
					POP_WPAPI_PAGE_LOADERS_COMMENTS_FIELDS => GD_TEMPLATE_BLOCK_DATAQUERY_COMMENTS_CONTENT_UPDATEDATA,
					POP_WPAPI_PAGE_LOADERS_TAGS_FIELDS => GD_TEMPLATE_BLOCK_DATAQUERY_TAGS_CONTENT_UPDATEDATA,
				);
				foreach ($pageblocks_updatedata as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_UPDATEDATA] = $block;
				
					// Also Default
					$ret[$page]['blocks']['default'] = $block;
				}

				$pageblocks_requestlayouts = array(
					POP_WPAPI_PAGE_LOADERS_POSTS_LAYOUTS => GD_TEMPLATE_BLOCK_DATAQUERY_ALLCONTENT_CONTENT_REQUESTLAYOUTS,
					POP_WPAPI_PAGE_LOADERS_USERS_LAYOUTS => GD_TEMPLATE_BLOCK_DATAQUERY_ALLUSERS_CONTENT_REQUESTLAYOUTS,
					POP_WPAPI_PAGE_LOADERS_COMMENTS_LAYOUTS => GD_TEMPLATE_BLOCK_DATAQUERY_COMMENTS_CONTENT_REQUESTLAYOUTS,
					POP_WPAPI_PAGE_LOADERS_TAGS_LAYOUTS => GD_TEMPLATE_BLOCK_DATAQUERY_TAGS_CONTENT_REQUESTLAYOUTS,
				);
				foreach ($pageblocks_requestlayouts as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_REQUESTLAYOUTS] = $block;
				
					// Also Default
					$ret[$page]['blocks']['default'] = $block;
				}

				$pageblocks_latestcounts = array(
					POP_WPAPI_PAGE_LOADERS_POSTS_LAYOUTS => GD_TEMPLATE_BLOCK_LATESTCOUNTS,
				);
				foreach ($pageblocks_latestcounts as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_LATESTCOUNT] = $block;
				}
		
				$pageblocks_typeahead = array(
					POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCK_ALLCONTENT_TYPEAHEAD,
					POP_WPAPI_PAGE_SEARCHPOSTS => GD_TEMPLATE_BLOCK_SEARCHPOSTS_TYPEAHEAD,
					POPTHEME_WASSUP_PAGE_WEBPOSTLINKS  => GD_TEMPLATE_BLOCK_LINKS_TYPEAHEAD,
					POPTHEME_WASSUP_PAGE_WEBPOSTS  => GD_TEMPLATE_BLOCK_WEBPOSTS_TYPEAHEAD,
				);
				foreach ($pageblocks_typeahead as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_TYPEAHEAD] = $block;

					if ($default_format_section == GD_TEMPLATEFORMAT_TYPEAHEAD) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}
				$pageblocks_highlighttypeahead = array(
					POPTHEME_WASSUP_PAGE_HIGHLIGHTS  => GD_TEMPLATE_BLOCK_HIGHLIGHTS_TYPEAHEAD,
				);
				foreach ($pageblocks_highlighttypeahead as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_TYPEAHEAD] = $block;

					if ($default_format_highlights == GD_TEMPLATEFORMAT_TYPEAHEAD) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}

				$pageblocks_usertypeahead = array(
					POP_WPAPI_PAGE_ALLUSERS => GD_TEMPLATE_BLOCK_ALLUSERS_TYPEAHEAD,
					POP_WPAPI_PAGE_SEARCHUSERS => GD_TEMPLATE_BLOCK_SEARCHUSERS_TYPEAHEAD,
				);
				foreach ($pageblocks_usertypeahead as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_TYPEAHEAD] = $block;

					if ($default_format_users == GD_TEMPLATEFORMAT_TYPEAHEAD) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}

				$pageblocks_mentions = array(
					POP_WPAPI_PAGE_TAGS  => GD_TEMPLATE_BLOCK_TAGS_MENTIONS,
				);
				foreach ($pageblocks_mentions as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_MENTION] = $block;

					if ($default_format_tags == GD_TEMPLATEFORMAT_MENTION) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}
				$pageblocks_usermentions = array(
					POP_WPAPI_PAGE_ALLUSERS => GD_TEMPLATE_BLOCK_ALLUSERS_MENTIONS,
					POP_WPAPI_PAGE_SEARCHUSERS => GD_TEMPLATE_BLOCK_SEARCHUSERS_MENTIONS,
				);
				foreach ($pageblocks_usermentions as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_MENTION] = $block;

					if ($default_format_users == GD_TEMPLATEFORMAT_MENTION) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}

				$pageblocks_details = array(
					POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_DETAILS,
					POP_WPAPI_PAGE_SEARCHPOSTS => GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_DETAILS,
					POPTHEME_WASSUP_PAGE_WEBPOSTLINKS  => GD_TEMPLATE_BLOCK_LINKS_SCROLL_DETAILS,
					POPTHEME_WASSUP_PAGE_WEBPOSTS  => GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_DETAILS,
				);
				foreach ($pageblocks_details as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_DETAILS] = $block;

					if ($default_format_section == GD_TEMPLATEFORMAT_DETAILS) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}
				$pageblocks_userdetails = array(
					POP_WPAPI_PAGE_ALLUSERS => GD_TEMPLATE_BLOCK_ALLUSERS_SCROLL_DETAILS,
					POP_WPAPI_PAGE_SEARCHUSERS => GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLL_DETAILS,
				);
				foreach ($pageblocks_userdetails as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_DETAILS] = $block;

					if ($default_format_users == GD_TEMPLATEFORMAT_DETAILS) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}
				$pageblocks_tagdetails = array(
					POP_WPAPI_PAGE_TAGS  => GD_TEMPLATE_BLOCK_TAGS_SCROLL_DETAILS,
					POP_WPAPI_PAGE_TRENDINGTAGS  => GD_TEMPLATE_BLOCK_TRENDINGTAGS_SCROLL_DETAILS,
				);
				foreach ($pageblocks_tagdetails as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_LIST] = $block;

					if ($default_format_tags == GD_TEMPLATEFORMAT_DETAILS) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}
				$pageblocks_simpleview = array(
					POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_SIMPLEVIEW,
					POP_WPAPI_PAGE_SEARCHPOSTS => GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_SIMPLEVIEW,
					POPTHEME_WASSUP_PAGE_WEBPOSTLINKS  => GD_TEMPLATE_BLOCK_LINKS_SCROLL_SIMPLEVIEW,
					POPTHEME_WASSUP_PAGE_WEBPOSTS  => GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_SIMPLEVIEW,
				);
				foreach ($pageblocks_simpleview as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_SIMPLEVIEW] = $block;

					if ($default_format_section == GD_TEMPLATEFORMAT_SIMPLEVIEW) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}
				$pageblocks_fullview = array(
					POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCK_ALLCONTENT_SCROLL_FULLVIEW,
					POP_WPAPI_PAGE_SEARCHPOSTS => GD_TEMPLATE_BLOCK_SEARCHPOSTS_SCROLL_FULLVIEW,
					POPTHEME_WASSUP_PAGE_WEBPOSTLINKS  => GD_TEMPLATE_BLOCK_LINKS_SCROLL_FULLVIEW,
					POPTHEME_WASSUP_PAGE_WEBPOSTS  => GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_FULLVIEW,
				);
				foreach ($pageblocks_fullview as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_FULLVIEW] = $block;

					if ($default_format_section == GD_TEMPLATEFORMAT_FULLVIEW) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}
				$pageblocks_highlightfullview = array(
					POPTHEME_WASSUP_PAGE_HIGHLIGHTS  => GD_TEMPLATE_BLOCK_HIGHLIGHTS_SCROLL_FULLVIEW,
				);
				foreach ($pageblocks_highlightfullview as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_FULLVIEW] = $block;

					if ($default_format_highlights == GD_TEMPLATEFORMAT_FULLVIEW) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}
				$pageblocks_userfullview = array(
					POP_WPAPI_PAGE_ALLUSERS => GD_TEMPLATE_BLOCK_ALLUSERS_SCROLL_FULLVIEW,
					POP_WPAPI_PAGE_SEARCHUSERS => GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLL_FULLVIEW,
				);
				foreach ($pageblocks_userfullview as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_FULLVIEW] = $block;

					if ($default_format_users == GD_TEMPLATEFORMAT_FULLVIEW) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}
				$pageblocks_highlightthumbnail = array(
					POPTHEME_WASSUP_PAGE_HIGHLIGHTS  => GD_TEMPLATE_BLOCK_HIGHLIGHTS_SCROLL_THUMBNAIL,
				);
				foreach ($pageblocks_highlightthumbnail as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_THUMBNAIL] = $block;

					if ($default_format_highlights == GD_TEMPLATEFORMAT_THUMBNAIL) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}
				$pageblocks_userthumbnail = array(
					POP_WPAPI_PAGE_ALLUSERS => GD_TEMPLATE_BLOCK_ALLUSERS_SCROLL_THUMBNAIL,
					POP_WPAPI_PAGE_SEARCHUSERS => GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLL_THUMBNAIL,
				);
				foreach ($pageblocks_userthumbnail as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_THUMBNAIL] = $block;

					if ($default_format_users == GD_TEMPLATEFORMAT_THUMBNAIL) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}
				$pageblocks_userlist = array(
					POP_WPAPI_PAGE_ALLUSERS => GD_TEMPLATE_BLOCK_ALLUSERS_SCROLL_LIST,
					POP_WPAPI_PAGE_SEARCHUSERS => GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLL_LIST,
				);
				foreach ($pageblocks_userlist as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_LIST] = $block;

					if ($default_format_users == GD_TEMPLATEFORMAT_LIST) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}
				$pageblocks_highlightlist = array(
					POPTHEME_WASSUP_PAGE_HIGHLIGHTS  => GD_TEMPLATE_BLOCK_HIGHLIGHTS_SCROLL_LIST,
				);
				foreach ($pageblocks_highlightlist as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_LIST] = $block;

					if ($default_format_highlights == GD_TEMPLATEFORMAT_LIST) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}
				$pageblocks_taglist = array(
					POP_WPAPI_PAGE_TAGS  => GD_TEMPLATE_BLOCK_TAGS_SCROLL_LIST,
					POP_WPAPI_PAGE_TRENDINGTAGS  => GD_TEMPLATE_BLOCK_TRENDINGTAGS_SCROLL_LIST,
				);
				foreach ($pageblocks_taglist as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_LIST] = $block;

					if ($default_format_tags == GD_TEMPLATEFORMAT_LIST) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}
				$pageblocks_fixedlist = array(
					POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCK_AUTHORALLCONTENT_SCROLL_FIXEDLIST,
				);
				foreach ($pageblocks_fixedlist as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_FIXEDLIST] = $block;
				}
				$pageblocks_carousels_home = array(
					POP_WPAPI_PAGE_ALLUSERS => GD_TEMPLATE_BLOCK_USERS_CAROUSEL,
				);
				foreach ($pageblocks_carousels_home as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_CAROUSEL] = $block;

					if ($default_format_users == GD_TEMPLATEFORMAT_CAROUSEL) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}
				$pageblocks_mycontent = array(
					POP_WPAPI_PAGE_MYCONTENT => GD_TEMPLATE_BLOCK_MYCONTENT_TABLE_EDIT,
					POPTHEME_WASSUP_PAGE_MYWEBPOSTLINKS => GD_TEMPLATE_BLOCK_MYLINKS_TABLE_EDIT,
					POPTHEME_WASSUP_PAGE_MYWEBPOSTS => GD_TEMPLATE_BLOCK_MYWEBPOSTS_TABLE_EDIT,
				);
				foreach ($pageblocks_mycontent as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_TABLE] = $block;

					if ($default_format_mycontent == GD_TEMPLATEFORMAT_TABLE) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}
				$pageblocks_myhighlights = array(
					POPTHEME_WASSUP_PAGE_MYHIGHLIGHTS => GD_TEMPLATE_BLOCK_MYHIGHLIGHTS_TABLE_EDIT,
				);
				foreach ($pageblocks_myhighlights as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_TABLE] = $block;

					if ($default_format_myhighlights == GD_TEMPLATEFORMAT_TABLE) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}
				$pageblocks_mycontent_simpleviewpreviews = array(
					POP_WPAPI_PAGE_MYCONTENT => GD_TEMPLATE_BLOCK_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW,
					POPTHEME_WASSUP_PAGE_MYWEBPOSTLINKS => GD_TEMPLATE_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW,
					POPTHEME_WASSUP_PAGE_MYWEBPOSTS => GD_TEMPLATE_BLOCK_MYWEBPOSTS_SCROLL_SIMPLEVIEWPREVIEW,
				);
				foreach ($pageblocks_mycontent_simpleviewpreviews as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_SIMPLEVIEW] = $block;

					if ($default_format_mycontent == GD_TEMPLATEFORMAT_SIMPLEVIEW) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}
				$pageblocks_mycontent_fullviewpreviews = array(
					POP_WPAPI_PAGE_MYCONTENT => GD_TEMPLATE_BLOCK_MYCONTENT_SCROLL_FULLVIEWPREVIEW,
					POPTHEME_WASSUP_PAGE_MYWEBPOSTLINKS => GD_TEMPLATE_BLOCK_MYLINKS_SCROLL_FULLVIEWPREVIEW,
					POPTHEME_WASSUP_PAGE_MYWEBPOSTS => GD_TEMPLATE_BLOCK_MYWEBPOSTS_SCROLL_FULLVIEWPREVIEW,
				);
				foreach ($pageblocks_mycontent_fullviewpreviews as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_FULLVIEW] = $block;

					if ($default_format_mycontent == GD_TEMPLATEFORMAT_FULLVIEW) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}
				$pageblocks_myhighlights_fullviewpreviews = array(
					POPTHEME_WASSUP_PAGE_MYHIGHLIGHTS => GD_TEMPLATE_BLOCK_MYHIGHLIGHTS_SCROLL_FULLVIEWPREVIEW,
				);
				foreach ($pageblocks_myhighlights_fullviewpreviews as $page => $block) {
					$ret[$page]['blocks'][GD_TEMPLATEFORMAT_FULLVIEW] = $block;

					if ($default_format_mycontent == GD_TEMPLATEFORMAT_FULLVIEW) {
						$ret[$page]['blocks']['default'] = $block;
					}
				}
			}
		}

		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_TAG) {
	
			$default_format_section = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_TAGSECTION);
			$default_format_tagusers = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_TAGUSERS);
			
			$pageblocks_navigator = array(
				POP_COREPROCESSORS_PAGE_MAIN => GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_NAVIGATOR,
				// POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_NAVIGATOR,
			);
			foreach ($pageblocks_navigator as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_NAVIGATOR] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_NAVIGATOR) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_details = array(
				POP_COREPROCESSORS_PAGE_MAIN => GD_TEMPLATE_BLOCK_TAGMAINALLCONTENT_SCROLL_DETAILS,
				POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_DETAILS,
				POPTHEME_WASSUP_PAGE_WEBPOSTLINKS  => GD_TEMPLATE_BLOCK_TAGLINKS_SCROLL_DETAILS,
				POPTHEME_WASSUP_PAGE_WEBPOSTS  => GD_TEMPLATE_BLOCK_TAGWEBPOSTS_SCROLL_DETAILS,
			);
			foreach ($pageblocks_details as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_DETAILS] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_DETAILS) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_userdetails = array(
				POP_COREPROCESSORS_PAGE_SUBSCRIBERS => GD_TEMPLATE_BLOCK_TAGSUBSCRIBERS_SCROLL_DETAILS,
			);
			foreach ($pageblocks_userdetails as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_DETAILS] = $block;

				if ($default_format_tagusers == GD_TEMPLATEFORMAT_DETAILS) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_simpleview = array(
				POP_COREPROCESSORS_PAGE_MAIN => GD_TEMPLATE_BLOCK_TAGMAINALLCONTENT_SCROLL_SIMPLEVIEW,
				POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_SIMPLEVIEW,
				POPTHEME_WASSUP_PAGE_WEBPOSTLINKS  => GD_TEMPLATE_BLOCK_TAGLINKS_SCROLL_SIMPLEVIEW,
				POPTHEME_WASSUP_PAGE_WEBPOSTS  => GD_TEMPLATE_BLOCK_TAGWEBPOSTS_SCROLL_SIMPLEVIEW,
			);
			foreach ($pageblocks_simpleview as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_SIMPLEVIEW] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_SIMPLEVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_fullview = array(
				POP_COREPROCESSORS_PAGE_MAIN => GD_TEMPLATE_BLOCK_TAGMAINALLCONTENT_SCROLL_FULLVIEW,
				POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_FULLVIEW,
				POPTHEME_WASSUP_PAGE_WEBPOSTLINKS  => GD_TEMPLATE_BLOCK_TAGLINKS_SCROLL_FULLVIEW,
				POPTHEME_WASSUP_PAGE_WEBPOSTS  => GD_TEMPLATE_BLOCK_TAGWEBPOSTS_SCROLL_FULLVIEW,
			);
			foreach ($pageblocks_fullview as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_FULLVIEW] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_FULLVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_userfullview = array(
				POP_COREPROCESSORS_PAGE_SUBSCRIBERS => GD_TEMPLATE_BLOCK_TAGSUBSCRIBERS_SCROLL_FULLVIEW,
			);
			foreach ($pageblocks_userfullview as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_FULLVIEW] = $block;

				if ($default_format_tagusers == GD_TEMPLATEFORMAT_FULLVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_thumbnail = array(
				POP_COREPROCESSORS_PAGE_MAIN => GD_TEMPLATE_BLOCK_TAGMAINALLCONTENT_SCROLL_THUMBNAIL,
				POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_THUMBNAIL,
				POPTHEME_WASSUP_PAGE_WEBPOSTLINKS  => GD_TEMPLATE_BLOCK_TAGLINKS_SCROLL_THUMBNAIL,
				POPTHEME_WASSUP_PAGE_WEBPOSTS  => GD_TEMPLATE_BLOCK_TAGWEBPOSTS_SCROLL_THUMBNAIL,
			);
			foreach ($pageblocks_thumbnail as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_THUMBNAIL] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_THUMBNAIL) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_userthumbnail = array(
				POP_COREPROCESSORS_PAGE_SUBSCRIBERS => GD_TEMPLATE_BLOCK_TAGSUBSCRIBERS_SCROLL_THUMBNAIL,
			);
			foreach ($pageblocks_userthumbnail as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_THUMBNAIL] = $block;

				if ($default_format_tagusers == GD_TEMPLATEFORMAT_THUMBNAIL) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_list = array(
				POP_COREPROCESSORS_PAGE_MAIN => GD_TEMPLATE_BLOCK_TAGMAINALLCONTENT_SCROLL_LIST,
				POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCK_TAGALLCONTENT_SCROLL_LIST,
				POPTHEME_WASSUP_PAGE_WEBPOSTLINKS  => GD_TEMPLATE_BLOCK_TAGLINKS_SCROLL_LIST,
				POPTHEME_WASSUP_PAGE_WEBPOSTS  => GD_TEMPLATE_BLOCK_TAGWEBPOSTS_SCROLL_LIST,
			);
			foreach ($pageblocks_list as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_LIST] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_LIST) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_userlist = array(
				POP_COREPROCESSORS_PAGE_SUBSCRIBERS => GD_TEMPLATE_BLOCK_TAGSUBSCRIBERS_SCROLL_LIST,
			);
			foreach ($pageblocks_userlist as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_LIST] = $block;

				if ($default_format_tagusers == GD_TEMPLATEFORMAT_LIST) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
		}

		// Author page blocks
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_AUTHOR) {

			$default_format_authorusers = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_AUTHORUSERS);
			$default_format_authortags = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_AUTHORTAGS);
			$default_format_section = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_SECTION);
			$default_format_users = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_USERS);
			$default_format_highlights = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_HIGHLIGHTS);
			
			// Default (Needed only for when executing http://u/leo/?tab=description&output=json&module=data)
			$pageblocks_content = array(
				POP_COREPROCESSORS_PAGE_DESCRIPTION => GD_TEMPLATE_BLOCK_AUTHOR_CONTENT,
				POP_COREPROCESSORS_PAGE_SUMMARY => GD_TEMPLATE_BLOCK_AUTHOR_SUMMARYCONTENT,
			);
			foreach ($pageblocks_content as $page => $block) {
				
				// Also Default
				$ret[$page]['blocks']['default'] = $block;
			}

			$pageblocks_typeahead = array(
				POP_WPAPI_PAGE_ALLUSERS => GD_TEMPLATE_BLOCK_AUTHORUSERS_TYPEAHEAD,
			);
			foreach ($pageblocks_typeahead as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_TYPEAHEAD] = $block;

				if ($default_format_authorusers == GD_TEMPLATEFORMAT_TYPEAHEAD) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}

			$pageblocks_details = array(
				POP_COREPROCESSORS_PAGE_MAIN => GD_TEMPLATE_BLOCK_AUTHORMAINALLCONTENT_SCROLL_DETAILS,
				POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCK_AUTHORALLCONTENT_SCROLL_DETAILS,
				POPTHEME_WASSUP_PAGE_WEBPOSTLINKS  => GD_TEMPLATE_BLOCK_AUTHORLINKS_SCROLL_DETAILS,
				POPTHEME_WASSUP_PAGE_WEBPOSTS  => GD_TEMPLATE_BLOCK_AUTHORWEBPOSTS_SCROLL_DETAILS,
				POP_COREPROCESSORS_PAGE_RECOMMENDEDPOSTS  => GD_TEMPLATE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS,
			);
			foreach ($pageblocks_details as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_DETAILS] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_DETAILS) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_userdetails = array(
				POP_COREPROCESSORS_PAGE_FOLLOWERS  => GD_TEMPLATE_BLOCK_AUTHORFOLLOWERS_SCROLL_DETAILS,
				POP_COREPROCESSORS_PAGE_FOLLOWINGUSERS  => GD_TEMPLATE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS,
			);
			foreach ($pageblocks_userdetails as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_DETAILS] = $block;

				if ($default_format_authorusers == GD_TEMPLATEFORMAT_DETAILS) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_tagdetails = array(
				POP_COREPROCESSORS_PAGE_SUBSCRIBEDTO  => GD_TEMPLATE_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS,
			);
			foreach ($pageblocks_tagdetails as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_DETAILS] = $block;

				if ($default_format_authortags == GD_TEMPLATEFORMAT_DETAILS) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_simpleview = array(
				POP_COREPROCESSORS_PAGE_MAIN => GD_TEMPLATE_BLOCK_AUTHORMAINALLCONTENT_SCROLL_SIMPLEVIEW,
				POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCK_AUTHORALLCONTENT_SCROLL_SIMPLEVIEW,
				POPTHEME_WASSUP_PAGE_WEBPOSTLINKS  => GD_TEMPLATE_BLOCK_AUTHORLINKS_SCROLL_SIMPLEVIEW,
				POPTHEME_WASSUP_PAGE_WEBPOSTS  => GD_TEMPLATE_BLOCK_AUTHORWEBPOSTS_SCROLL_SIMPLEVIEW,
				POP_COREPROCESSORS_PAGE_RECOMMENDEDPOSTS  => GD_TEMPLATE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW,
			);
			foreach ($pageblocks_simpleview as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_SIMPLEVIEW] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_SIMPLEVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_fullview = array(
				POP_COREPROCESSORS_PAGE_MAIN => GD_TEMPLATE_BLOCK_AUTHORMAINALLCONTENT_SCROLL_FULLVIEW,
				POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCK_AUTHORALLCONTENT_SCROLL_FULLVIEW,
				POPTHEME_WASSUP_PAGE_WEBPOSTLINKS  => GD_TEMPLATE_BLOCK_AUTHORLINKS_SCROLL_FULLVIEW,
				POPTHEME_WASSUP_PAGE_WEBPOSTS  => GD_TEMPLATE_BLOCK_AUTHORWEBPOSTS_SCROLL_FULLVIEW,
				POP_COREPROCESSORS_PAGE_RECOMMENDEDPOSTS  => GD_TEMPLATE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW,
			);
			foreach ($pageblocks_fullview as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_FULLVIEW] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_FULLVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_highlightfullview = array(
				POPTHEME_WASSUP_PAGE_HIGHLIGHTS  => GD_TEMPLATE_BLOCK_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW,
			);
			foreach ($pageblocks_highlightfullview as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_FULLVIEW] = $block;

				if ($default_format_highlights == GD_TEMPLATEFORMAT_FULLVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_userfullview = array(
				POP_COREPROCESSORS_PAGE_FOLLOWERS  => GD_TEMPLATE_BLOCK_AUTHORFOLLOWERS_SCROLL_FULLVIEW,
				POP_COREPROCESSORS_PAGE_FOLLOWINGUSERS  => GD_TEMPLATE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW,
			);
			foreach ($pageblocks_userfullview as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_FULLVIEW] = $block;

				if ($default_format_authorusers == GD_TEMPLATEFORMAT_FULLVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_thumbnail = array(
				POP_COREPROCESSORS_PAGE_MAIN => GD_TEMPLATE_BLOCK_AUTHORMAINALLCONTENT_SCROLL_THUMBNAIL,
				POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCK_AUTHORALLCONTENT_SCROLL_THUMBNAIL,
				POPTHEME_WASSUP_PAGE_WEBPOSTLINKS  => GD_TEMPLATE_BLOCK_AUTHORLINKS_SCROLL_THUMBNAIL,
				POPTHEME_WASSUP_PAGE_WEBPOSTS  => GD_TEMPLATE_BLOCK_AUTHORWEBPOSTS_SCROLL_THUMBNAIL,
				POP_COREPROCESSORS_PAGE_RECOMMENDEDPOSTS  => GD_TEMPLATE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL,
			);
			foreach ($pageblocks_thumbnail as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_THUMBNAIL] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_THUMBNAIL) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_highlightthumbnail = array(
				POPTHEME_WASSUP_PAGE_HIGHLIGHTS  => GD_TEMPLATE_BLOCK_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL,
			);
			foreach ($pageblocks_highlightthumbnail as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_THUMBNAIL] = $block;

				if ($default_format_highlights == GD_TEMPLATEFORMAT_THUMBNAIL) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_userthumbnail = array(
				POP_COREPROCESSORS_PAGE_FOLLOWERS  => GD_TEMPLATE_BLOCK_AUTHORFOLLOWERS_SCROLL_THUMBNAIL,
				POP_COREPROCESSORS_PAGE_FOLLOWINGUSERS  => GD_TEMPLATE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL,
			);
			foreach ($pageblocks_userthumbnail as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_THUMBNAIL] = $block;

				if ($default_format_authorusers == GD_TEMPLATEFORMAT_THUMBNAIL) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_list = array(
				POP_COREPROCESSORS_PAGE_MAIN => GD_TEMPLATE_BLOCK_AUTHORMAINALLCONTENT_SCROLL_LIST,
				POP_WPAPI_PAGE_ALLCONTENT => GD_TEMPLATE_BLOCK_AUTHORALLCONTENT_SCROLL_LIST,
				POPTHEME_WASSUP_PAGE_WEBPOSTLINKS  => GD_TEMPLATE_BLOCK_AUTHORLINKS_SCROLL_LIST,
				POPTHEME_WASSUP_PAGE_WEBPOSTS  => GD_TEMPLATE_BLOCK_AUTHORWEBPOSTS_SCROLL_LIST,
				POP_COREPROCESSORS_PAGE_RECOMMENDEDPOSTS  => GD_TEMPLATE_BLOCK_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST,
			);
			foreach ($pageblocks_list as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_LIST] = $block;

				if ($default_format_section == GD_TEMPLATEFORMAT_LIST) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_userlist = array(
				POP_COREPROCESSORS_PAGE_FOLLOWERS  => GD_TEMPLATE_BLOCK_AUTHORFOLLOWERS_SCROLL_LIST,
				POP_COREPROCESSORS_PAGE_FOLLOWINGUSERS  => GD_TEMPLATE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLL_LIST,
			);
			foreach ($pageblocks_userlist as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_LIST] = $block;

				if ($default_format_authorusers == GD_TEMPLATEFORMAT_LIST) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_taglist = array(
				POP_COREPROCESSORS_PAGE_SUBSCRIBEDTO  => GD_TEMPLATE_BLOCK_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST,
			);
			foreach ($pageblocks_taglist as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_LIST] = $block;

				if ($default_format_authortags == GD_TEMPLATEFORMAT_LIST) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_highlightlist = array(
				POPTHEME_WASSUP_PAGE_HIGHLIGHTS  => GD_TEMPLATE_BLOCK_AUTHORHIGHLIGHTS_SCROLL_LIST,
			);
			foreach ($pageblocks_highlightlist as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_LIST] = $block;

				if ($default_format_highlights == GD_TEMPLATEFORMAT_LIST) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
		}

		// Single page blocks
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_SINGLE) {

			$default_format_singlesection = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_SINGLESECTION);
			$default_format_singleusers = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_SINGLEUSERS);
			$default_format_singlehighlights = PoPTheme_Wassup_Utils::get_defaultformat_by_screen(POP_SCREEN_SINGLEHIGHLIGHTS);
			
			// Default (Needed only for when executing https://www.mesym.com/events/mesym-documentary-night-22-under-the-dome/?output=json&module=data)
			$pageblocks_content = array(
				POP_COREPROCESSORS_PAGE_DESCRIPTION => GD_TEMPLATE_BLOCK_SINGLE_CONTENT,
			);
			foreach ($pageblocks_content as $page => $block) {
				
				// Also Default
				$ret[$page]['blocks']['default'] = $block;
			}

			$pageblocks_details = array(
				POP_COREPROCESSORS_PAGE_RELATEDCONTENT => GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_DETAILS,
			);
			foreach ($pageblocks_details as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_DETAILS] = $block;

				if ($default_format_singlesection == GD_TEMPLATEFORMAT_DETAILS) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_userdetails = array(
				POP_COREPROCESSORS_PAGE_POSTAUTHORS => GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLL_DETAILS,
				POP_COREPROCESSORS_PAGE_RECOMMENDEDBY => GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_DETAILS,
				POP_COREPROCESSORS_PAGE_UPVOTEDBY => GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLL_DETAILS,
				POP_COREPROCESSORS_PAGE_DOWNVOTEDBY => GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_DETAILS,
			);
			foreach ($pageblocks_userdetails as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_DETAILS] = $block;

				if ($default_format_singleusers == GD_TEMPLATEFORMAT_DETAILS) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_simpleview = array(
				POP_COREPROCESSORS_PAGE_RELATEDCONTENT => GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW,
			);
			foreach ($pageblocks_simpleview as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_SIMPLEVIEW] = $block;

				if ($default_format_singlesection == GD_TEMPLATEFORMAT_SIMPLEVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_fullview = array(
				POP_COREPROCESSORS_PAGE_RELATEDCONTENT => GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_FULLVIEW,
			);
			foreach ($pageblocks_fullview as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_FULLVIEW] = $block;

				if ($default_format_singlesection == GD_TEMPLATEFORMAT_FULLVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_highlightfullview = array(
				POPTHEME_WASSUP_PAGE_HIGHLIGHTS => GD_TEMPLATE_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW,
			);
			foreach ($pageblocks_highlightfullview as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_FULLVIEW] = $block;

				if ($default_format_singlehighlights == GD_TEMPLATEFORMAT_FULLVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_userfullview = array(
				POP_COREPROCESSORS_PAGE_POSTAUTHORS => GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLL_FULLVIEW,
				POP_COREPROCESSORS_PAGE_RECOMMENDEDBY => GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW,
				POP_COREPROCESSORS_PAGE_UPVOTEDBY => GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLL_FULLVIEW,
				POP_COREPROCESSORS_PAGE_DOWNVOTEDBY => GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW,
			);
			foreach ($pageblocks_userfullview as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_FULLVIEW] = $block;

				if ($default_format_singleusers == GD_TEMPLATEFORMAT_FULLVIEW) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_thumbnail = array(
				POP_COREPROCESSORS_PAGE_RELATEDCONTENT => GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL,
			);
			foreach ($pageblocks_thumbnail as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_THUMBNAIL] = $block;

				if ($default_format_singlesection == GD_TEMPLATEFORMAT_THUMBNAIL) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_highlightthumbnail = array(
				POPTHEME_WASSUP_PAGE_HIGHLIGHTS => GD_TEMPLATE_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL,
			);
			foreach ($pageblocks_highlightthumbnail as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_THUMBNAIL] = $block;

				if ($default_format_singlehighlights == GD_TEMPLATEFORMAT_THUMBNAIL) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_userthumbnail = array(
				POP_COREPROCESSORS_PAGE_POSTAUTHORS => GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLL_THUMBNAIL,
				POP_COREPROCESSORS_PAGE_RECOMMENDEDBY => GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL,
				POP_COREPROCESSORS_PAGE_UPVOTEDBY => GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL,
				POP_COREPROCESSORS_PAGE_DOWNVOTEDBY => GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL,
			);
			foreach ($pageblocks_userthumbnail as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_THUMBNAIL] = $block;

				if ($default_format_singleusers == GD_TEMPLATEFORMAT_THUMBNAIL) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_list = array(
				POP_COREPROCESSORS_PAGE_RELATEDCONTENT => GD_TEMPLATE_BLOCK_SINGLERELATEDCONTENT_SCROLL_LIST,
			);
			foreach ($pageblocks_list as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_LIST] = $block;

				if ($default_format_singlesection == GD_TEMPLATEFORMAT_LIST) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_highlightlist = array(
				POPTHEME_WASSUP_PAGE_HIGHLIGHTS => GD_TEMPLATE_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST,
			);
			foreach ($pageblocks_highlightlist as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_LIST] = $block;

				if ($default_format_singlehighlights == GD_TEMPLATEFORMAT_LIST) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
			$pageblocks_userlist = array(
				POP_COREPROCESSORS_PAGE_POSTAUTHORS => GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLL_LIST,
				POP_COREPROCESSORS_PAGE_RECOMMENDEDBY => GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLL_LIST,
				POP_COREPROCESSORS_PAGE_UPVOTEDBY => GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLL_LIST,
				POP_COREPROCESSORS_PAGE_DOWNVOTEDBY => GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLL_LIST,
			);
			foreach ($pageblocks_userlist as $page => $block) {
				$ret[$page]['blocks'][GD_TEMPLATEFORMAT_LIST] = $block;

				if ($default_format_singleusers == GD_TEMPLATEFORMAT_LIST) {
					$ret[$page]['blocks']['default'] = $block;
				}
			}
		}

		// Allow Events Manager to inject the settings for the Map
		$ret = apply_filters('Wassup_Template_SettingsProcessor:page_blocks', $ret, $hierarchy, $include_common);

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_Template_SettingsProcessor();
