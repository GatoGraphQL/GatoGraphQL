<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_SectionProcessors_PageSectionHooks {

	function __construct() {

		add_filter(
			'GD_Template_Processor_CustomTabPanePageSections:get_atts_block_initial:addons', 
			array($this, 'get_atts_block_initial_addons'), 
			10, 
			3
		);
		add_filter(
			'GD_Template_Processor_CustomPageTabPageSections:blockunit_intercept_url:source_blocks:addontabs',
			array($this, 'intercepturl_sourceblocks_addontabs')
		);

		add_filter(
			'GD_Template_Processor_CustomPageTabPageSections:blockunit_intercept_url:source_blocks:main', 
			array($this, 'intercepturl_sourceblocks_main')
		);
		add_filter(
			'GD_Template_Processor_CustomTabPanePageSections:blockunit_intercept_url:sideinfo_sourceblocks',
			array($this, 'blockunit_intercept_url_sideinfo_sourceblocks')
		);
		add_filter(
			'GD_Template_Processor_BootstrapPageSectionsBase:replicate_blocksettingsids',
			array($this, 'replicate_blocksettingsids')
		);
	}

	function replicate_blocksettingsids($block_frames) {

		return array_merge(
			$block_frames,
			array(
				GD_TEMPLATE_BLOCK_PROJECT_CREATE => GD_TEMPLATE_BLOCK_PAGECONTROL_PROJECT_CREATE,
				GD_TEMPLATE_BLOCK_PROJECTLINK_CREATE => GD_TEMPLATE_BLOCK_PAGECONTROL_PROJECTLINK_CREATE,
				GD_TEMPLATE_BLOCK_STORY_CREATE => GD_TEMPLATE_BLOCK_PAGECONTROL_STORY_CREATE,
				GD_TEMPLATE_BLOCK_STORYLINK_CREATE => GD_TEMPLATE_BLOCK_PAGECONTROL_STORYLINK_CREATE,
				GD_TEMPLATE_BLOCK_ANNOUNCEMENT_CREATE => GD_TEMPLATE_BLOCK_PAGECONTROL_ANNOUNCEMENT_CREATE,
				GD_TEMPLATE_BLOCK_ANNOUNCEMENTLINK_CREATE => GD_TEMPLATE_BLOCK_PAGECONTROL_ANNOUNCEMENTLINK_CREATE,
				GD_TEMPLATE_BLOCK_DISCUSSION_CREATE => GD_TEMPLATE_BLOCK_PAGECONTROL_DISCUSSION_CREATE,
				GD_TEMPLATE_BLOCK_DISCUSSIONLINK_CREATE => GD_TEMPLATE_BLOCK_PAGECONTROL_DISCUSSIONLINK_CREATE,
			)
		);
	}

	function blockunit_intercept_url_sideinfo_sourceblocks($block_sources) {

		if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_MAIN) {
			
			return array_merge(
				$block_sources,
				array(
					GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_PROJECT_CREATE => GD_TEMPLATE_BLOCK_PROJECT_CREATE,
					GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_PROJECTLINK_CREATE => GD_TEMPLATE_BLOCK_PROJECTLINK_CREATE,
					GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_STORY_CREATE => GD_TEMPLATE_BLOCK_STORY_CREATE,
					GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_STORYLINK_CREATE => GD_TEMPLATE_BLOCK_STORYLINK_CREATE,
					GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_ANNOUNCEMENT_CREATE => GD_TEMPLATE_BLOCK_ANNOUNCEMENT_CREATE,
					GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_ANNOUNCEMENTLINK_CREATE => GD_TEMPLATE_BLOCK_ANNOUNCEMENTLINK_CREATE,
					GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_DISCUSSION_CREATE => GD_TEMPLATE_BLOCK_DISCUSSION_CREATE,
					GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_DISCUSSIONLINK_CREATE => GD_TEMPLATE_BLOCK_DISCUSSIONLINK_CREATE,
				)
			);
		}

		return $block_sources;
	}

	function intercepturl_sourceblocks_main($source_blocks) {

		if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_MAIN) {
		
			return array_merge(
				$source_blocks,
				array(
					GD_TEMPLATE_BLOCK_PAGETABS_PROJECT_CREATE => GD_TEMPLATE_BLOCK_PROJECT_CREATE,
					GD_TEMPLATE_BLOCK_PAGETABS_PROJECTLINK_CREATE => GD_TEMPLATE_BLOCK_PROJECTLINK_CREATE,
					GD_TEMPLATE_BLOCK_PAGETABS_STORY_CREATE => GD_TEMPLATE_BLOCK_STORY_CREATE,
					GD_TEMPLATE_BLOCK_PAGETABS_STORYLINK_CREATE => GD_TEMPLATE_BLOCK_STORYLINK_CREATE,
					GD_TEMPLATE_BLOCK_PAGETABS_ANNOUNCEMENT_CREATE => GD_TEMPLATE_BLOCK_ANNOUNCEMENT_CREATE,
					GD_TEMPLATE_BLOCK_PAGETABS_ANNOUNCEMENTLINK_CREATE => GD_TEMPLATE_BLOCK_ANNOUNCEMENTLINK_CREATE,
					GD_TEMPLATE_BLOCK_PAGETABS_DISCUSSION_CREATE => GD_TEMPLATE_BLOCK_DISCUSSION_CREATE,
					GD_TEMPLATE_BLOCK_PAGETABS_DISCUSSIONLINK_CREATE => GD_TEMPLATE_BLOCK_DISCUSSIONLINK_CREATE,
				)
			);
		}

		return $block_sources;
	}

	function get_atts_block_initial_addons($ret, $subcomponent, $processor) {

		if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {

			$notitle = array(
				GD_TEMPLATE_BLOCK_PROJECT_CREATE,
				GD_TEMPLATE_BLOCK_PROJECT_UPDATE,
				GD_TEMPLATE_BLOCK_PROJECTLINK_CREATE,
				GD_TEMPLATE_BLOCK_PROJECTLINK_UPDATE,
				GD_TEMPLATE_BLOCK_STORY_CREATE,
				GD_TEMPLATE_BLOCK_STORY_UPDATE,
				GD_TEMPLATE_BLOCK_STORYLINK_CREATE,
				GD_TEMPLATE_BLOCK_STORYLINK_UPDATE,
				GD_TEMPLATE_BLOCK_ANNOUNCEMENT_CREATE,
				GD_TEMPLATE_BLOCK_ANNOUNCEMENT_UPDATE,
				GD_TEMPLATE_BLOCK_ANNOUNCEMENTLINK_CREATE,
				GD_TEMPLATE_BLOCK_ANNOUNCEMENTLINK_UPDATE,
				GD_TEMPLATE_BLOCK_DISCUSSION_CREATE,
				GD_TEMPLATE_BLOCK_DISCUSSION_UPDATE,
				GD_TEMPLATE_BLOCK_DISCUSSIONLINK_CREATE,
				GD_TEMPLATE_BLOCK_DISCUSSIONLINK_UPDATE,
			);
			if (in_array($subcomponent, $notitle)) {
				$processor->add_att($subcomponent, $ret, 'title', '');
			}
		}

		return $ret;
	}

	function intercepturl_sourceblocks_addontabs($source_blocks) {

		if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {

			$source_blocks = array_merge(
				$source_blocks,
				array(
					GD_TEMPLATE_BLOCK_PAGETABS_PROJECT_CREATE => GD_TEMPLATE_BLOCK_PROJECT_CREATE,
					GD_TEMPLATE_BLOCK_PAGETABS_PROJECTLINK_CREATE => GD_TEMPLATE_BLOCK_PROJECTLINK_CREATE,
					GD_TEMPLATE_BLOCK_PAGETABS_STORY_CREATE => GD_TEMPLATE_BLOCK_STORY_CREATE,
					GD_TEMPLATE_BLOCK_PAGETABS_STORYLINK_CREATE => GD_TEMPLATE_BLOCK_STORYLINK_CREATE,
					GD_TEMPLATE_BLOCK_PAGETABS_ANNOUNCEMENT_CREATE => GD_TEMPLATE_BLOCK_ANNOUNCEMENT_CREATE,
					GD_TEMPLATE_BLOCK_PAGETABS_ANNOUNCEMENTLINK_CREATE => GD_TEMPLATE_BLOCK_ANNOUNCEMENTLINK_CREATE,
					GD_TEMPLATE_BLOCK_PAGETABS_DISCUSSION_CREATE => GD_TEMPLATE_BLOCK_DISCUSSION_CREATE,
					GD_TEMPLATE_BLOCK_PAGETABS_DISCUSSIONLINK_CREATE => GD_TEMPLATE_BLOCK_DISCUSSIONLINK_CREATE,
				)
			);
		}
		return $source_blocks;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_SectionProcessors_PageSectionHooks();
