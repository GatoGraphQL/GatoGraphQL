<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * All PageSection IDs
 * ---------------------------------------------------------------------------------------------------------------*/
define ('GD_TEMPLATEID_PAGESECTIONID_TOP', 'ps-top');
define ('GD_TEMPLATEID_PAGESECTIONID_SIDE', 'ps-side');
define ('GD_TEMPLATEID_PAGESECTIONID_BACKGROUND', 'ps-background');

define ('GD_TEMPLATEID_PAGESECTIONID_CONTAINER', 'ps-container');

/**---------------------------------------------------------------------------------------------------------------
 * All IDs
 * ---------------------------------------------------------------------------------------------------------------*/
// define ('GD_TEMPLATE_ID_NOTIFICATIONSCOUNT', 'notifications-count');


/**---------------------------------------------------------------------------------------------------------------
 * All PageSections
 * ---------------------------------------------------------------------------------------------------------------*/
define ('GD_TEMPLATE_PAGESECTION_TOP', PoP_ServerUtils::get_template_definition('pagesection-top', true));
define ('GD_TEMPLATE_PAGESECTION_SIDE', PoP_ServerUtils::get_template_definition('pagesection-side', true));
define ('GD_TEMPLATE_PAGESECTION_BACKGROUND', PoP_ServerUtils::get_template_definition('pagesection-background', true));

/**---------------------------------------------------------------------------------------------------------------
 * THEMES: Print
 * ---------------------------------------------------------------------------------------------------------------*/
define ('GD_TEMPLATE_PAGESECTION_TOPSIMPLE', PoP_ServerUtils::get_template_definition('pagesection-topsimple', true));
define ('GD_TEMPLATE_PAGESECTION_TOPEMBED', PoP_ServerUtils::get_template_definition('pagesection-topembed', true));

/**---------------------------------------------------------------------------------------------------------------
 * ThemeMode/Style Hooks
 * ---------------------------------------------------------------------------------------------------------------*/
define ('POP_HOOK_PAGESECTIONS_SIDE_LOGOSIZE', 'pagesections-side-logosize');

