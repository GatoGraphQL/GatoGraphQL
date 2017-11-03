<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORM_LOCATIONPOST_UPDATE', PoP_TemplateIDUtils::get_template_definition('form-locationpost-update'));
define ('GD_TEMPLATE_FORM_LOCATIONPOSTLINK_UPDATE', PoP_TemplateIDUtils::get_template_definition('form-locationpostlink-update'));
define ('GD_TEMPLATE_FORM_LOCATIONPOST_CREATE', PoP_TemplateIDUtils::get_template_definition('form-locationpost-create'));
define ('GD_TEMPLATE_FORM_LOCATIONPOSTLINK_CREATE', PoP_TemplateIDUtils::get_template_definition('form-locationpostlink-create'));
define ('GD_TEMPLATE_FORM_STORY_UPDATE', PoP_TemplateIDUtils::get_template_definition('form-story-update'));
define ('GD_TEMPLATE_FORM_STORYLINK_UPDATE', PoP_TemplateIDUtils::get_template_definition('form-storylink-update'));
define ('GD_TEMPLATE_FORM_STORY_CREATE', PoP_TemplateIDUtils::get_template_definition('form-story-create'));
define ('GD_TEMPLATE_FORM_STORYLINK_CREATE', PoP_TemplateIDUtils::get_template_definition('form-storylink-create'));
define ('GD_TEMPLATE_FORM_ANNOUNCEMENT_UPDATE', PoP_TemplateIDUtils::get_template_definition('form-announcement-update'));
define ('GD_TEMPLATE_FORM_ANNOUNCEMENTLINK_UPDATE', PoP_TemplateIDUtils::get_template_definition('form-announcementlink-update'));
define ('GD_TEMPLATE_FORM_ANNOUNCEMENT_CREATE', PoP_TemplateIDUtils::get_template_definition('form-announcement-create'));
define ('GD_TEMPLATE_FORM_ANNOUNCEMENTLINK_CREATE', PoP_TemplateIDUtils::get_template_definition('form-announcementlink-create'));
define ('GD_TEMPLATE_FORM_DISCUSSION_UPDATE', PoP_TemplateIDUtils::get_template_definition('form-discussion-update'));
define ('GD_TEMPLATE_FORM_DISCUSSIONLINK_UPDATE', PoP_TemplateIDUtils::get_template_definition('form-discussionlink-update'));
define ('GD_TEMPLATE_FORM_DISCUSSION_CREATE', PoP_TemplateIDUtils::get_template_definition('form-discussion-create'));
define ('GD_TEMPLATE_FORM_DISCUSSIONLINK_CREATE', PoP_TemplateIDUtils::get_template_definition('form-discussionlink-create'));
define ('GD_TEMPLATE_FORM_FEATURED_UPDATE', PoP_TemplateIDUtils::get_template_definition('form-featured-update'));
define ('GD_TEMPLATE_FORM_FEATURED_CREATE', PoP_TemplateIDUtils::get_template_definition('form-featured-create'));

