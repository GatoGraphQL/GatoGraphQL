<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MULTICOMPONENT_FORM_LOCATIONPOST_RIGHTSIDE', PoP_ServerUtils::get_template_definition('multicomponent-form-locationpost-rightside'));
define ('GD_TEMPLATE_MULTICOMPONENT_FORM_LOCATIONPOSTLINK_RIGHTSIDE', PoP_ServerUtils::get_template_definition('multicomponent-form-locationpostlink-rightside'));
define ('GD_TEMPLATE_MULTICOMPONENT_FORM_STORY_RIGHTSIDE', PoP_ServerUtils::get_template_definition('multicomponent-form-story-rightside'));
define ('GD_TEMPLATE_MULTICOMPONENT_FORM_STORYLINK_RIGHTSIDE', PoP_ServerUtils::get_template_definition('multicomponent-form-storylink-rightside'));
define ('GD_TEMPLATE_MULTICOMPONENT_FORM_DISCUSSION_RIGHTSIDE', PoP_ServerUtils::get_template_definition('multicomponent-form-discussion-rightside'));
define ('GD_TEMPLATE_MULTICOMPONENT_FORM_DISCUSSIONLINK_RIGHTSIDE', PoP_ServerUtils::get_template_definition('multicomponent-form-discussionlink-rightside'));
define ('GD_TEMPLATE_MULTICOMPONENT_FORM_ANNOUNCEMENT_RIGHTSIDE', PoP_ServerUtils::get_template_definition('multicomponent-form-announcement-rightside'));
define ('GD_TEMPLATE_MULTICOMPONENT_FORM_ANNOUNCEMENTLINK_RIGHTSIDE', PoP_ServerUtils::get_template_definition('multicomponent-form-announcementlink-rightside'));
define ('GD_TEMPLATE_MULTICOMPONENT_FORM_FEATURED_RIGHTSIDE', PoP_ServerUtils::get_template_definition('multicomponent-form-featured-rightside'));

