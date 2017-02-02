<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTROLGROUP_FILTER', PoP_ServerUtils::get_template_definition('controlgroup-filter'));
define ('GD_TEMPLATE_CONTROLGROUP_SHOWHIDESIDEBAR', PoP_ServerUtils::get_template_definition('controlgroup-showsidehidebar'));
define ('GD_TEMPLATE_CONTROLGROUP_CALENDAR_WIDGET', PoP_ServerUtils::get_template_definition('controlgroup-calendar-widget'));
// define ('GD_TEMPLATE_CONTROLGROUP_NAVIGATORCALENDAR', PoP_ServerUtils::get_template_definition('controlgroup-navigatorcalendar'));
define ('GD_TEMPLATE_CONTROLGROUP_COMMENTS', PoP_ServerUtils::get_template_definition('controlgroup-comments'));
define ('GD_TEMPLATE_CONTROLGROUP_TAGLIST', PoP_ServerUtils::get_template_definition('controlgroup-taglist'));
define ('GD_TEMPLATE_CONTROLGROUP_TRENDINGTAGLIST', PoP_ServerUtils::get_template_definition('controlgroup-trendingtaglist'));
define ('GD_TEMPLATE_CONTROLGROUP_POSTLIST', PoP_ServerUtils::get_template_definition('controlgroup-postlist'));
define ('GD_TEMPLATE_CONTROLGROUP_BLOCKAUTHORPOSTLIST', PoP_ServerUtils::get_template_definition('controlgroup-blockauthorpostlist'));
define ('GD_TEMPLATE_CONTROLGROUP_BLOCKPOSTLIST', PoP_ServerUtils::get_template_definition('controlgroup-blockpostlist'));
define ('GD_TEMPLATE_CONTROLGROUP_BLOCKRELOAD', PoP_ServerUtils::get_template_definition('controlgroup-blockreload'));
define ('GD_TEMPLATE_CONTROLGROUP_BLOCKLOADLATEST', PoP_ServerUtils::get_template_definition('controlgroup-blockloadlatest'));
define ('GD_TEMPLATE_CONTROLGROUP_QUICKVIEWBLOCKPOSTLIST', PoP_ServerUtils::get_template_definition('controlgroup-quickviewblockpostlist'));
define ('GD_TEMPLATE_CONTROLGROUP_SUBMENUPOSTLIST', PoP_ServerUtils::get_template_definition('controlgroup-submenupostlist'));
define ('GD_TEMPLATE_CONTROLGROUP_SUBMENUPOSTLISTMAIN', PoP_ServerUtils::get_template_definition('controlgroup-submenupostlistmain'));
define ('GD_TEMPLATE_CONTROLGROUP_USERLIST', PoP_ServerUtils::get_template_definition('controlgroup-userlist'));
define ('GD_TEMPLATE_CONTROLGROUP_BLOCKUSERLIST', PoP_ServerUtils::get_template_definition('controlgroup-blockuserlist'));
define ('GD_TEMPLATE_CONTROLGROUP_SUBMENUUSERLIST', PoP_ServerUtils::get_template_definition('controlgroup-submenuuserlist'));
define ('GD_TEMPLATE_CONTROLGROUP_SUBMENUUSERLISTMAIN', PoP_ServerUtils::get_template_definition('controlgroup-submenuuserlistmain'));
define ('GD_TEMPLATE_CONTROLGROUP_SHARE', PoP_ServerUtils::get_template_definition('controlgroup-share'));
// define ('GD_TEMPLATE_CONTROLGROUP_NAVIGATORPOSTLIST', PoP_ServerUtils::get_template_definition('controlgroup-navigatorpostlist'));
define ('GD_TEMPLATE_CONTROLGROUP_MYPOSTLIST', PoP_ServerUtils::get_template_definition('controlgroup-mypostlist'));
define ('GD_TEMPLATE_CONTROLGROUP_MYBLOCKPOSTLIST', PoP_ServerUtils::get_template_definition('controlgroup-myblockpostlist'));
define ('GD_TEMPLATE_CONTROLGROUP_MYWEBPOSTLIST', PoP_ServerUtils::get_template_definition('controlgroup-mywebpostlist'));
define ('GD_TEMPLATE_CONTROLGROUP_MYBLOCKWEBPOSTLIST', PoP_ServerUtils::get_template_definition('controlgroup-myblockwebpostlist'));
define ('GD_TEMPLATE_CONTROLGROUP_MYHIGHLIGHTLIST', PoP_ServerUtils::get_template_definition('controlgroup-myhighlightlist'));
define ('GD_TEMPLATE_CONTROLGROUP_ACCOUNT', PoP_ServerUtils::get_template_definition('controlgroup-account'));
define ('GD_TEMPLATE_CONTROLGROUP_CREATEACCOUNT', PoP_ServerUtils::get_template_definition('controlgroup-createaccount'));
define ('GD_TEMPLATE_CONTROLGROUP_CREATEPOST', PoP_ServerUtils::get_template_definition('controlgroup-createpost'));
define ('GD_TEMPLATE_CONTROLGROUP_CREATERESETPOST', PoP_ServerUtils::get_template_definition('controlgroup-createresetpost'));
define ('GD_TEMPLATE_CONTROLGROUP_EDITPOST', PoP_ServerUtils::get_template_definition('controlgroup-editpost'));
define ('GD_TEMPLATE_CONTROLGROUP_TOGGLESIDEINFO_BACK', PoP_ServerUtils::get_template_definition('controlgroup-togglesideinfo-back'));
define ('GD_TEMPLATE_CONTROLGROUP_USERPOSTINTERACTION', PoP_ServerUtils::get_template_definition('controlgroup-userpostinteraction'));
define ('GD_TEMPLATE_CONTROLGROUP_PAGEOPTIONS', PoP_ServerUtils::get_template_definition('controlgroup-pageoptions'));
define ('GD_TEMPLATE_CONTROLGROUP_PAGEWITHSIDEOPTIONS', PoP_ServerUtils::get_template_definition('controlgroup-pagewithsideoptions'));
define ('GD_TEMPLATE_CONTROLGROUP_QUICKVIEWPAGEOPTIONS', PoP_ServerUtils::get_template_definition('controlgroup-quickviewpageoptions'));
define ('GD_TEMPLATE_CONTROLGROUP_QUICKVIEWPAGEWITHSIDEOPTIONS', PoP_ServerUtils::get_template_definition('controlgroup-quickviewpagewithsideoptions'));
define ('GD_TEMPLATE_CONTROLGROUP_SINGLEOPTIONS', PoP_ServerUtils::get_template_definition('controlgroup-singleoptions'));
define ('GD_TEMPLATE_CONTROLGROUP_SINGLEOPTIONSBOTTOM', PoP_ServerUtils::get_template_definition('controlgroup-singleoptionsbottom'));
define ('GD_TEMPLATE_CONTROLGROUP_QUICKVIEWSINGLEOPTIONS', PoP_ServerUtils::get_template_definition('controlgroup-quickviewsingleoptions'));
define ('GD_TEMPLATE_CONTROLGROUP_HOMEOPTIONS', PoP_ServerUtils::get_template_definition('controlgroup-homeoptions'));
define ('GD_TEMPLATE_CONTROLGROUP_QUICKVIEWHOMEOPTIONS', PoP_ServerUtils::get_template_definition('controlgroup-quickviewhomeoptions'));
define ('GD_TEMPLATE_CONTROLGROUP_AUTHOROPTIONS', PoP_ServerUtils::get_template_definition('controlgroup-authoroptions'));
define ('GD_TEMPLATE_CONTROLGROUP_QUICKVIEWAUTHOROPTIONS', PoP_ServerUtils::get_template_definition('controlgroup-quickviewauthoroptions'));
define ('GD_TEMPLATE_CONTROLGROUP_TAGOPTIONS', PoP_ServerUtils::get_template_definition('controlgroup-tagoptions'));
define ('GD_TEMPLATE_CONTROLGROUP_QUICKVIEWTAGOPTIONS', PoP_ServerUtils::get_template_definition('controlgroup-quickviewtagoptions'));

