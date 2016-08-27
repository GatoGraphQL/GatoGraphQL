<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_ACTION_PROJECT_CREATE', PoP_ServerUtils::get_template_definition('action-project-create'));
define ('GD_TEMPLATE_ACTION_PROJECTLINK_CREATE', PoP_ServerUtils::get_template_definition('action-projectlink-create'));
define ('GD_TEMPLATE_ACTION_PROJECT_UPDATE', PoP_ServerUtils::get_template_definition('action-project-update'));
define ('GD_TEMPLATE_ACTION_PROJECTLINK_UPDATE', PoP_ServerUtils::get_template_definition('action-projectlink-update'));
define ('GD_TEMPLATE_ACTION_STORY_CREATE', PoP_ServerUtils::get_template_definition('action-story-create'));
define ('GD_TEMPLATE_ACTION_STORYLINK_CREATE', PoP_ServerUtils::get_template_definition('action-storylink-create'));
define ('GD_TEMPLATE_ACTION_STORY_UPDATE', PoP_ServerUtils::get_template_definition('action-story-update'));
define ('GD_TEMPLATE_ACTION_STORYLINK_UPDATE', PoP_ServerUtils::get_template_definition('action-storylink-update'));
define ('GD_TEMPLATE_ACTION_ANNOUNCEMENT_CREATE', PoP_ServerUtils::get_template_definition('action-announcement-create'));
define ('GD_TEMPLATE_ACTION_ANNOUNCEMENTLINK_CREATE', PoP_ServerUtils::get_template_definition('action-announcementlink-create'));
define ('GD_TEMPLATE_ACTION_ANNOUNCEMENT_UPDATE', PoP_ServerUtils::get_template_definition('action-announcement-update'));
define ('GD_TEMPLATE_ACTION_ANNOUNCEMENTLINK_UPDATE', PoP_ServerUtils::get_template_definition('action-announcementlink-update'));
define ('GD_TEMPLATE_ACTION_DISCUSSION_CREATE', PoP_ServerUtils::get_template_definition('action-discussion-create'));
define ('GD_TEMPLATE_ACTION_DISCUSSIONLINK_CREATE', PoP_ServerUtils::get_template_definition('action-discussionlink-create'));
define ('GD_TEMPLATE_ACTION_DISCUSSION_UPDATE', PoP_ServerUtils::get_template_definition('action-discussion-update'));
define ('GD_TEMPLATE_ACTION_DISCUSSIONLINK_UPDATE', PoP_ServerUtils::get_template_definition('action-discussionlink-update'));
define ('GD_TEMPLATE_ACTION_FEATURED_CREATE', PoP_ServerUtils::get_template_definition('action-featured-create'));
define ('GD_TEMPLATE_ACTION_FEATURED_UPDATE', PoP_ServerUtils::get_template_definition('action-featured-update'));

class GD_Custom_Template_Processor_CreateUpdatePostActions extends GD_Template_Processor_ActionsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_ACTION_PROJECT_CREATE,
			GD_TEMPLATE_ACTION_PROJECTLINK_CREATE,
			GD_TEMPLATE_ACTION_PROJECT_UPDATE,
			GD_TEMPLATE_ACTION_PROJECTLINK_UPDATE,
			GD_TEMPLATE_ACTION_STORY_CREATE,
			GD_TEMPLATE_ACTION_STORYLINK_CREATE,
			GD_TEMPLATE_ACTION_STORY_UPDATE,
			GD_TEMPLATE_ACTION_STORYLINK_UPDATE,
			GD_TEMPLATE_ACTION_ANNOUNCEMENT_CREATE,
			GD_TEMPLATE_ACTION_ANNOUNCEMENTLINK_CREATE,
			GD_TEMPLATE_ACTION_ANNOUNCEMENT_UPDATE,
			GD_TEMPLATE_ACTION_ANNOUNCEMENTLINK_UPDATE,
			GD_TEMPLATE_ACTION_DISCUSSION_CREATE,
			GD_TEMPLATE_ACTION_DISCUSSIONLINK_CREATE,
			GD_TEMPLATE_ACTION_DISCUSSION_UPDATE,
			GD_TEMPLATE_ACTION_DISCUSSIONLINK_UPDATE,
			GD_TEMPLATE_ACTION_FEATURED_CREATE,
			GD_TEMPLATE_ACTION_FEATURED_UPDATE,
		);
	}

	protected function get_actionexecuter($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_PROJECT_CREATE:
			case GD_TEMPLATE_ACTION_PROJECT_UPDATE:

				return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_PROJECT;

			case GD_TEMPLATE_ACTION_PROJECTLINK_CREATE:
			case GD_TEMPLATE_ACTION_PROJECTLINK_UPDATE:

				return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_PROJECTLINK;

			case GD_TEMPLATE_ACTION_STORY_CREATE:
			case GD_TEMPLATE_ACTION_STORY_UPDATE:

				return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_STORY;
			
			case GD_TEMPLATE_ACTION_STORYLINK_CREATE:
			case GD_TEMPLATE_ACTION_STORYLINK_UPDATE:

				return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_STORYLINK;

			case GD_TEMPLATE_ACTION_ANNOUNCEMENT_CREATE:
			case GD_TEMPLATE_ACTION_ANNOUNCEMENT_UPDATE:
				
				return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_ANNOUNCEMENT;

			case GD_TEMPLATE_ACTION_ANNOUNCEMENTLINK_CREATE:
			case GD_TEMPLATE_ACTION_ANNOUNCEMENTLINK_UPDATE:
				
				return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_ANNOUNCEMENTLINK;
			
			case GD_TEMPLATE_ACTION_DISCUSSION_CREATE:
			case GD_TEMPLATE_ACTION_DISCUSSION_UPDATE:
				
				return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_DISCUSSION;

			case GD_TEMPLATE_ACTION_DISCUSSIONLINK_CREATE:
			case GD_TEMPLATE_ACTION_DISCUSSIONLINK_UPDATE:
				
				return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_DISCUSSIONLINK;
			
			case GD_TEMPLATE_ACTION_FEATURED_CREATE:
			case GD_TEMPLATE_ACTION_FEATURED_UPDATE:
				
				return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_FEATURED;
		}

		return parent::get_actionexecuter($template_id);
	}

	protected function get_iohandler($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_PROJECT_CREATE:
			case GD_TEMPLATE_ACTION_PROJECTLINK_CREATE:
			case GD_TEMPLATE_ACTION_STORY_CREATE:
			case GD_TEMPLATE_ACTION_STORYLINK_CREATE:
			case GD_TEMPLATE_ACTION_ANNOUNCEMENT_CREATE:
			case GD_TEMPLATE_ACTION_ANNOUNCEMENTLINK_CREATE:
			case GD_TEMPLATE_ACTION_DISCUSSION_CREATE:
			case GD_TEMPLATE_ACTION_DISCUSSIONLINK_CREATE:
			case GD_TEMPLATE_ACTION_FEATURED_CREATE:

				return GD_DATALOAD_IOHANDLER_ADDPOST;
					
			case GD_TEMPLATE_ACTION_PROJECT_UPDATE:
			case GD_TEMPLATE_ACTION_PROJECTLINK_UPDATE:
			case GD_TEMPLATE_ACTION_STORY_UPDATE:
			case GD_TEMPLATE_ACTION_STORYLINK_UPDATE:
			case GD_TEMPLATE_ACTION_ANNOUNCEMENT_UPDATE:
			case GD_TEMPLATE_ACTION_ANNOUNCEMENTLINK_UPDATE:
			case GD_TEMPLATE_ACTION_DISCUSSION_UPDATE:
			case GD_TEMPLATE_ACTION_DISCUSSIONLINK_UPDATE:
			case GD_TEMPLATE_ACTION_FEATURED_UPDATE:

				return GD_DATALOAD_IOHANDLER_EDITPOST;
		}

		return parent::get_iohandler($template_id);
	}


	function get_dataloader($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_PROJECT_CREATE:
			case GD_TEMPLATE_ACTION_PROJECTLINK_CREATE:
			case GD_TEMPLATE_ACTION_PROJECT_UPDATE:
			case GD_TEMPLATE_ACTION_PROJECTLINK_UPDATE:
			case GD_TEMPLATE_ACTION_STORY_CREATE:
			case GD_TEMPLATE_ACTION_STORYLINK_CREATE:
			case GD_TEMPLATE_ACTION_STORY_UPDATE:
			case GD_TEMPLATE_ACTION_STORYLINK_UPDATE:
			case GD_TEMPLATE_ACTION_ANNOUNCEMENT_CREATE:
			case GD_TEMPLATE_ACTION_ANNOUNCEMENTLINK_CREATE:
			case GD_TEMPLATE_ACTION_ANNOUNCEMENT_UPDATE:
			case GD_TEMPLATE_ACTION_ANNOUNCEMENTLINK_UPDATE:
			case GD_TEMPLATE_ACTION_DISCUSSION_CREATE:
			case GD_TEMPLATE_ACTION_DISCUSSIONLINK_CREATE:
			case GD_TEMPLATE_ACTION_DISCUSSION_UPDATE:
			case GD_TEMPLATE_ACTION_DISCUSSIONLINK_UPDATE:
			case GD_TEMPLATE_ACTION_FEATURED_CREATE:
			case GD_TEMPLATE_ACTION_FEATURED_UPDATE:

				return GD_DATALOADER_POSTLIST;
		}

		return parent::get_dataloader($template_id);
	}


	function get_settings_id($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_PROJECT_CREATE:
			
				return GD_TEMPLATE_BLOCK_PROJECT_CREATE;

			case GD_TEMPLATE_ACTION_PROJECTLINK_CREATE:
			
				return GD_TEMPLATE_BLOCK_PROJECTLINK_CREATE;
			
			case GD_TEMPLATE_ACTION_PROJECT_UPDATE:
			
				return GD_TEMPLATE_BLOCK_PROJECT_UPDATE;

			case GD_TEMPLATE_ACTION_PROJECTLINK_UPDATE:
			
				return GD_TEMPLATE_BLOCK_PROJECTLINK_UPDATE;
			
			case GD_TEMPLATE_ACTION_STORY_CREATE:
			
				return GD_TEMPLATE_BLOCK_STORY_CREATE;

			case GD_TEMPLATE_ACTION_STORYLINK_CREATE:
			
				return GD_TEMPLATE_BLOCK_STORYLINK_CREATE;
			
			case GD_TEMPLATE_ACTION_STORY_UPDATE:
			
				return GD_TEMPLATE_BLOCK_STORY_UPDATE;

			case GD_TEMPLATE_ACTION_STORYLINK_UPDATE:
			
				return GD_TEMPLATE_BLOCK_STORYLINK_UPDATE;
			
			case GD_TEMPLATE_ACTION_ANNOUNCEMENT_CREATE:
			
				return GD_TEMPLATE_BLOCK_ANNOUNCEMENT_CREATE;

			case GD_TEMPLATE_ACTION_ANNOUNCEMENTLINK_CREATE:
			
				return GD_TEMPLATE_BLOCK_ANNOUNCEMENTLINK_CREATE;
			
			case GD_TEMPLATE_ACTION_ANNOUNCEMENT_UPDATE:
			
				return GD_TEMPLATE_BLOCK_ANNOUNCEMENT_UPDATE;

			case GD_TEMPLATE_ACTION_ANNOUNCEMENTLINK_UPDATE:
			
				return GD_TEMPLATE_BLOCK_ANNOUNCEMENTLINK_UPDATE;
			
			case GD_TEMPLATE_ACTION_DISCUSSION_CREATE:
			
				return GD_TEMPLATE_BLOCK_DISCUSSION_CREATE;

			case GD_TEMPLATE_ACTION_DISCUSSIONLINK_CREATE:
			
				return GD_TEMPLATE_BLOCK_DISCUSSIONLINK_CREATE;
			
			case GD_TEMPLATE_ACTION_DISCUSSION_UPDATE:
			
				return GD_TEMPLATE_BLOCK_DISCUSSION_UPDATE;

			case GD_TEMPLATE_ACTION_DISCUSSIONLINK_UPDATE:
			
				return GD_TEMPLATE_BLOCK_DISCUSSIONLINK_UPDATE;
			
			case GD_TEMPLATE_ACTION_FEATURED_CREATE:
			
				return GD_TEMPLATE_BLOCK_FEATURED_CREATE;
			
			case GD_TEMPLATE_ACTION_FEATURED_UPDATE:
			
				return GD_TEMPLATE_BLOCK_FEATURED_UPDATE;
		}

		return parent::get_settings_id($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_CreateUpdatePostActions();