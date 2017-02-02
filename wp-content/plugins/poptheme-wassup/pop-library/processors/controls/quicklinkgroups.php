<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_QUICKLINKGROUP_POST', PoP_ServerUtils::get_template_definition('quicklinkgroup-post'));
define ('GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOM', PoP_ServerUtils::get_template_definition('quicklinkgroup-postbottom'));
define ('GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOMVOLUNTEER', PoP_ServerUtils::get_template_definition('quicklinkgroup-postbottomvolunteer'));
define ('GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOMEXTENDED', PoP_ServerUtils::get_template_definition('quicklinkgroup-postbottom-extended'));
define ('GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOMEXTENDEDVOLUNTEER', PoP_ServerUtils::get_template_definition('quicklinkgroup-postbottom-extendedvolunteer'));
define ('GD_TEMPLATE_QUICKLINKGROUP_POSTEDIT', PoP_ServerUtils::get_template_definition('quicklinkgroup-postedit'));
define ('GD_TEMPLATE_QUICKLINKGROUP_ADDONSPOSTEDIT', PoP_ServerUtils::get_template_definition('quicklinkgroup-addonspostedit'));
define ('GD_TEMPLATE_QUICKLINKGROUP_HIGHLIGHTEDIT', PoP_ServerUtils::get_template_definition('quicklinkgroup-highlightedit'));
define ('GD_TEMPLATE_QUICKLINKGROUP_HIGHLIGHTCONTENT', PoP_ServerUtils::get_template_definition('quicklinkgroup-highlightcontent'));
define ('GD_TEMPLATE_QUICKLINKGROUP_USERCOMPACT', PoP_ServerUtils::get_template_definition('quicklinkgroup-usercompact'));
define ('GD_TEMPLATE_QUICKLINKGROUP_USER', PoP_ServerUtils::get_template_definition('quicklinkgroup-user'));
define ('GD_TEMPLATE_QUICKLINKGROUP_USERBOTTOM', PoP_ServerUtils::get_template_definition('quicklinkgroup-userbottom'));
define ('GD_TEMPLATE_QUICKLINKGROUP_USER_EDITMEMBERS', PoP_ServerUtils::get_template_definition('quicklinkgroup-user-editmembers'));
define ('GD_TEMPLATE_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST', PoP_ServerUtils::get_template_definition('quicklinkgroup-updownvoteundoupdownvotepost'));
define ('GD_TEMPLATE_QUICKLINKGROUP_TAG', PoP_ServerUtils::get_template_definition('quicklinkgroup-tag'));