class GD_Template_Processor_CustomControlGroups extends GD_Template_Processor_ControlGroupsBase {

	function get_templates_to_process() {
	
		return array(
			// GD_TEMPLATE_CONTROLGROUP_CAROUSEL_WIDGET,
			GD_TEMPLATE_CONTROLGROUP_FILTER,
			GD_TEMPLATE_CONTROLGROUP_SHOWHIDESIDEBAR,
			GD_TEMPLATE_CONTROLGROUP_CALENDAR_WIDGET,
			// GD_TEMPLATE_CONTROLGROUP_CALENDAR_SECTION,
			// GD_TEMPLATE_CONTROLGROUP_NAVIGATORCALENDAR,
			GD_TEMPLATE_CONTROLGROUP_COMMENTS,
			GD_TEMPLATE_CONTROLGROUP_TAGLIST,
			GD_TEMPLATE_CONTROLGROUP_TRENDINGTAGLIST,
			GD_TEMPLATE_CONTROLGROUP_POSTLIST,
			GD_TEMPLATE_CONTROLGROUP_BLOCKAUTHORPOSTLIST,
			GD_TEMPLATE_CONTROLGROUP_BLOCKPOSTLIST,
			GD_TEMPLATE_CONTROLGROUP_BLOCKRELOAD,
			GD_TEMPLATE_CONTROLGROUP_BLOCKLOADLATEST,
			GD_TEMPLATE_CONTROLGROUP_QUICKVIEWBLOCKPOSTLIST,
			GD_TEMPLATE_CONTROLGROUP_SUBMENUPOSTLIST,
			GD_TEMPLATE_CONTROLGROUP_SUBMENUPOSTLISTMAIN,
			GD_TEMPLATE_CONTROLGROUP_USERLIST,
			GD_TEMPLATE_CONTROLGROUP_BLOCKUSERLIST,
			GD_TEMPLATE_CONTROLGROUP_SUBMENUUSERLIST,
			GD_TEMPLATE_CONTROLGROUP_SUBMENUUSERLISTMAIN,
			GD_TEMPLATE_CONTROLGROUP_SHARE,
			// GD_TEMPLATE_CONTROLGROUP_NAVIGATORPOSTLIST,
			// GD_TEMPLATE_CONTROLGROUP_USERLAYOUT,
			GD_TEMPLATE_CONTROLGROUP_MYPOSTLIST,
			GD_TEMPLATE_CONTROLGROUP_MYBLOCKPOSTLIST,
			GD_TEMPLATE_CONTROLGROUP_MYWEBPOSTLIST,
			GD_TEMPLATE_CONTROLGROUP_MYBLOCKWEBPOSTLIST,
			GD_TEMPLATE_CONTROLGROUP_MYHIGHLIGHTLIST,
			GD_TEMPLATE_CONTROLGROUP_ACCOUNT,
			GD_TEMPLATE_CONTROLGROUP_CREATEACCOUNT,
			GD_TEMPLATE_CONTROLGROUP_CREATEPOST,
			GD_TEMPLATE_CONTROLGROUP_CREATERESETPOST,
			GD_TEMPLATE_CONTROLGROUP_EDITPOST,
			// GD_TEMPLATE_CONTROLGROUP_BODYSECTION,
			GD_TEMPLATE_CONTROLGROUP_TOGGLESIDEINFO_BACK,
			GD_TEMPLATE_CONTROLGROUP_USERPOSTINTERACTION,
			GD_TEMPLATE_CONTROLGROUP_PAGEOPTIONS,
			GD_TEMPLATE_CONTROLGROUP_PAGEWITHSIDEOPTIONS,
			GD_TEMPLATE_CONTROLGROUP_QUICKVIEWPAGEOPTIONS,
			GD_TEMPLATE_CONTROLGROUP_QUICKVIEWPAGEWITHSIDEOPTIONS,
			GD_TEMPLATE_CONTROLGROUP_SINGLEOPTIONS,
			GD_TEMPLATE_CONTROLGROUP_SINGLEOPTIONSBOTTOM,
			GD_TEMPLATE_CONTROLGROUP_QUICKVIEWSINGLEOPTIONS,
			GD_TEMPLATE_CONTROLGROUP_HOMEOPTIONS,
			GD_TEMPLATE_CONTROLGROUP_QUICKVIEWHOMEOPTIONS,
			GD_TEMPLATE_CONTROLGROUP_AUTHOROPTIONS,
			GD_TEMPLATE_CONTROLGROUP_QUICKVIEWAUTHOROPTIONS,
			GD_TEMPLATE_CONTROLGROUP_TAGOPTIONS,
			GD_TEMPLATE_CONTROLGROUP_QUICKVIEWTAGOPTIONS,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		global $gd_template_processor_manager;

		// $submenu = $atts['submenu'];

		switch ($template_id) {
				
			// case GD_TEMPLATE_CONTROLGROUP_CAROUSEL_WIDGET:
			
			// 	$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_CAROUSEL;
			// 	$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
			// 	break;

			case GD_TEMPLATE_CONTROLGROUP_FILTER:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				break;

			case GD_TEMPLATE_CONTROLGROUP_CALENDAR_WIDGET:
			
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_CALENDAR;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				break;
				
			// case GD_TEMPLATE_CONTROLGROUP_CALENDAR_SECTION:
			
			// 	$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_CALENDAR;
			// 	$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_OPENALL;
			// 	$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
			// 	$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_SHARE;
			// 	// $ret[] = GD_TEMPLATE_BUTTONCONTROL_NAVIGATEBLOCK;
			// 	break;

			// case GD_TEMPLATE_CONTROLGROUP_NAVIGATORCALENDAR:
			
			// 	$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_CALENDAR;
			// 	$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_OPENALL;
			// 	// $ret[] = GD_TEMPLATE_BUTTONCONTROL_NAVIGATEBLOCK;
			// 	break;

			case GD_TEMPLATE_CONTROLGROUP_SHOWHIDESIDEBAR:
			
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_SHOWHIDESIDEBAR;
				break;

			case GD_TEMPLATE_CONTROLGROUP_COMMENTS:
			
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				break;
				
			case GD_TEMPLATE_CONTROLGROUP_POSTLIST:
			case GD_TEMPLATE_CONTROLGROUP_USERLIST:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCKGROUP;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RESULTSSHARE;
				break;

			case GD_TEMPLATE_CONTROLGROUP_BLOCKAUTHORPOSTLIST:

				// Allow URE to add the Switch Organization/Organization+Members if the author is an organization
				$layouts = apply_filters(
					'GD_Template_Processor_CustomControlGroups:blockauthorpostlist:layouts',
					array(
						GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCK,
						GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER,
						GD_TEMPLATE_CONTROLBUTTONGROUP_RESULTSSHARE
					)
				);
				if ($layouts) {
					$ret = array_merge(
						$ret,
						$layouts
					);
				}
				break;

			case GD_TEMPLATE_CONTROLGROUP_BLOCKPOSTLIST:
			case GD_TEMPLATE_CONTROLGROUP_BLOCKUSERLIST:
			case GD_TEMPLATE_CONTROLGROUP_TAGLIST:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCK;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RESULTSSHARE;
				break;

			case GD_TEMPLATE_CONTROLGROUP_TRENDINGTAGLIST:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_ALLTAGSLINK;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCK;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RESULTSSHARE;
				break;

			case GD_TEMPLATE_CONTROLGROUP_BLOCKRELOAD:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCK;
				break;

			case GD_TEMPLATE_CONTROLGROUP_BLOCKLOADLATEST:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_LOADLATESTBLOCK;
				break;

			case GD_TEMPLATE_CONTROLGROUP_QUICKVIEWBLOCKPOSTLIST:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_CURRENTURL;
				break;

			case GD_TEMPLATE_CONTROLGROUP_SUBMENUPOSTLIST:
			case GD_TEMPLATE_CONTROLGROUP_SUBMENUUSERLIST:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_SUBMENU_XS;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RESULTSSHARE;
				break;

			case GD_TEMPLATE_CONTROLGROUP_SUBMENUPOSTLISTMAIN:
			case GD_TEMPLATE_CONTROLGROUP_SUBMENUUSERLISTMAIN:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RESULTSSHARE;
				break;

			case GD_TEMPLATE_CONTROLGROUP_SHARE:

				// if ($submenu) {
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_SUBMENU_XS;
				// }
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_SHARE;
				break;

			// case GD_TEMPLATE_CONTROLGROUP_NAVIGATORPOSTLIST:

			// 	$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_OPENALL;
			// 	// $ret[] = GD_TEMPLATE_BUTTONCONTROL_NAVIGATEBLOCK;
			// 	break;

			case GD_TEMPLATE_CONTROLGROUP_MYPOSTLIST:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCKGROUP;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				break;

			case GD_TEMPLATE_CONTROLGROUP_MYBLOCKPOSTLIST:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCK;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				break;

			case GD_TEMPLATE_CONTROLGROUP_MYWEBPOSTLIST:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_ADDWEBPOST;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCKGROUP;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				break;

			case GD_TEMPLATE_CONTROLGROUP_MYBLOCKWEBPOSTLIST:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_ADDWEBPOST;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCK;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				break;

			case GD_TEMPLATE_CONTROLGROUP_MYHIGHLIGHTLIST:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCK;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				break;

			case GD_TEMPLATE_CONTROLGROUP_ACCOUNT:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_SUBMENU_XS;
				$ret[] = GD_TEMPLATE_CUSTOMCONTROLBUTTONGROUP_ACCOUNTFAQ;
				break;

			case GD_TEMPLATE_CONTROLGROUP_CREATEACCOUNT:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_TOGGLEOPTIONALFIELDS;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_SUBMENU_XS;
				$ret[] = GD_TEMPLATE_CUSTOMCONTROLBUTTONGROUP_ACCOUNTFAQ;
				break;

			case GD_TEMPLATE_CONTROLGROUP_CREATEPOST:

				$ret[] = GD_TEMPLATE_CUSTOMCONTROLBUTTONGROUP_ADDCONTENTFAQ;
				break;

			case GD_TEMPLATE_CONTROLGROUP_CREATERESETPOST:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RESETEDITOR;
				$ret[] = GD_TEMPLATE_CUSTOMCONTROLBUTTONGROUP_ADDCONTENTFAQ;
				break;

			case GD_TEMPLATE_CONTROLGROUP_EDITPOST:

				$ret[] = GD_TEMPLATE_CUSTOMCONTROLBUTTONGROUP_ADDCONTENTFAQ;
				break;

			// case GD_TEMPLATE_CONTROLGROUP_BODYSECTION:

			// 	$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_BODYSECTION;
			// 	break;

			case GD_TEMPLATE_CONTROLGROUP_TOGGLESIDEINFO_BACK:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_TOGGLESIDEINFO_BACK;
				break;

			case GD_TEMPLATE_CONTROLGROUP_USERPOSTINTERACTION:

				// Allow TPPDebate to add the "What do you think about TPP?" before these layouts
				if ($layouts = apply_filters(
					'GD_Template_Processor_CustomControlGroups:userpostinteraction:layouts',
					array(
						GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT,
						GD_TEMPLATE_CONTROLBUTTONGROUP_ADDRELATEDPOST,
						GD_CUSTOM_TEMPLATE_BUTTON_HIGHLIGHT_CREATEBTN,
					),
					$template_id
				)) {
					$ret = array_merge(
						$ret,
						$layouts
					);
				}
				break;

			case GD_TEMPLATE_CONTROLGROUP_SINGLEOPTIONS:

				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_SINGLEOPTIONS;
				break;

			default :

				$buttongroups = array(
					GD_TEMPLATE_CONTROLGROUP_PAGEOPTIONS => GD_TEMPLATE_CONTROLBUTTONGROUP_PAGEOPTIONS,
					GD_TEMPLATE_CONTROLGROUP_PAGEWITHSIDEOPTIONS => GD_TEMPLATE_CONTROLBUTTONGROUP_PAGEWITHSIDEOPTIONS,
					GD_TEMPLATE_CONTROLGROUP_QUICKVIEWPAGEOPTIONS => GD_TEMPLATE_CONTROLBUTTONGROUP_QUICKVIEWPAGEOPTIONS,
					GD_TEMPLATE_CONTROLGROUP_QUICKVIEWPAGEWITHSIDEOPTIONS => GD_TEMPLATE_CONTROLBUTTONGROUP_QUICKVIEWPAGEWITHSIDEOPTIONS,
					// GD_TEMPLATE_CONTROLGROUP_SINGLEOPTIONS => GD_TEMPLATE_CONTROLBUTTONGROUP_SINGLEOPTIONS,
					GD_TEMPLATE_CONTROLGROUP_SINGLEOPTIONSBOTTOM => GD_TEMPLATE_CONTROLBUTTONGROUP_SINGLEOPTIONSBOTTOM,
					GD_TEMPLATE_CONTROLGROUP_QUICKVIEWSINGLEOPTIONS => GD_TEMPLATE_CONTROLBUTTONGROUP_QUICKVIEWSINGLEOPTIONS,
					GD_TEMPLATE_CONTROLGROUP_AUTHOROPTIONS => GD_TEMPLATE_CONTROLBUTTONGROUP_AUTHOROPTIONS,
					GD_TEMPLATE_CONTROLGROUP_QUICKVIEWAUTHOROPTIONS => GD_TEMPLATE_CONTROLBUTTONGROUP_QUICKVIEWAUTHOROPTIONS,
					GD_TEMPLATE_CONTROLGROUP_HOMEOPTIONS => GD_TEMPLATE_CONTROLBUTTONGROUP_HOMEOPTIONS,
					GD_TEMPLATE_CONTROLGROUP_QUICKVIEWHOMEOPTIONS => GD_TEMPLATE_CONTROLBUTTONGROUP_QUICKVIEWHOMEOPTIONS,
					GD_TEMPLATE_CONTROLGROUP_TAGOPTIONS => GD_TEMPLATE_CONTROLBUTTONGROUP_TAGOPTIONS,
					GD_TEMPLATE_CONTROLGROUP_QUICKVIEWTAGOPTIONS => GD_TEMPLATE_CONTROLBUTTONGROUP_QUICKVIEWTAGOPTIONS,
				);
				if ($buttongroup = $buttongroups[$template_id]) {
					$ret[] = $buttongroup;
				}
				break;
		}

		return $ret;
	}

	// function init_atts($template_id, &$atts) {

	// 	switch ($template_id) {
					
	// 		case GD_TEMPLATE_CONTROLGROUP_BLOCKPOSTLIST:
	// 		case GD_TEMPLATE_CONTROLGROUP_BLOCKUSERLIST:
				
	// 			// Do not show in the Quickview. Eg: Single Thoughts, which calling from clicking on link on content feed opens in quickview
	// 			$this->append_att($template_id, $atts, 'class', 'pop-hidden-quickview');
	// 			break;
	// 	}
		
	// 	return parent::init_atts($template_id, $atts);
	// }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomControlGroups();