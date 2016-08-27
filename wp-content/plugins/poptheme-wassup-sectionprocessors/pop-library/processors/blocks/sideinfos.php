<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_PROJECT_CREATE', PoP_ServerUtils::get_template_definition('block-emptysideinfo-project-create'));
define ('GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_PROJECTLINK_CREATE', PoP_ServerUtils::get_template_definition('block-emptysideinfo-projectlink-create'));
define ('GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_STORY_CREATE', PoP_ServerUtils::get_template_definition('block-emptysideinfo-story-create'));
define ('GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_STORYLINK_CREATE', PoP_ServerUtils::get_template_definition('block-emptysideinfo-storylink-create'));
define ('GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_ANNOUNCEMENT_CREATE', PoP_ServerUtils::get_template_definition('block-emptysideinfo-announcement-create'));
define ('GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_ANNOUNCEMENTLINK_CREATE', PoP_ServerUtils::get_template_definition('block-emptysideinfo-announcementlink-create'));
define ('GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_DISCUSSION_CREATE', PoP_ServerUtils::get_template_definition('block-emptysideinfo-discussion-create'));
define ('GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_DISCUSSIONLINK_CREATE', PoP_ServerUtils::get_template_definition('block-emptysideinfo-discussionlink-create'));

class GD_Custom_Template_Processor_CustomSideInfoBlocks extends GD_Template_Processor_CustomSideInfoBlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_PROJECT_CREATE,
			GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_PROJECTLINK_CREATE,
			GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_STORY_CREATE,
			GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_STORYLINK_CREATE,
			GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_ANNOUNCEMENT_CREATE,
			GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_ANNOUNCEMENTLINK_CREATE,
			GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_DISCUSSION_CREATE,
			GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_DISCUSSIONLINK_CREATE,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_CustomSideInfoBlocks();