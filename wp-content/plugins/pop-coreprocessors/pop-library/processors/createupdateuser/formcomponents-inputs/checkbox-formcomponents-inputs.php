<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// My Preferences
define ('GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_GENERAL_NEWPOST', PoP_TemplateIDUtils::get_template_definition('formcomponent-emailnotifications-general-newpost'));
define ('GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_CREATEDPOST', PoP_TemplateIDUtils::get_template_definition('formcomponent-emailnotifications-network-createdpost'));
define ('GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST', PoP_TemplateIDUtils::get_template_definition('formcomponent-emailnotifications-network-recommendedpost'));
define ('GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER', PoP_TemplateIDUtils::get_template_definition('formcomponent-emailnotifications-network-followeduser'));
define ('GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC', PoP_TemplateIDUtils::get_template_definition('formcomponent-emailnotifications-network-subscribedtotopic'));
define ('GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT', PoP_TemplateIDUtils::get_template_definition('formcomponent-emailnotifications-network-addedcomment'));
define ('GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST', PoP_TemplateIDUtils::get_template_definition('formcomponent-emailnotifications-network-updownvotedpost'));
// define ('GD_URE_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY', PoP_TemplateIDUtils::get_template_definition('ure-formcomponent-emailnotifications-network-joinscommunity'));
define ('GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDPOST', PoP_TemplateIDUtils::get_template_definition('formcomponent-emailnotifications-subscribedtopic-createdpost'));
define ('GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT', PoP_TemplateIDUtils::get_template_definition('formcomponent-emailnotifications-subscribedtopic-addedcomment'));
define ('GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_WEEKLYLATESTPOSTS', PoP_TemplateIDUtils::get_template_definition('formcomponent-emaildigests-weeklylatestposts'));
define ('GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS', PoP_TemplateIDUtils::get_template_definition('formcomponent-emaildigests-weeklyupcomingevents'));
define ('GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_DAILYNOTIFICATIONS', PoP_TemplateIDUtils::get_template_definition('formcomponent-emaildigests-dailynotifications'));
define ('GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_SPECIALPOSTS', PoP_TemplateIDUtils::get_template_definition('formcomponent-emaildigests-specialposts'));
// define ('GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_DAILYSUBSCRIBEDTOPICSACTIVITY', PoP_TemplateIDUtils::get_template_definition('formcomponent-emaildigests-dailysubscribedtopicsactivity'));

class GD_Template_Processor_UserProfileCheckboxFormComponentInputs extends GD_Template_Processor_CheckboxFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_GENERAL_NEWPOST,
			GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_CREATEDPOST,
			GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST,
			GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER,
			GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC,
			GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT,
			GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST,
			// GD_URE_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY,
			GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDPOST,
			GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT,
			GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_WEEKLYLATESTPOSTS,
			GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS,
			GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_DAILYNOTIFICATIONS,
			GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_SPECIALPOSTS,
			// GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_DAILYSUBSCRIBEDTOPICSACTIVITY,
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_GENERAL_NEWPOST:

				return __('New content is posted on the website', 'pop-coreprocessors');
			
			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_CREATEDPOST:

				return __('Created content', 'pop-coreprocessors');
			
			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST:

				return __('Recommends content', 'pop-coreprocessors');
			
			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER:

				return __('Follows another user', 'pop-coreprocessors');
			
			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC:

				return __('Subscribed to a topic', 'pop-coreprocessors');
			
			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT:

				return __('Added a comment', 'pop-coreprocessors');
			
			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST:

				return __('Up/down-voted a highlight', 'pop-coreprocessors');

			// case GD_URE_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY:

			// 	return __('Joins an organization', 'pop-coreprocessors');
			
			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDPOST:

				return __('Has new content', 'pop-coreprocessors');
			
			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT:

				return __('Has a comment added', 'pop-coreprocessors');
			
			case GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_WEEKLYLATESTPOSTS:

				return __('New content by the community (weekly)', 'pop-coreprocessors');
			
			case GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS:

				return __('Upcoming events (weekly)', 'pop-coreprocessors');
			
			case GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_DAILYNOTIFICATIONS:

				return __('My notifications (daily)', 'pop-coreprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_SPECIALPOSTS:

				return __('Special posts or announcements', 'pop-coreprocessors');
			
			// case GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_DAILYSUBSCRIBEDTOPICSACTIVITY:

			// 	return __('Activity on my subscribed topics (daily)', 'pop-coreprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);
	
		switch ($template_id) {
			
			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_GENERAL_NEWPOST:
			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_CREATEDPOST:
			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST:
			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER:
			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC:
			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT:
			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST:
			// case GD_URE_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY:
			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDPOST:
			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT:
			case GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_WEEKLYLATESTPOSTS:
			case GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS:
			case GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_DAILYNOTIFICATIONS:
			case GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_SPECIALPOSTS:
			// case GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_DAILYSUBSCRIBEDTOPICSACTIVITY:

				$fields = array(
					GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_GENERAL_NEWPOST => 'pref-emailnotif-general-newpost',
					GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_CREATEDPOST => 'pref-emailnotif-network-createdpost',
					GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST => 'pref-emailnotif-network-recommendedpost',
					GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER => 'pref-emailnotif-network-followeduser',
					GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC => 'pref-emailnotif-network-subscribedtotopic',
					GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT => 'pref-emailnotif-network-addedcomment',
					GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST => 'pref-emailnotif-network-updownvotedpost',
					// GD_URE_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY => 'pref-emailnotif-network-joinscommunity',
					GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDPOST => 'pref-emailnotif-subscribedtopic-createdpost',
					GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT => 'pref-emailnotif-subscribedtopic-addedcomment',
					GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_WEEKLYLATESTPOSTS => 'pref-emaildigests-weeklylatestposts',
					GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS => 'pref-emaildigests-weeklyupcomingevents',
					GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_DAILYNOTIFICATIONS => 'pref-emaildigests-dailynotifications',
					GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_SPECIALPOSTS => 'pref-emaildigests-specialposts',
					// GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_DAILYSUBSCRIBEDTOPICSACTIVITY => 'pref-emaildigests-dailysubscribedtopicsactivity',
				);

				$ret[] = array('key' => 'value', 'field' => $fields[$template_id]);
				break;
		}

		return $ret;
	}

	function collapsible($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_GENERAL_NEWPOST:
			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_CREATEDPOST:
			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST:
			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER:
			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC:
			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT:
			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST:
			// case GD_URE_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY:
			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDPOST:
			case GD_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT:
			case GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_WEEKLYLATESTPOSTS:
			case GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS:
			case GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_DAILYNOTIFICATIONS:
			case GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_SPECIALPOSTS:
			// case GD_TEMPLATE_FORMCOMPONENT_EMAILDIGESTS_DAILYSUBSCRIBEDTOPICSACTIVITY:

				return false;
		}

		return parent::collapsible($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_UserProfileCheckboxFormComponentInputs();