class GD_Custom_Template_Processor_FormMultipleComponents extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MULTICOMPONENT_FORM_LOCATIONPOST_RIGHTSIDE,
			GD_TEMPLATE_MULTICOMPONENT_FORM_LOCATIONPOSTLINK_RIGHTSIDE,
			GD_TEMPLATE_MULTICOMPONENT_FORM_STORY_RIGHTSIDE,
			GD_TEMPLATE_MULTICOMPONENT_FORM_STORYLINK_RIGHTSIDE,
			GD_TEMPLATE_MULTICOMPONENT_FORM_DISCUSSION_RIGHTSIDE,
			GD_TEMPLATE_MULTICOMPONENT_FORM_DISCUSSIONLINK_RIGHTSIDE,
			GD_TEMPLATE_MULTICOMPONENT_FORM_ANNOUNCEMENT_RIGHTSIDE,
			GD_TEMPLATE_MULTICOMPONENT_FORM_ANNOUNCEMENTLINK_RIGHTSIDE,
			GD_TEMPLATE_MULTICOMPONENT_FORM_FEATURED_RIGHTSIDE,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		$status = GD_CreateUpdate_Utils::moderate() ? GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_MODERATEDPUBLISH : GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_UNMODERATEDPUBLISH;

		switch ($template_id) {

			case GD_TEMPLATE_MULTICOMPONENT_FORM_LOCATIONPOST_RIGHTSIDE:
			case GD_TEMPLATE_MULTICOMPONENT_FORM_LOCATIONPOSTLINK_RIGHTSIDE:
			case GD_TEMPLATE_MULTICOMPONENT_FORM_STORY_RIGHTSIDE:
			case GD_TEMPLATE_MULTICOMPONENT_FORM_STORYLINK_RIGHTSIDE:
			case GD_TEMPLATE_MULTICOMPONENT_FORM_DISCUSSION_RIGHTSIDE:
			case GD_TEMPLATE_MULTICOMPONENT_FORM_DISCUSSIONLINK_RIGHTSIDE:
			case GD_TEMPLATE_MULTICOMPONENT_FORM_ANNOUNCEMENT_RIGHTSIDE:
			case GD_TEMPLATE_MULTICOMPONENT_FORM_ANNOUNCEMENTLINK_RIGHTSIDE:
			case GD_TEMPLATE_MULTICOMPONENT_FORM_FEATURED_RIGHTSIDE:

				$details = array(
					GD_TEMPLATE_MULTICOMPONENT_FORM_LOCATIONPOST_RIGHTSIDE => GD_TEMPLATE_WIDGET_FORM_LOCATIONPOSTDETAILS,
					GD_TEMPLATE_MULTICOMPONENT_FORM_LOCATIONPOSTLINK_RIGHTSIDE => GD_TEMPLATE_WIDGET_FORM_LOCATIONPOSTLINKDETAILS,
					GD_TEMPLATE_MULTICOMPONENT_FORM_STORY_RIGHTSIDE => GD_TEMPLATE_WIDGET_FORM_STORYDETAILS,
					GD_TEMPLATE_MULTICOMPONENT_FORM_STORYLINK_RIGHTSIDE => GD_TEMPLATE_WIDGET_FORM_STORYLINKDETAILS,
					GD_TEMPLATE_MULTICOMPONENT_FORM_DISCUSSION_RIGHTSIDE => GD_TEMPLATE_WIDGET_FORM_DISCUSSIONDETAILS,
					GD_TEMPLATE_MULTICOMPONENT_FORM_DISCUSSIONLINK_RIGHTSIDE => GD_TEMPLATE_WIDGET_FORM_DISCUSSIONLINKDETAILS,
					GD_TEMPLATE_MULTICOMPONENT_FORM_ANNOUNCEMENT_RIGHTSIDE => GD_TEMPLATE_WIDGET_FORM_ANNOUNCEMENTDETAILS,
					GD_TEMPLATE_MULTICOMPONENT_FORM_ANNOUNCEMENTLINK_RIGHTSIDE => GD_TEMPLATE_WIDGET_FORM_ANNOUNCEMENTLINKDETAILS,
					GD_TEMPLATE_MULTICOMPONENT_FORM_FEATURED_RIGHTSIDE => GD_TEMPLATE_WIDGET_FORM_FEATUREDDETAILS,
				);

				$ret[] = $details[$template_id];
				$ret[] = GD_TEMPLATE_WIDGET_FORM_FEATUREDIMAGE;
				$ret[] = GD_TEMPLATE_WIDGET_FORM_METAINFORMATION;
				$ret[] = $status;

				// // For the Announcement Only
				// // Only if the Volunteering is enabled, since it's the only component of Announcement Details
				// // if (POPTHEME_WASSUP_GF_PAGE_VOLUNTEER) {
				// $ret[] = GD_TEMPLATE_WIDGET_FORM_ANNOUNCEMENTDETAILS;
				// // }
				// $ret[] = $status;
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		global $gd_template_processor_manager;

		switch ($template_id) {

			case GD_TEMPLATE_MULTICOMPONENT_FORM_LOCATIONPOST_RIGHTSIDE:
			case GD_TEMPLATE_MULTICOMPONENT_FORM_LOCATIONPOSTLINK_RIGHTSIDE:
			case GD_TEMPLATE_MULTICOMPONENT_FORM_STORY_RIGHTSIDE:
			case GD_TEMPLATE_MULTICOMPONENT_FORM_STORYLINK_RIGHTSIDE:
			case GD_TEMPLATE_MULTICOMPONENT_FORM_DISCUSSION_RIGHTSIDE:
			case GD_TEMPLATE_MULTICOMPONENT_FORM_DISCUSSIONLINK_RIGHTSIDE:
			case GD_TEMPLATE_MULTICOMPONENT_FORM_ANNOUNCEMENT_RIGHTSIDE:
			case GD_TEMPLATE_MULTICOMPONENT_FORM_ANNOUNCEMENTLINK_RIGHTSIDE:
			case GD_TEMPLATE_MULTICOMPONENT_FORM_FEATURED_RIGHTSIDE:

				if (!($classs = $this->get_general_att($atts, 'formcomponent-publish-class'))) {
					$classs = 'alert alert-info';
				}
				$status = GD_CreateUpdate_Utils::moderate() ? GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_MODERATEDPUBLISH : GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_UNMODERATEDPUBLISH;
				$this->append_att($status, $atts, 'class', $classs);
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_FormMultipleComponents();