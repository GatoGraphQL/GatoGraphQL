<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTQUICKVIEW', PoP_TemplateIDUtils::get_template_definition('quicklinkbuttongroup-postquickview'));
// define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTVOLUNTEER', PoP_TemplateIDUtils::get_template_definition('quicklinkbuttongroup-postvolunteer'));
define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTSHARE', PoP_TemplateIDUtils::get_template_definition('quicklinkbuttongroup-postshare'));
define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTEDIT', PoP_TemplateIDUtils::get_template_definition('quicklinkbuttongroup-postedit'));
define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTVIEW', PoP_TemplateIDUtils::get_template_definition('quicklinkbuttongroup-postview'));
define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTPREVIEW', PoP_TemplateIDUtils::get_template_definition('quicklinkbuttongroup-postpreview'));
define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTPERMALINK', PoP_TemplateIDUtils::get_template_definition('quicklinkbuttongroup-postpermalink'));
// define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERSENDMESSAGE', PoP_TemplateIDUtils::get_template_definition('quicklinkbuttongroup-usersendmessage'));
define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERFOLLOWUNFOLLOWUSER', PoP_TemplateIDUtils::get_template_definition('quicklinkbuttongroup-userfollowunfollowuser'));
define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND', PoP_TemplateIDUtils::get_template_definition('quicklinkbuttongroup-postrecommendunrecommend'));
define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTUPVOTEUNDOUPVOTE', PoP_TemplateIDUtils::get_template_definition('quicklinkbuttongroup-postupvoteundoupvote'));
define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTDOWNVOTEUNDODOWNVOTE', PoP_TemplateIDUtils::get_template_definition('quicklinkbuttongroup-postdownvoteundodownvote'));
define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_TAGSUBSCRIBETOUNSUBSCRIBEFROM', PoP_TemplateIDUtils::get_template_definition('quicklinkbuttongroup-tagsubscribetounsubscribefrom'));
define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERSHARE', PoP_TemplateIDUtils::get_template_definition('quicklinkbuttongroup-usershare'));
define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERCONTACTINFO', PoP_TemplateIDUtils::get_template_definition('quicklinkbuttongroup-usercontactinfo'));
define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_COMMENTS', PoP_TemplateIDUtils::get_template_definition('quicklinkbuttongroup-comments'));
define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_COMMENTS_LABEL', PoP_TemplateIDUtils::get_template_definition('quicklinkbuttongroup-comments-label'));
define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_TAGSHARE', PoP_TemplateIDUtils::get_template_definition('quicklinkbuttongroup-tagshare'));

class GD_Template_Processor_QuicklinkButtonGroups extends GD_Template_Processor_ControlButtonGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTQUICKVIEW,
			// GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTVOLUNTEER,
			GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTSHARE,
			GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTEDIT,
			GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTVIEW,
			GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTPREVIEW,
			GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTPERMALINK,
			// GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERSENDMESSAGE,
			GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERFOLLOWUNFOLLOWUSER,
			GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND,
			GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTUPVOTEUNDOUPVOTE,
			GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTDOWNVOTEUNDODOWNVOTE,
			GD_TEMPLATE_QUICKLINKBUTTONGROUP_TAGSUBSCRIBETOUNSUBSCRIBEFROM,
			GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERSHARE,
			GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERCONTACTINFO,
			GD_TEMPLATE_QUICKLINKBUTTONGROUP_COMMENTS,
			GD_TEMPLATE_QUICKLINKBUTTONGROUP_COMMENTS_LABEL,
			GD_TEMPLATE_QUICKLINKBUTTONGROUP_TAGSHARE,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		switch ($template_id) {
		
			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTQUICKVIEW:

				$ret[] = GD_TEMPLATE_BUTTON_QUICKVIEW_PREVIEWDROPDOWN;
				break;

			// case GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTVOLUNTEER:

			// 	$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY;
			// 	break;

			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTSHARE:

				$ret[] = GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_POSTSHARE;
				break;

			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTEDIT:

				$ret[] = GD_TEMPLATE_BUTTON_POSTEDIT;
				break;

			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTVIEW:

				$ret[] = GD_TEMPLATE_BUTTONWRAPPER_POSTVIEW;
				break;

			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTPREVIEW:

				$ret[] = GD_TEMPLATE_BUTTONWRAPPER_POSTPREVIEW;
				break;

			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTPERMALINK:

				$ret[] = GD_TEMPLATE_BUTTONWRAPPER_POSTPERMALINK;
				break;

			// case GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERSENDMESSAGE:

			// 	$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_PREVIEW;
			// 	break;

			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERFOLLOWUNFOLLOWUSER:

				$ret[] = GD_TEMPLATE_BUTTON_FOLLOWUSER_PREVIEW;
				$ret[] = GD_TEMPLATE_BUTTON_UNFOLLOWUSER_PREVIEW;
				break;

			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND:

				$ret[] = GD_TEMPLATE_BUTTON_RECOMMENDPOST_PREVIEW;
				$ret[] = GD_TEMPLATE_BUTTON_UNRECOMMENDPOST_PREVIEW;
				break;

			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTUPVOTEUNDOUPVOTE:

				$ret[] = GD_TEMPLATE_BUTTON_UPVOTEPOST_PREVIEW;
				$ret[] = GD_TEMPLATE_BUTTON_UNDOUPVOTEPOST_PREVIEW;
				break;

			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTDOWNVOTEUNDODOWNVOTE:

				$ret[] = GD_TEMPLATE_BUTTON_DOWNVOTEPOST_PREVIEW;
				$ret[] = GD_TEMPLATE_BUTTON_UNDODOWNVOTEPOST_PREVIEW;
				break;

			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_TAGSUBSCRIBETOUNSUBSCRIBEFROM:

				$ret[] = GD_TEMPLATE_BUTTON_SUBSCRIBETOTAG_PREVIEW;
				$ret[] = GD_TEMPLATE_BUTTON_UNSUBSCRIBEFROMTAG_PREVIEW;
				break;

			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERSHARE:

				$ret[] = GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERSHARE;
				break;

			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERCONTACTINFO:

				$ret[] = GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_USERCONTACTINFO;
				break;

			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_COMMENTS:

				$ret[] = GD_TEMPLATE_BUTTON_POSTCOMMENTS;
				break;

			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_COMMENTS_LABEL:

				$ret[] = GD_TEMPLATE_BUTTON_POSTCOMMENTS_LABEL;
				break;

			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_TAGSHARE:

				$ret[] = GD_TEMPLATE_DROPDOWNBUTTONQUICKLINK_TAGSHARE;
				break;
		}
		
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_QuicklinkButtonGroups();