class GD_Template_Processor_CustomQuicklinkGroups extends GD_Template_Processor_ControlGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_QUICKLINKGROUP_POST,
			GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOM,
			GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOMVOLUNTEER,
			GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOMEXTENDED,
			GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOMEXTENDEDVOLUNTEER,
			GD_TEMPLATE_QUICKLINKGROUP_POSTEDIT,
			GD_TEMPLATE_QUICKLINKGROUP_ADDONSPOSTEDIT,
			GD_TEMPLATE_QUICKLINKGROUP_HIGHLIGHTEDIT,
			GD_TEMPLATE_QUICKLINKGROUP_HIGHLIGHTCONTENT,
			GD_TEMPLATE_QUICKLINKGROUP_USER,
			GD_TEMPLATE_QUICKLINKGROUP_USERBOTTOM,
			GD_TEMPLATE_QUICKLINKGROUP_USERCOMPACT,
			GD_TEMPLATE_QUICKLINKGROUP_USER_EDITMEMBERS,
			GD_TEMPLATE_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST,
			GD_TEMPLATE_QUICKLINKGROUP_TAG,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {
				
			case GD_TEMPLATE_QUICKLINKGROUP_POST:

				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTSHARE;
				break;
				
			case GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOM:
			case GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOMEXTENDED:

				// Allow TPP Debate website to remove the Comments from the post list
				$modules = array();
				$modules[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND;
				$modules[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_COMMENTS;
				if ($template_id == GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOMEXTENDED) {
					
					$modules[] = GD_TEMPLATE_WIDGETWRAPPER_REFERENCES_LINE;
				}
				$modules = apply_filters('GD_Template_Processor_CustomQuicklinkGroups:modules', $modules, $template_id);
				$ret = array_merge(
					$ret,
					$modules
				);
				break;

			case GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOMVOLUNTEER:
			case GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOMEXTENDEDVOLUNTEER:

				// Allow TPP Debate website to remove the Comments from the post list
				$modules = array();
				$modules[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND;
				$modules[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_COMMENTS;
				// Only if the Volunteering is enabled
				if (POPTHEME_WASSUP_GF_PAGE_VOLUNTEER) {
					$modules[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTVOLUNTEER;
				}
				if ($template_id == GD_TEMPLATE_QUICKLINKGROUP_POSTBOTTOMEXTENDEDVOLUNTEER) {

					$modules[] = GD_TEMPLATE_WIDGETWRAPPER_REFERENCES_LINE;
				}
				$modules = apply_filters('GD_Template_Processor_CustomQuicklinkGroups:modules', $modules, $template_id);
				$ret = array_merge(
					$ret,
					$modules
				);
				break;

			case GD_TEMPLATE_QUICKLINKGROUP_POSTEDIT:

				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_ADDONSORMAINPOSTEDIT;//GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTEDIT;
				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTVIEW;
				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTPREVIEW;
				break;

			case GD_TEMPLATE_QUICKLINKGROUP_ADDONSPOSTEDIT:

				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_ADDONSPOSTEDIT;
				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTVIEW;
				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTPREVIEW;
				break;

			case GD_TEMPLATE_QUICKLINKGROUP_HIGHLIGHTEDIT:

				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_HIGHLIGHTEDIT;
				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_HIGHLIGHTVIEW;
				break;

			case GD_TEMPLATE_QUICKLINKGROUP_HIGHLIGHTCONTENT:

				$ret[] = GD_TEMPLATE_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST;
				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_COMMENTS;
				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTPERMALINK;
				break;

			case GD_TEMPLATE_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST:

				// Allow to not show the "Important?" label, since it's too bulky
				if (apply_filters('GD_Template_Processor_CustomQuicklinkGroups:updownvotepost:addlabel', false)) {
					$ret[] = GD_TEMPLATE_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL;
				}
				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTUPVOTEUNDOUPVOTE;
				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTDOWNVOTEUNDODOWNVOTE;
				break;

			// case GD_TEMPLATE_QUICKLINKGROUP_USER:

			// 	$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERFOLLOWUNFOLLOWUSER;
			// 	$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERSENDMESSAGE;
			// 	$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERCONTACTINFO;
			// 	$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERSHARE;
			// 	break;

			case GD_TEMPLATE_QUICKLINKGROUP_USER:

				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERSHARE;
				break;

			case GD_TEMPLATE_QUICKLINKGROUP_USERBOTTOM:

				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERFOLLOWUNFOLLOWUSER;
				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERSENDMESSAGE;
				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERCONTACTINFO;
				break;

			case GD_TEMPLATE_QUICKLINKGROUP_USERCOMPACT:

				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERSENDMESSAGE;
				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERCONTACTINFO;
				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_USERSHARE;
				break;

			case GD_TEMPLATE_QUICKLINKGROUP_USER_EDITMEMBERS:

				$ret[] = GD_URE_TEMPLATE_QUICKLINKBUTTONGROUP_USER_EDITMEMBERSHIP;
				break;
				
			case GD_TEMPLATE_QUICKLINKGROUP_TAG:

				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_TAGSHARE;
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST:
		
				$this->append_att($template_id, $atts, 'class', 'pop-functiongroup');
				break;
		}

		switch ($template_id) {

			case GD_TEMPLATE_QUICKLINKGROUP_HIGHLIGHTCONTENT:
			case GD_TEMPLATE_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST:

				// Make the level below also a 'btn-group' so it shows inline
				$downlevels = array(
					GD_TEMPLATE_QUICKLINKGROUP_HIGHLIGHTCONTENT => GD_TEMPLATE_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST,
					GD_TEMPLATE_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST => GD_TEMPLATE_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL,
				);			
				// $this->append_att($downlevels[$template_id], $atts, 'class', 'btn-group bg-warning');
				$this->append_att($downlevels[$template_id], $atts, 'class', 'btn-group');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomQuicklinkGroups();