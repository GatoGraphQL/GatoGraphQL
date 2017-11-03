<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_PAGECONTROL_LOCATIONPOST_CREATE', PoP_TemplateIDUtils::get_template_definition('block-pagecontrol-locationpost-create'));
define ('GD_TEMPLATE_BLOCK_PAGECONTROL_LOCATIONPOSTLINK_CREATE', PoP_TemplateIDUtils::get_template_definition('block-pagecontrol-locationpostlink-create'));
define ('GD_TEMPLATE_BLOCK_PAGECONTROL_STORY_CREATE', PoP_TemplateIDUtils::get_template_definition('block-pagecontrol-story-create'));
define ('GD_TEMPLATE_BLOCK_PAGECONTROL_STORYLINK_CREATE', PoP_TemplateIDUtils::get_template_definition('block-pagecontrol-storylink-create'));
define ('GD_TEMPLATE_BLOCK_PAGECONTROL_ANNOUNCEMENT_CREATE', PoP_TemplateIDUtils::get_template_definition('block-pagecontrol-announcement-create'));
define ('GD_TEMPLATE_BLOCK_PAGECONTROL_ANNOUNCEMENTLINK_CREATE', PoP_TemplateIDUtils::get_template_definition('block-pagecontrol-announcementlink-create'));
define ('GD_TEMPLATE_BLOCK_PAGECONTROL_DISCUSSION_CREATE', PoP_TemplateIDUtils::get_template_definition('block-pagecontrol-discussion-create'));
define ('GD_TEMPLATE_BLOCK_PAGECONTROL_DISCUSSIONLINK_CREATE', PoP_TemplateIDUtils::get_template_definition('block-pagecontrol-discussionlink-create'));

class GD_Custom_Template_Processor_CustomControlBlocks extends GD_Template_Processor_ControlBlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_PAGECONTROL_LOCATIONPOST_CREATE,
			GD_TEMPLATE_BLOCK_PAGECONTROL_LOCATIONPOSTLINK_CREATE,
			GD_TEMPLATE_BLOCK_PAGECONTROL_STORY_CREATE,
			GD_TEMPLATE_BLOCK_PAGECONTROL_STORYLINK_CREATE,
			GD_TEMPLATE_BLOCK_PAGECONTROL_ANNOUNCEMENT_CREATE,
			GD_TEMPLATE_BLOCK_PAGECONTROL_ANNOUNCEMENTLINK_CREATE,
			GD_TEMPLATE_BLOCK_PAGECONTROL_DISCUSSION_CREATE,
			GD_TEMPLATE_BLOCK_PAGECONTROL_DISCUSSIONLINK_CREATE,
		);
	}

	protected function get_controlgroup_top($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_PAGECONTROL_LOCATIONPOST_CREATE:
			case GD_TEMPLATE_BLOCK_PAGECONTROL_LOCATIONPOSTLINK_CREATE:
			case GD_TEMPLATE_BLOCK_PAGECONTROL_STORY_CREATE:
			case GD_TEMPLATE_BLOCK_PAGECONTROL_STORYLINK_CREATE:
			case GD_TEMPLATE_BLOCK_PAGECONTROL_ANNOUNCEMENT_CREATE:
			case GD_TEMPLATE_BLOCK_PAGECONTROL_ANNOUNCEMENTLINK_CREATE:
			case GD_TEMPLATE_BLOCK_PAGECONTROL_DISCUSSION_CREATE:
			case GD_TEMPLATE_BLOCK_PAGECONTROL_DISCUSSIONLINK_CREATE:

				return GD_TEMPLATE_CONTROLGROUP_PAGEOPTIONS;
		}

		return parent::get_controlgroup_top($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_CustomControlBlocks();