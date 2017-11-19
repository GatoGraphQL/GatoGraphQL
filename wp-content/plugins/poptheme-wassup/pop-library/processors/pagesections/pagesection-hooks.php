<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_PageSectionHooks {

	function __construct() {

		add_filter(
			'GD_Template_Processor_CustomPageTabPageSections:blockunit_intercept_url:source_blocks:addontabs',
			array($this, 'intercepturl_sourceblocks_addontabs')
		);
		add_filter(
			'GD_Template_Processor_CustomTabPanePageSections:get_atts_block_initial:hover', 
			array($this, 'get_atts_block_initial_hover'), 
			10, 
			3
		);
		add_filter(
			'GD_Template_Processor_CustomTabPanePageSections:get_atts_block_initial:addons', 
			array($this, 'get_atts_block_initial_addons'), 
			10, 
			3
		);
		add_filter(
			'GD_Template_Processor_CustomTabPanePageSections:get_atts_block_initial:sideinfo', 
			array($this, 'get_atts_block_initial_sideinfo'), 
			10, 
			3
		);
		add_filter(
			'GD_Template_Processor_CustomTabPanePageSections:get_header_titles:addons',
			array($this, 'header_titles')
		);
		add_filter(
			'GD_Template_Processor_CustomModalPageSections:get_header_titles:modals',
			array($this, 'modal_header_titles')
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

	function get_atts_block_initial_sideinfo($ret, $subcomponent, $processor) {

		if ($subcomponent == GD_TEMPLATE_BLOCK_TRENDINGTAGS_SCROLL_LIST) {
			
			// Comment Leo 29/10/2017: Actually, we need to lazy-load it, so that it doesn't change the ETag value 
			// for when visiting any one page on the site (eg: viewing a post should not say "click here to update" since the post itself was not updated,
			// only the sideinfo with some unrelated content was)
			// // Comment Leo 19/10/2017: load it straight, damn it! So no need to show the "Loading" or the Skeleton Screen,
			// // taking into account that this component in the sideinfo shows immediately (it's above the fold)
			// Make the block lazy load, but not when loading the frame if generating the code on the server, 
			// because then we need the website to look complete immediately
			// if (!(GD_TemplateManager_Utils::loading_frame() && PoP_Frontend_ServerUtils::use_serverside_rendering())) {
			if (PoP_Frontend_ServerUtils::use_serviceworkers()) {
				
				$processor->add_att($subcomponent, $ret, 'content-loaded', false);
			}
			
			// Comment Leo 29/10/2017: we can't use skeleton screen, since it will then load posts
			// which may change the ETag value for the page... not worth it
			// // Use the Skeleton screen to load the lazy-load content
			// $processor->add_att($subcomponent, $ret, 'use-skeletonscreen', true);
			// }

			// Formatting
			$processor->add_att($subcomponent, $ret, 'show-fetchmore', false);
			$processor->add_att($subcomponent, $ret, 'title-htmltag', 'h4');
			$processor->add_att($subcomponent, $ret, 'add-titlelink', true);

			// Limit to only few elems
			$processor->add_att($subcomponent, $ret, 'limit', 5);
		}		

		return $ret;
	}

	function replicate_blocksettingsids($block_frames) {

		return array_merge(
			$block_frames,
			array(
				GD_TEMPLATE_BLOCK_WEBPOSTLINK_CREATE => GD_TEMPLATE_BLOCK_PAGECONTROL_WEBPOSTLINK_CREATE,
				GD_TEMPLATE_BLOCK_HIGHLIGHT_CREATE => GD_TEMPLATE_BLOCK_PAGECONTROL_HIGHLIGHT_CREATE,
				GD_TEMPLATE_BLOCK_WEBPOST_CREATE => GD_TEMPLATE_BLOCK_PAGECONTROL_WEBPOST_CREATE,
			)
		);
	}

	function blockunit_intercept_url_sideinfo_sourceblocks($block_sources) {

		// Add Content window in the Main or Addons pageSection?
		if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_MAIN) {

			return array_merge(
				$block_sources,
				array(
					GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_WEBPOST_CREATE => GD_TEMPLATE_BLOCK_WEBPOST_CREATE,
					GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_WEBPOSTLINK_CREATE => GD_TEMPLATE_BLOCK_WEBPOSTLINK_CREATE,
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
					GD_TEMPLATE_BLOCK_PAGETABS_WEBPOST_CREATE => GD_TEMPLATE_BLOCK_WEBPOST_CREATE,
					GD_TEMPLATE_BLOCK_PAGETABS_WEBPOSTLINK_CREATE => GD_TEMPLATE_BLOCK_WEBPOSTLINK_CREATE,
				)
			);
		}

		return $source_blocks;
	}

	function modal_header_titles($header_titles) {

		$faqs = sprintf(
			'<i class="fa fa-fw fa-info-circle"></i>%s',
			__('FAQs', 'poptheme-wassup')
		);
		return array_merge(
			$header_titles,
			array(
				GD_TEMPLATE_BLOCK_ADDCONTENTFAQ => $faqs,
				GD_TEMPLATE_BLOCK_ACCOUNTFAQ => $faqs,
			)
		);
	}

	function header_titles($header_titles) {

		return array_merge(
			$header_titles,
			array(
				GD_TEMPLATE_BLOCKGROUP_REPLICATEADDCOMMENT => __('Add a comment for:', 'poptheme-wassup'),
			)
		);
	}

	function get_atts_block_initial_hover($ret, $subcomponent, $processor) {

		// Needed to erase previous feedback messages when a pageSection opens. Eg: Reset password
		$processor->merge_block_jsmethod_att($subcomponent, $ret, array('closeMessageFeedbacksOnPageSectionOpen'));

		$close_blocks = array(
			GD_TEMPLATE_BLOCK_LOGIN,
			GD_TEMPLATE_BLOCK_LOGOUT,
		);
		if (in_array($subcomponent, $close_blocks)) {
			$processor->merge_block_jsmethod_att($subcomponent, $ret, array('closePageSectionOnSuccess'));
			$processor->merge_att($subcomponent, $ret, 'params', array(
				'data-closetime' => 1500
			));
		}

		return $ret;
	}

	function get_atts_block_initial_addons($ret, $subcomponent, $processor) {

		$destroy_blocks = array(
			GD_TEMPLATE_BLOCK_ADDCOMMENT,
		);
		if (in_array($subcomponent, $destroy_blocks)) {
			$processor->merge_block_jsmethod_att($subcomponent, $ret, array('destroyPageOnSuccess'));
		}

		$notitle = array(
			GD_TEMPLATE_BLOCK_HIGHLIGHT_CREATE,
			GD_TEMPLATE_BLOCK_HIGHLIGHT_UPDATE,
		);
		if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {

			$notitle = array_merge(
				$notitle,
				array(
					GD_TEMPLATE_BLOCK_WEBPOST_CREATE,
					GD_TEMPLATE_BLOCK_WEBPOST_UPDATE,
					GD_TEMPLATE_BLOCK_WEBPOSTLINK_CREATE,
					GD_TEMPLATE_BLOCK_WEBPOSTLINK_UPDATE,
				)
			);
		}
		if (in_array($subcomponent, $notitle)) {
			$processor->add_att($subcomponent, $ret, 'title', '');
		}

		return $ret;
	}

	function intercepturl_sourceblocks_addontabs($source_blocks) {

		$source_blocks = array_merge(
			$source_blocks,
			array(
				GD_TEMPLATE_BLOCK_ADDONTABS_ADDCOMMENT => GD_TEMPLATE_BLOCK_ADDCOMMENT,
				GD_TEMPLATE_BLOCK_PAGETABS_HIGHLIGHT_CREATE => GD_TEMPLATE_BLOCK_HIGHLIGHT_CREATE,
			)
		);
		if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {

			$source_blocks = array_merge(
				$source_blocks,
				array(
					GD_TEMPLATE_BLOCK_PAGETABS_WEBPOST_CREATE => GD_TEMPLATE_BLOCK_WEBPOST_CREATE,
					GD_TEMPLATE_BLOCK_PAGETABS_WEBPOSTLINK_CREATE => GD_TEMPLATE_BLOCK_WEBPOSTLINK_CREATE,
				)
			);
		}
		return $source_blocks;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_PageSectionHooks();