class GD_Template_Processor_CustomPageSections extends GD_Template_Processor_PageSectionsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_PAGESECTION_TOP,
			GD_TEMPLATE_PAGESECTION_SIDE,
			GD_TEMPLATE_PAGESECTION_BACKGROUND,
			GD_TEMPLATE_PAGESECTION_TOPSIMPLE,
			GD_TEMPLATE_PAGESECTION_TOPEMBED,
		);
	}

	function get_template_source($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_TOP:

				return GD_TEMPLATESOURCE_PAGESECTION_TOP;

			case GD_TEMPLATE_PAGESECTION_SIDE:

				return GD_TEMPLATESOURCE_PAGESECTION_SIDE;

			case GD_TEMPLATE_PAGESECTION_BACKGROUND:

				return GD_TEMPLATESOURCE_PAGESECTION_BACKGROUND;

			case GD_TEMPLATE_PAGESECTION_TOPSIMPLE:
			case GD_TEMPLATE_PAGESECTION_TOPEMBED:

				return GD_TEMPLATESOURCE_PAGESECTION_TOPSIMPLE;				
		}

		return parent::get_template_source($template_id, $atts);
	}

	function get_pagesection_jsmethod($template_id, $atts) {

		$ret = parent::get_pagesection_jsmethod($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_TOP:
			case GD_TEMPLATE_PAGESECTION_SIDE:
			case GD_TEMPLATE_PAGESECTION_TOPSIMPLE:
			case GD_TEMPLATE_PAGESECTION_TOPEMBED:

				// $this->add_jsmethod($ret, 'offcanvasToggle', 'togglenav');
				$this->add_jsmethod($ret, 'offcanvasToggle', 'togglenavigator');
				$this->add_jsmethod($ret, 'offcanvasToggle', 'togglepagetabs');
				break;
		}
		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_TOP:
			case GD_TEMPLATE_PAGESECTION_SIDE:
			case GD_TEMPLATE_PAGESECTION_TOPSIMPLE:

				// Comment Leo 24/10/2016: This is the only difference between GD_TEMPLATE_PAGESECTION_TOPSIMPLE and GD_TEMPLATE_PAGESECTION_TOPEMBED:
				// the Embed does not use the Side, as such do not execute this JS below which will add class "active-side" and so create a bug
				$this->add_jsmethod($ret, 'offcanvasToggle', 'togglenav');
				break;
		}
		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_TOP:
			case GD_TEMPLATE_PAGESECTION_TOPSIMPLE:
			case GD_TEMPLATE_PAGESECTION_TOPEMBED:

				$this->add_jsmethod($ret, 'doNothing', 'void-link');
				$this->add_jsmethod($ret, 'tooltip', 'logo');
				$this->add_jsmethod($ret, 'tooltip', 'togglenav');
				$this->add_jsmethod($ret, 'tooltip', 'togglenavigator');
				$this->add_jsmethod($ret, 'tooltip', 'togglepagetabs');
				$this->add_jsmethod($ret, 'offcanvasToggle', 'togglenav-xs');
				$this->add_jsmethod($ret, 'offcanvasToggle', 'togglepagetabs-xs');

				// Save the state of the Main Navigation being open or not: open by default, by adding class "active-side"
				// to the pageSectionGroup, but if the user clicks, then it's dismissed
				if (apply_filters(
					'GD_Template_Processor_CustomPageSections:jsmethods:toggleside',
					true,
					$template_id
				)) {
					$this->add_jsmethod($ret, 'cookieToggleClass', 'togglenav');
				}
				break;
		}
		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_TOP:

				$this->add_jsmethod($ret, 'switchTargetClass', 'togglesearch-xs');
				$this->add_jsmethod($ret, 'scrollbarVertical', 'notifications');
				$this->add_jsmethod($ret, 'clearDatasetCount', 'notification-link');
				$this->add_jsmethod($ret, 'clearDatasetCountOnUserLoggedOut', 'notification-link');
				break;

			case GD_TEMPLATE_PAGESECTION_TOPSIMPLE:
			case GD_TEMPLATE_PAGESECTION_TOPEMBED:

				$this->add_jsmethod($ret, 'fullscreen', 'fullscreen');
				$this->add_jsmethod($ret, 'fullscreen', 'fullscreen-xs');
				$this->add_jsmethod($ret, 'newWindow', 'new-window');
				$this->add_jsmethod($ret, 'newWindow', 'new-window-xs');
				$this->add_jsmethod($ret, 'tooltip', 'fullscreen');
				$this->add_jsmethod($ret, 'tooltip', 'new-window');
				break;
		}
		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_SIDE:
			case GD_TEMPLATE_PAGESECTION_BACKGROUND:

				$this->add_jsmethod($ret, 'scrollbarVertical');
				break;
		}

		return $ret;
	}

	// protected function get_atts_hierarchy_initial($template_id) {

	// 	$atts = parent::get_atts_hierarchy_initial($template_id);

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_PAGESECTION_TOP:

	// 			// Set the params for the cookie
	// 			$this->merge_att($template_id, $atts, 'params', array(
	// 				'data-cookieid' => 'togglenav',
	// 				'data-cookietarget' => '#'.GD_TEMPLATEID_PAGESECTIONGROUP_ID,
	// 				'data-cookieclass' => 'active-side',
	// 				'data-togglecookiebtn' => 'self',
	// 			));
	// 			break;
	// 	}

	// 	return $atts;
	// }

	protected function get_atts_block_initial($template_id, $subcomponent) {

		$ret = parent::get_atts_block_initial($template_id, $subcomponent);
	
		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_TOP:

				switch ($subcomponent) {
					
					case GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_LIST:
						
						if (GD_TemplateManager_Utils::loading_frame()) {
							
							// Do not load the Notifications initially, since they depend on the user being logged in or not
							// Unless this page cannot be cached anyway
							// By not loading yet, we can generate the cache of the initial page							
							if (!GD_TemplateManager_Utils::page_requires_user_state()) {
								$this->add_att($subcomponent, $ret, 'content-loaded', false);
							}

							// Set the target where to update the new Notifications count
							// $this->add_att(GD_TEMPLATE_BUTTONCONTROL_LOADLATESTBLOCK, $ret, 'datasetcount-target', '#'.GD_TEMPLATE_ID_NOTIFICATIONSCOUNT);
							// $this->add_att($subcomponent, $ret, 'datasetcount-target', '#'.AAL_PoPProcessors_NotificationUtils::get_notificationcount_id());
							$this->add_att($subcomponent, $ret, 'set-datasetcount', true);
						}
						break;
				}
				break;
		}

		return $ret;
	}

	// function get_template_url_path() {

	// 	return GD_Template_CustomProcessor::get_custom_template_url_path();
	// }

	function get_id($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_TOP:
			case GD_TEMPLATE_PAGESECTION_TOPSIMPLE:
			case GD_TEMPLATE_PAGESECTION_TOPEMBED:

				return GD_TEMPLATEID_PAGESECTIONID_TOP;

			case GD_TEMPLATE_PAGESECTION_SIDE:

				return GD_TEMPLATEID_PAGESECTIONID_SIDE;

			case GD_TEMPLATE_PAGESECTION_BACKGROUND:

				return GD_TEMPLATEID_PAGESECTIONID_BACKGROUND;
		}

		return parent::get_id($template_id, $atts);
	}

	protected function get_blocks($template_id) {

		$ret = parent::get_blocks($template_id);
		$vars = GD_TemplateManager_Utils::get_vars();

		if (GD_TemplateManager_Utils::loading_frame()) {
			
			$blockgroups = $blocks_main = array();
			switch ($template_id) {

				case GD_TEMPLATE_PAGESECTION_TOP:

					$blocks_main = array(
						GD_TEMPLATE_BLOCK_MENU_TOP_ADDNEW,
						GD_TEMPLATE_BLOCK_MENU_TOPNAV_USERLOGGEDIN,
						GD_TEMPLATE_BLOCK_MENU_TOPNAV_USERNOTLOGGEDIN,
						GD_TEMPLATE_BLOCK_MENU_TOPNAV_ABOUT,
						GD_TEMPLATE_BLOCK_EVERYTHING_QUICKLINKS,
						GD_TEMPLATE_BLOCK_OURSPONSORS_TOPNAV_SCROLL,
						GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_LIST,
						// GD_TEMPLATE_BLOCK_FEATUREDCOMMUNITIES_SCROLL
					);
					break;

				case GD_TEMPLATE_PAGESECTION_SIDE:

					// $blockgroups[] = GD_TEMPLATE_BLOCKGROUP_CAROUSEL_SIDE;
					$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SIDE;
					break;

				case GD_TEMPLATE_PAGESECTION_BACKGROUND:

					if (PoPTheme_Wassup_Utils::add_background_menu()) {
						$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_BACKGROUNDMENU;
					}
					break;
			}

			// Merge all blocks with the ones set by the parent
			$this->add_blockgroups($ret, $blockgroups, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUP);
			$this->add_blocks($ret, $blocks_main, GD_TEMPLATEBLOCKSETTINGS_MAIN);
		}
		else {

			switch ($template_id) {

				// Allow for the PageSection Top to receive data for the notifications
				case GD_TEMPLATE_PAGESECTION_TOP:

					PoPTheme_Wassup_PageSectionSettingsUtils::add_page_blockunits($ret, $template_id);
					break;
			}

		}

		return $ret;
	}
	protected function get_initjs_blockbranches($template_id, $atts) {

		$ret = parent::get_initjs_blockbranches($template_id, $atts);

		$id = $this->get_frontend_id($template_id, $atts);
		$placeholder = '#'.$id.'-%s > div.pop-block';
		switch ($template_id) {
	
			case GD_TEMPLATE_PAGESECTION_TOP:

				$ret[] = sprintf($placeholder, 'menu-about');
				$ret[] = sprintf($placeholder, 'sponsors');
				$ret[] = sprintf($placeholder, 'menu-userloggedin');
				$ret[] = sprintf($placeholder, 'menu-usernotloggedin');
				$ret[] = sprintf($placeholder, 'quicklink-everything');
				$ret[] = '#'.$id.'-notifications > div > div.pop-block';
				// $ret[] = sprintf($placeholder, 'featuredcommunities');
				break;

			case GD_TEMPLATE_PAGESECTION_SIDE:

				$ret[] = sprintf($placeholder, 'side');
				break;

			case GD_TEMPLATE_PAGESECTION_BACKGROUND:

				$ret[] = sprintf($placeholder, 'menu');
				break;
		}

		return $ret;
	}

	protected function get_iohandler($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_TOPSIMPLE:
			case GD_TEMPLATE_PAGESECTION_TOPEMBED:

				return GD_DATALOAD_IOHANDLER_PAGESECTION_FRAMETOPSIMPLE;
		}

		return parent::get_iohandler($template_id);
	}

	function get_template_configuration($template_id, $atts) {
	
		global $gd_template_processor_manager, $post;

		$ret = parent::get_template_configuration($template_id, $atts);

		$togglenav = __('Toggle Navigation', 'poptheme-wassup');
		$togglehistory = __('Toggle Browsing Tabs', 'poptheme-wassup');

		switch ($template_id) {

			// Comment Leo 24/10/2016: This is the only difference between GD_TEMPLATE_PAGESECTION_TOPSIMPLE and GD_TEMPLATE_PAGESECTION_TOPEMBED:
			// the Embed does not use the Side, as such do not execute this JS below which will add class "active-side" and so create a bug
			case GD_TEMPLATE_PAGESECTION_TOP:
			case GD_TEMPLATE_PAGESECTION_TOPSIMPLE:

				$ret['offcanvas-sidenav-target'] = '#'.GD_TEMPLATEID_PAGESECTIONID_SIDE;
				$ret['offcanvas-navigator-target'] = '#'.GD_TEMPLATEID_PAGESECTIONID_NAVIGATOR;

				// Save the state of the main navigation (open or close) with a cookie
				$ret['togglenav-params'] = array(
					'data-cookieid' => 'togglenav',
					'data-cookietarget' => '#'.GD_TEMPLATEID_PAGESECTIONGROUP_ID,
					'data-cookieclass' => 'active-side',
					'data-togglecookiebtn' => 'self',
				);
				break;
		}

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_TOP:

				$title = get_bloginfo('name');

				// Generate the small and large logos
				$logo_sizes = array(
					'small',
					'large-white'
				);
				foreach ($logo_sizes as $size) {
					$logo = gd_logo($size);
					$ret['logo-'.$size] = array(
						'src' => $logo[0],
						'width' => $logo[1],
						'height' => $logo[2],
						'title' => $title
					);
				}
				
				$ret['offcanvas-pagetabs-target'] = '#'.GD_TEMPLATEID_PAGESECTIONID_PAGETABS;
				// $ret['offcanvas-sidenav-target'] = '#'.GD_TEMPLATEID_PAGESECTIONID_SIDE;
				// $ret['offcanvas-navigator-target'] = '#'.GD_TEMPLATEID_PAGESECTIONID_NAVIGATOR;
				$ret['togglesearch-target'] = '#'.GD_TEMPLATEID_PAGESECTIONID_TOP;

				// // Save the state of the main navigation (open or close) with a cookie
				// $ret['togglenav-params'] = array(
				// 	'data-cookieid' => 'togglenav',
				// 	'data-cookietarget' => '#'.GD_TEMPLATEID_PAGESECTIONGROUP_ID,
				// 	'data-cookieclass' => 'active-side',
				// 	'data-togglecookiebtn' => 'self',
				// );

				$ret['ids'] = array(
					'notifications-count' => AAL_PoPProcessors_NotificationUtils::get_notificationcount_id(),//GD_TEMPLATE_ID_NOTIFICATIONSCOUNT,
				);
				$ret['params'] = array(
					'notifications-link' => array(
						'data-datasetcount-target' => '#'.AAL_PoPProcessors_NotificationUtils::get_notificationcount_id(),
						'data-datasetcount-updatetitle' => true,
					)
				);
				
				// Allow TPPDebate to override the social media
				$ret['socialmedias'] = apply_filters(
					'GD_Template_Processor_CustomPageSections:pagesection-top:socialmedias',
					array()
				);
				$ret['icons'] = array(
					'notifications' => 'glyphicon-bell',
					'sponsors' => 'glyphicon-heart',
					'about' => 'glyphicon-info-sign',
					'settings' => 'glyphicon-cog',
					'togglenavigation' => 'glyphicon-menu-hamburger',
					'togglesearch' => 'glyphicon-option-horizontal',// 'glyphicon-search',
					'togglepagetabs' => 'glyphicon-time',
					'account' => 'glyphicon-user',
					'addcontent' => 'glyphicon-plus',
				);
				$ret['links'] = array(
					'home' => trailingslashit(home_url()),
					'useravatar' => get_permalink(POP_WPAPI_PAGE_EDITAVATAR),
					'settings' => get_permalink(POP_COREPROCESSORS_PAGE_SETTINGS),
					'login' => wp_login_url(),
					'notifications' => get_permalink(POP_AAL_PAGE_NOTIFICATIONS),
				);
				// Allow TPPDebate to override the titles
				$ret[GD_JS_TITLES/*'titles'*/] = apply_filters(
					'GD_Template_Processor_CustomPageSections:pagesection-top:titles',
					array(
						'home' => __('Home', 'poptheme-wassup'),
						'account-loading-msg' => '<i class="fa fa-circle-o-notch fa-spin fa-inverse fa-2x"></i>', //GD_CONSTANT_LOADING_SPINNERINVERSE,
						'togglesearch' => __('Toggle Search', 'poptheme-wassup'),
						'togglepagetabs' => $togglehistory,
						'togglenavigation' => $togglenav,
						// 'featuredcommunities' => __('Featured Organizations', 'poptheme-wassup'),
						// Override the footer value
						'footer' => sprintf(
							__('Powered by <a href="%s">the PoP framework</a>', 'poptheme-wassup'),
							'https://getpop.org'
						),
						'about' => __('About us', 'poptheme-wassup'),
						'myprofile' => __('My Profile', 'poptheme-wassup'),
						'useravatar' => get_the_title(POP_WPAPI_PAGE_EDITAVATAR),
						'loginaddprofile' => __('Login/Register', 'poptheme-wassup'),
						'settings' => __('Settings', 'poptheme-wassup'),
						'notifications' => __('Notifications', 'poptheme-wassup'),
						'sponsors-description' => __('<em>Many thanks to our <strong>Sponsors and Supporters</strong>:</em>', 'poptheme-wassup'),
						'sponsors' => __('Our Sponsors and Supporters', 'poptheme-wassup'),
						'viewallsponsors' => sprintf(
							__('View all <a href="%s">%s</a>', 'poptheme-wassup'),
							get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_OURSPONSORS),
							get_the_title(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_OURSPONSORS)
						),
						'viewallnotifications' => sprintf(
							__('View all <a href="%s">%s</a>', 'poptheme-wassup'),
							get_permalink(POP_AAL_PAGE_NOTIFICATIONS),
							get_the_title(POP_AAL_PAGE_NOTIFICATIONS)
						),
						'sponsorus' => sprintf(
							'<a href="%s">%s</a>',
							get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_SPONSORUS),
							get_the_title(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_SPONSORUS)
						),
						'addcontent' => __('Add content', 'poptheme-wassup'),
						'addcontent-right' => __('Add new...', 'poptheme-wassup'),
						'addcontent-left' => '<span class="glyphicon glyphicon-plus"></span>',
						'account-right' => sprintf(
							__('Your account in %s', 'poptheme-wassup'),
							$title
						),
						'account-left' => '<span class="glyphicon glyphicon-user"></span>',
					)
				);
				$ret[GD_JS_CLASSES/*'classes'*/]['useravatar'] = 'btn btn-xs btn-block btn-link';
				$ret[GD_JS_CLASSES/*'classes'*/]['socialmedia'] = 'btn btn-link btn-text-left';
				$ret[GD_JS_CLASSES/*'classes'*/]['notifications'] = 'notifications pop-waypoints-context scrollable perfect-scrollbar vertical';
				$ret[GD_JS_CLASSES/*'classes'*/]['notifications-count'] = 'badge';

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['block-menu-addnew'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_BLOCK_MENU_TOP_ADDNEW)->get_settings_id(GD_TEMPLATE_BLOCK_MENU_TOP_ADDNEW);
				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['block-menu-userloggedin'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_BLOCK_MENU_TOPNAV_USERLOGGEDIN)->get_settings_id(GD_TEMPLATE_BLOCK_MENU_TOPNAV_USERLOGGEDIN);
				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['block-menu-usernotloggedin'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_BLOCK_MENU_TOPNAV_USERNOTLOGGEDIN)->get_settings_id(GD_TEMPLATE_BLOCK_MENU_TOPNAV_USERNOTLOGGEDIN);
				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['block-menu-about'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_BLOCK_MENU_TOPNAV_ABOUT)->get_settings_id(GD_TEMPLATE_BLOCK_MENU_TOPNAV_ABOUT);
				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['block-quicklink-everything'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_BLOCK_EVERYTHING_QUICKLINKS)->get_settings_id(GD_TEMPLATE_BLOCK_EVERYTHING_QUICKLINKS);
				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['block-oursponsors'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_BLOCK_OURSPONSORS_TOPNAV_SCROLL)->get_settings_id(GD_TEMPLATE_BLOCK_OURSPONSORS_TOPNAV_SCROLL);
				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['block-notifications'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_LIST)->get_settings_id(GD_TEMPLATE_BLOCK_NOTIFICATIONS_SCROLL_LIST);
				// $ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['block-featuredcommunities'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_BLOCK_FEATUREDCOMMUNITIES_SCROLL)->get_settings_id(GD_TEMPLATE_BLOCK_FEATUREDCOMMUNITIES_SCROLL);
				break;

			case GD_TEMPLATE_PAGESECTION_TOPSIMPLE:
			case GD_TEMPLATE_PAGESECTION_TOPEMBED:

				// Generate the small logo
				$logo_sizes = array(
					'small',
				);
				foreach ($logo_sizes as $size) {
					$logo = gd_logo($size);
					$ret['logo-'.$size] = array(
						'src' => $logo[0],
						'width' => $logo[1],
						'height' => $logo[2],
					);
				}
				
				$ret['offcanvas-pagetabs-target'] = '#'.GD_TEMPLATEID_PAGESECTIONID_PAGETABS;

				$ret['targets'] = array(
					'home' => '_blank',
				);
				$ret['links'] = array(
					'home' => trailingslashit(home_url()),//get_site_url(),
				);
				$ret['icons'] = array(
					'togglenavigation' => 'glyphicon-menu-hamburger',
				);
				$ret[GD_JS_TITLES/*'titles'*/] = array(
					'togglenavigation' => $togglenav,
					'togglepagetabs' => $togglehistory,
					'home' => __('Home', 'poptheme-wassup'),
					'homenewtab' => sprintf(
						__('Open %s in a new tab', 'poptheme-wassup'), 
						get_bloginfo('name')
					),
					'fullscreen' => __('Toggle full screen', 'poptheme-wassup'),
					'newwindow' => __('Open in new window', 'poptheme-wassup'),
				);
				break;

			case GD_TEMPLATE_PAGESECTION_SIDE:

				$title = get_bloginfo('name');

				// Allow the ThemeStyle to override the logo size
				$size = apply_filters(POP_HOOK_PAGESECTIONS_SIDE_LOGOSIZE, 'large');
				$logo = gd_logo($size);
				$ret['logo-main'] = array(
					'src' => $logo[0],
					'width' => $logo[1],
					'height' => $logo[2],
					'title' => $title
				);
				
				$theme = GD_TemplateManager_Utils::get_theme();
				$ret['links'] = array(
					// trailingslashit because of qTrans: it will output link https://www.mesym.com/zh and it fails with popURLInterceptors, which expects https://www.mesym.com/zh/
					'home' => trailingslashit(home_url()),
				);
				$ret[GD_JS_TITLES/*'titles'*/] = array(
					'home' => get_bloginfo('name'),
					'togglenavigation' => $togglenav,
				);

				// $side = GD_TEMPLATE_BLOCKGROUP_CAROUSEL_SIDE;
				$side = GD_TEMPLATE_BLOCKGROUP_SIDE;
				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['blockgroup-side'] = $gd_template_processor_manager->get_processor($side)->get_settings_id($side);
				break;

			case GD_TEMPLATE_PAGESECTION_BACKGROUND:

				$ret[GD_JS_TITLES/*'titles'*/]['title'] = PoPTheme_Wassup_Utils::get_welcome_title(true);
				$ret[GD_JS_TITLES/*'titles'*/]['description'] = gd_get_website_description();
				if ($img = gd_images_background()) {
					$ret['img'] = array(
						'src' => $img
					);
				}

				if (PoPTheme_Wassup_Utils::add_background_menu()) {
					$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['blockgroup-menu'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_BLOCKGROUP_BACKGROUNDMENU)->get_settings_id(GD_TEMPLATE_BLOCKGROUP_BACKGROUNDMENU);
				}
				break;
		}
		
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomPageSections();