class GD_Custom_Template_Processor_CreateUpdatePostForms extends GD_Template_Processor_FormsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORM_LOCATIONPOST_UPDATE,
			GD_TEMPLATE_FORM_LOCATIONPOSTLINK_UPDATE,
			GD_TEMPLATE_FORM_LOCATIONPOST_CREATE,
			GD_TEMPLATE_FORM_LOCATIONPOSTLINK_CREATE,
			GD_TEMPLATE_FORM_STORY_UPDATE,
			GD_TEMPLATE_FORM_STORYLINK_UPDATE,
			GD_TEMPLATE_FORM_STORY_CREATE,
			GD_TEMPLATE_FORM_STORYLINK_CREATE,
			GD_TEMPLATE_FORM_ANNOUNCEMENT_UPDATE,
			GD_TEMPLATE_FORM_ANNOUNCEMENTLINK_UPDATE,
			GD_TEMPLATE_FORM_ANNOUNCEMENT_CREATE,
			GD_TEMPLATE_FORM_ANNOUNCEMENTLINK_CREATE,
			GD_TEMPLATE_FORM_DISCUSSION_UPDATE,
			GD_TEMPLATE_FORM_DISCUSSIONLINK_UPDATE,
			GD_TEMPLATE_FORM_DISCUSSION_CREATE,
			GD_TEMPLATE_FORM_DISCUSSIONLINK_CREATE,
			GD_TEMPLATE_FORM_FEATURED_UPDATE,
			GD_TEMPLATE_FORM_FEATURED_CREATE,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_FORM_LOCATIONPOST_UPDATE => GD_TEMPLATE_FORMINNER_LOCATIONPOST_UPDATE,
			GD_TEMPLATE_FORM_LOCATIONPOSTLINK_UPDATE => GD_TEMPLATE_FORMINNER_LOCATIONPOSTLINK_UPDATE,
			GD_TEMPLATE_FORM_LOCATIONPOST_CREATE => GD_TEMPLATE_FORMINNER_LOCATIONPOST_CREATE,
			GD_TEMPLATE_FORM_LOCATIONPOSTLINK_CREATE => GD_TEMPLATE_FORMINNER_LOCATIONPOSTLINK_CREATE,
			GD_TEMPLATE_FORM_STORY_UPDATE => GD_TEMPLATE_FORMINNER_STORY_UPDATE,
			GD_TEMPLATE_FORM_STORYLINK_UPDATE => GD_TEMPLATE_FORMINNER_STORYLINK_UPDATE,
			GD_TEMPLATE_FORM_STORY_CREATE => GD_TEMPLATE_FORMINNER_STORY_CREATE,
			GD_TEMPLATE_FORM_STORYLINK_CREATE => GD_TEMPLATE_FORMINNER_STORYLINK_CREATE,
			GD_TEMPLATE_FORM_ANNOUNCEMENT_UPDATE => GD_TEMPLATE_FORMINNER_ANNOUNCEMENT_UPDATE,
			GD_TEMPLATE_FORM_ANNOUNCEMENTLINK_UPDATE => GD_TEMPLATE_FORMINNER_ANNOUNCEMENTLINK_UPDATE,
			GD_TEMPLATE_FORM_ANNOUNCEMENT_CREATE => GD_TEMPLATE_FORMINNER_ANNOUNCEMENT_CREATE,
			GD_TEMPLATE_FORM_ANNOUNCEMENTLINK_CREATE => GD_TEMPLATE_FORMINNER_ANNOUNCEMENTLINK_CREATE,
			GD_TEMPLATE_FORM_DISCUSSION_UPDATE => GD_TEMPLATE_FORMINNER_DISCUSSION_UPDATE,
			GD_TEMPLATE_FORM_DISCUSSIONLINK_UPDATE => GD_TEMPLATE_FORMINNER_DISCUSSIONLINK_UPDATE,
			GD_TEMPLATE_FORM_DISCUSSION_CREATE => GD_TEMPLATE_FORMINNER_DISCUSSION_CREATE,
			GD_TEMPLATE_FORM_DISCUSSIONLINK_CREATE => GD_TEMPLATE_FORMINNER_DISCUSSIONLINK_CREATE,
			GD_TEMPLATE_FORM_FEATURED_UPDATE => GD_TEMPLATE_FORMINNER_FEATURED_UPDATE,
			GD_TEMPLATE_FORM_FEATURED_CREATE => GD_TEMPLATE_FORMINNER_FEATURED_CREATE,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORM_LOCATIONPOST_UPDATE:
			case GD_TEMPLATE_FORM_LOCATIONPOSTLINK_UPDATE:
			case GD_TEMPLATE_FORM_LOCATIONPOST_CREATE:
			case GD_TEMPLATE_FORM_LOCATIONPOSTLINK_CREATE:
			case GD_TEMPLATE_FORM_STORY_UPDATE:
			case GD_TEMPLATE_FORM_STORYLINK_UPDATE:
			case GD_TEMPLATE_FORM_STORY_CREATE:
			case GD_TEMPLATE_FORM_STORYLINK_CREATE:
			case GD_TEMPLATE_FORM_ANNOUNCEMENT_UPDATE:
			case GD_TEMPLATE_FORM_ANNOUNCEMENTLINK_UPDATE:
			case GD_TEMPLATE_FORM_ANNOUNCEMENT_CREATE:
			case GD_TEMPLATE_FORM_ANNOUNCEMENTLINK_CREATE:
			case GD_TEMPLATE_FORM_DISCUSSION_UPDATE:
			case GD_TEMPLATE_FORM_DISCUSSIONLINK_UPDATE:
			case GD_TEMPLATE_FORM_DISCUSSION_CREATE:
			case GD_TEMPLATE_FORM_DISCUSSIONLINK_CREATE:
			case GD_TEMPLATE_FORM_FEATURED_UPDATE:
			case GD_TEMPLATE_FORM_FEATURED_CREATE:

				// Allow to override the classes, so it can be set for the Addons pageSection without the col-sm styles, but one on top of the other
				if (!($form_row_classs = $this->get_general_att($atts, 'form-row-class'))) {
					$form_row_classs = 'row';
				}
				$this->append_att($template_id, $atts, 'class', $form_row_classs);
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_CreateUpdatePostForms();