<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_EM_PageSectionHooks {

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

		// add_filter(
		// 	'GD_Template_Processor_CustomTabPanePageSections:get_permanent_templates:sideinfo', 
		// 	array($this, 'get_permanent_templates_sideinfo')
		// );
		add_filter(
			'GD_Template_Processor_CustomPageTabPageSections:blockunit_intercept_url:source_blocks:main', 
			array($this, 'intercepturl_sourceblocks_main')
		);
		add_filter(
			'GD_Template_Processor_CustomTabPanePageSections:blockunit_intercept_url:sideinfo_sourceblocks',
			array($this, 'blockunit_intercept_url_sideinfo_sourceblocks')
		);
		add_filter(
			'GD_Template_Processor_CustomModalPageSections:get_dialog_classes:modals',
			array($this, 'modal_dialog_classes')
		);
		add_filter(
			'GD_Template_Processor_CustomModalPageSections:get_header_titles:modals',
			array($this, 'modal_header_titles')
		);
		add_filter(
			'GD_Template_Processor_CustomModalPageSections:get_atts_block_initial:modals', 
			array($this, 'get_atts_block_initial_modals'), 
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
			'GD_Template_Processor_CustomModalPageSections:get_body_classes:modals',
			array($this, 'modal_body_classes')
		);
		add_filter(
			'GD_Template_Processor_CustomModalPageSections:get_replicate_types:modals',
			array($this, 'get_replicate_types')
		);
		add_filter(
			'GD_Template_Processor_CustomModalPageSections:unique_urls:modals',
			array($this, 'unique_urls')
		);
		add_filter(
			'GD_Template_Processor_BootstrapPageSectionsBase:replicate_blocksettingsids',
			array($this, 'replicate_blocksettingsids')
		);
	}

	// function get_permanent_templates_sideinfo($template_ids) {

	// 	// Make the Events Calendar be a permanent block in the Sideinfo
	// 	$template_ids[GD_TEMPLATE_BLOCK_EVENTSCALENDAR_CALENDAR_ADDONS] = true;
	// 	return $template_ids;
	// }

	function replicate_blocksettingsids($block_frames) {

		return array_merge(
			$block_frames,
			array(
				GD_TEMPLATE_BLOCK_EVENT_CREATE => GD_TEMPLATE_BLOCK_PAGECONTROL_EVENT_CREATE,
				GD_TEMPLATE_BLOCK_EVENTLINK_CREATE => GD_TEMPLATE_BLOCK_PAGECONTROL_EVENTLINK_CREATE,
			)
		);
	}

	function get_replicate_types($replicate_types) {

		// Make the Locations Map multiple
		$replicate_types[GD_TEMPLATE_BLOCK_STATICLOCATIONSMAP] = GD_CONSTANT_REPLICATETYPE_MULTIPLE;
		return $replicate_types;
	}

	function unique_urls($unique_urls) {

		// Modals map: they are multiple (a modal for a combination of locations),
		// however no need to make the URL unique since the params in the URL already make it unique,
		// and so can open an already-created modal again and again without replicating a new modal window each time
		$unique_urls[GD_TEMPLATE_BLOCK_STATICLOCATIONSMAP] = false;
		return $unique_urls;
	}

	function modal_body_classes($classes) {

		// Do not add the 'modal-body' for the map
		unset($classes[GD_TEMPLATE_BLOCK_STATICLOCATIONSMAP]);
		return $classes;
	}

	function modal_header_titles($header_titles) {

		return array_merge(
			$header_titles,
			array(
				GD_TEMPLATE_BLOCK_STATICLOCATIONSMAP => __('Location(s) for:', 'poptheme-wassup'),
				GD_TEMPLATE_BLOCKGROUP_CREATELOCATION => sprintf(
					'<i class="fa fa-fw fa-map-marker"></i>%s',
					__('Add location', 'poptheme-wassup')
				),
			)
		);
	}

	function modal_dialog_classes($classes) {

		return array_merge(
			$classes,
			array(
				GD_TEMPLATE_BLOCK_STATICLOCATIONSMAP => 'modal-lg',
				GD_TEMPLATE_BLOCKGROUP_CREATELOCATION => 'modal-lg',
			)
		);
	}

	function get_atts_block_initial_sideinfo($ret, $subcomponent, $processor) {

		// if ($subcomponent == GD_TEMPLATE_BLOCK_EVENTSCALENDAR_CALENDAR_ADDONS) {
		if ($subcomponent == GD_TEMPLATE_BLOCK_EVENTS_SCROLL_ADDONS) {
			
			// Make the block lazy load
			$processor->add_att($subcomponent, $ret, 'content-loaded', false);

			// Add the link
			$processor->add_att($subcomponent, $ret, 'title-htmltag', 'h4');
			$processor->add_att($subcomponent, $ret, 'add-titlelink', true);
			
			// For the Events scroll
			$processor->add_att($subcomponent, $ret, 'limit', 6);
			$processor->add_att($subcomponent, $ret, 'show-fetchmore', false);

			$link = sprintf(
				'<div class="text-center"><a href="%s" class="btn btn-link">%s</a></div>',
				get_permalink(POPTHEME_WASSUP_EM_PAGE_EVENTS),
				__('View all', 'poptheme-wassup')
			);
			$processor->add_att($subcomponent, $ret, 'description-bottom', $link);
		}		

		return $ret;
	}

	function get_atts_block_initial_modals($ret, $subcomponent, $processor) {

		if ($subcomponent == GD_TEMPLATE_BLOCKGROUP_CREATELOCATION) {
			
			$processor->merge_block_jsmethod_att($subcomponent, $ret, array('createLocationModalBlock'));
		}
		elseif ($subcomponent == GD_TEMPLATE_BLOCK_CREATELOCATION) {

			// Hide the Title
			$processor->add_att($subcomponent, $ret, 'title', '');

			// Make the block close the modal when the execution was successful
			$processor->merge_block_jsmethod_att($subcomponent, $ret, array('maybeCloseLocationModal'));
		}		
		elseif ($subcomponent == GD_TEMPLATE_BLOCK_STATICLOCATIONSMAP) {

			$processor->merge_block_jsmethod_att($subcomponent, $ret, array('modalMapBlock'));
		}		

		return $ret;
	}

	function blockunit_intercept_url_sideinfo_sourceblocks($block_sources) {

		if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_MAIN) {
			
			return array_merge(
				$block_sources,
				array(
					GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_EVENT_CREATE => GD_TEMPLATE_BLOCK_EVENT_CREATE,
					GD_TEMPLATE_BLOCK_EMPTYSIDEINFO_EVENTLINK_CREATE => GD_TEMPLATE_BLOCK_EVENTLINK_CREATE,
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
					GD_TEMPLATE_BLOCK_PAGETABS_EVENT_CREATE => GD_TEMPLATE_BLOCK_EVENT_CREATE,
					GD_TEMPLATE_BLOCK_PAGETABS_EVENTLINK_CREATE => GD_TEMPLATE_BLOCK_EVENTLINK_CREATE,
				)
			);
		}

		return $block_sources;
	}

	function get_atts_block_initial_addons($ret, $subcomponent, $processor) {

		if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {

			$notitle = array(
				GD_TEMPLATE_BLOCK_EVENT_CREATE,
				GD_TEMPLATE_BLOCK_EVENT_UPDATE,
				GD_TEMPLATE_BLOCK_EVENTLINK_CREATE,
				GD_TEMPLATE_BLOCK_EVENTLINK_UPDATE,
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
					GD_TEMPLATE_BLOCK_PAGETABS_EVENT_CREATE => GD_TEMPLATE_BLOCK_EVENT_CREATE,
					GD_TEMPLATE_BLOCK_PAGETABS_EVENTLINK_CREATE => GD_TEMPLATE_BLOCK_EVENTLINK_CREATE,
				)
			);
		}

		return $source_blocks;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_EM_PageSectionHooks();
