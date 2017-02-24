<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Functions for the MESYM Processors
 *
 * ---------------------------------------------------------------------------------------------------------------*/
// add_filter('GD_Template_Processor_BlockGroups:configuration:create_pages', 'gd_em_createpages', 5);
// function gd_em_createpages($create_pages) {

// 	// Add a '0' before so that the integer is not mistaken as the position in the array
// 	return array_merge(
// 		$create_pages,
// 		array(
// 			'0'.POPTHEME_WASSUP_EM_PAGE_ADDEVENT => __('Event', 'poptheme-wassup'),
// 		)
// 	);
// }

add_filter('GD_Template_Processor_SideTabPanelBlockGroups:navigator:blocks', 'gd_em_navigatorblocks');
function gd_em_navigatorblocks($blocks) {

	return array_merge(
		$blocks,
		array(
			GD_TEMPLATE_BLOCK_EVENTS_SCROLL_NAVIGATOR,
			GD_TEMPLATE_BLOCK_EVENTSCALENDAR_CALENDAR_NAVIGATOR,
			GD_TEMPLATE_BLOCK_PASTEVENTS_SCROLL_NAVIGATOR,
		)
	);
}

add_filter('GD_Template_Processor_DropdownButtonControls:addrelatedpost-dropdown:buttons', 'gd_em_addrelatedpost_buttons', 20);
function gd_em_addrelatedpost_buttons($buttons) {

	// Order here is different than in the main menu, since this order makes more sense for "Responses/additionals":
	// Most likely it will be replied to with a Discussion, Event might be last option by the user, so put it last
	if (POPTHEME_WASSUP_EM_PAGE_ADDEVENT) {
		$buttons[] = GD_CUSTOM_TEMPLATE_BUTTON_EVENT_CREATE;
		$buttons[] = GD_CUSTOM_TEMPLATE_BUTTON_EVENTLINK_CREATE;
	}

	return $buttons;
}

/**---------------------------------------------------------------------------------------------------------------
 * Uniqueblocks
 * ---------------------------------------------------------------------------------------------------------------*/
// add_filter('GD_TemplateManager_Utils:get_unique_blockgroups', 'get_em_custom_unique_blockgroups');
// function get_em_custom_unique_blockgroups($blockgroups) {

// 	return array_merge(
// 		$blockgroups,
// 		array(
// 			GD_TEMPLATE_BLOCKGROUP_CREATELOCATION_MODAL,
// 			GD_TEMPLATE_BLOCKGROUP_LOCATIONSMAP_MODAL,
// 		)
// 	);
// }

/**---------------------------------------------------------------------------------------------------------------
 * Pages with multiple opens
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_TemplateManager_Utils:multiple_open', 'gd_em_multipleopenurls');
function gd_em_multipleopenurls($multiple_open) {

	if (is_page()) {

		global $post;
		$create_pages = array(
			POPTHEME_WASSUP_EM_PAGE_ADDEVENT,
			POPTHEME_WASSUP_EM_PAGE_ADDEVENTLINK,
		);
		if (in_array($post->ID, $create_pages)) {

			return true;
		}
	}

	return $multiple_open;
}

/**---------------------------------------------------------------------------------------------------------------
 * Layout templates
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:navigator', 'gd_em_layouttemplates_navigator');
function gd_em_layouttemplates_navigator($layouts) {

	return array_merge(
		$layouts,
		array(
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_FUTURE => GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_NAVIGATOR,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_CURRENT => GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_NAVIGATOR,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_PAST => GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_NAVIGATOR,
		)
	);
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:addons', 'gd_em_layouttemplates_addons');
function gd_em_layouttemplates_addons($layouts) {

	return array_merge(
		$layouts,
		array(
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_FUTURE => GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_ADDONS,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_CURRENT => GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_ADDONS,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_PAST => GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_ADDONS,
		)
	);
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:details', 'gd_em_layouttemplates_details');
function gd_em_layouttemplates_details($layouts) {

	// $multi_future = array(POPTHEME_WASSUP_EM_CAT_FUTURE, POPTHEME_WASSUP_EM_CAT_EVENTLINKS);
	// array_multisort($multi_future);
	// $multi_current = array(POPTHEME_WASSUP_EM_CAT_CURRENT, POPTHEME_WASSUP_EM_CAT_EVENTLINKS);
	// array_multisort($multi_future);
	// $multi_past = array(POPTHEME_WASSUP_EM_CAT_PAST, POPTHEME_WASSUP_EM_CAT_EVENTLINKS);
	// array_multisort($multi_future);

	return array_merge(
		$layouts,
		array(
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_FUTURE => GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_DETAILS,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_CURRENT => GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_DETAILS,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_PAST => GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS,
			// EM_POST_TYPE_EVENT.'-'.implode('-', $multi_future) => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_DETAILS,
			// EM_POST_TYPE_EVENT.'-'.implode('-', $multi_current) => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_DETAILS,
			// EM_POST_TYPE_EVENT.'-'.implode('-', $multi_past) => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_DETAILS,
		)
	);
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:thumbnail', 'gd_em_layouttemplates_thumbnail');
function gd_em_layouttemplates_thumbnail($layouts) {

	return array_merge(
		$layouts,
		array(
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_FUTURE => GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_THUMBNAIL,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_CURRENT => GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_THUMBNAIL,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_PAST => GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_THUMBNAIL,
		)
	);
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:list', 'gd_em_layouttemplates_list');
function gd_em_layouttemplates_list($layouts) {

	return array_merge(
		$layouts,
		array(
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_FUTURE => GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_LIST,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_CURRENT => GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_LIST,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_PAST => GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_LIST,
		)
	);
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:related', 'gd_em_layouttemplates_related');
function gd_em_layouttemplates_related($layouts) {

	return array_merge(
		$layouts,
		array(
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_FUTURE => GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_RELATED,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_CURRENT => GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_RELATED,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_PAST => GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_RELATED,
		)
	);
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:edit', 'gd_em_layouttemplates_edit');
function gd_em_layouttemplates_edit($layouts) {

	return array_merge(
		$layouts,
		array(
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_FUTURE => GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_EDIT,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_CURRENT => GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_EDIT,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_PAST => GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_EDIT,
		)
	);
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:simpleview', 'gd_em_layouttemplates_simpleview');
function gd_em_layouttemplates_simpleview($layouts) {

	return array_merge(
		$layouts,
		array(
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_FUTURE => GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_SIMPLEVIEW,//GD_TEMPLATE_LAYOUT_SIMPLEVIEW_EVENT,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_CURRENT => GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_SIMPLEVIEW,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_PAST => GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_SIMPLEVIEW,//GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_SIMPLEVIEW,
		)
	);
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:fullview', 'gd_em_layouttemplates_fullview');
function gd_em_layouttemplates_fullview($layouts) {

	return array_merge(
		$layouts,
		array(
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_FUTURE => GD_TEMPLATE_LAYOUT_FULLVIEW_EVENT,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_CURRENT => GD_TEMPLATE_LAYOUT_FULLVIEW_EVENT,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_PAST => GD_TEMPLATE_LAYOUT_FULLVIEW_PASTEVENT,
		)
	);
}
// add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:authorsimpleview', 'gd_em_layouttemplates_authorsimpleview');
// function gd_em_layouttemplates_authorsimpleview($layouts) {

// 	return array_merge(
// 		$layouts,
// 		array(
// 			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_FUTURE => GD_TEMPLATE_AUTHORLAYOUT_SIMPLEVIEW_EVENT,
// 			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_CURRENT => GD_TEMPLATE_AUTHORLAYOUT_SIMPLEVIEW_EVENT,
// 			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_PAST => GD_TEMPLATE_AUTHORLAYOUT_SIMPLEVIEW_PASTEVENT,
// 		)
// 	);
// }
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:authorfullview', 'gd_em_layouttemplates_authorfullview');
function gd_em_layouttemplates_authorfullview($layouts) {

	return array_merge(
		$layouts,
		array(
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_FUTURE => GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_EVENT,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_CURRENT => GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_EVENT,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_PAST => GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_PASTEVENT,
		)
	);
}
// add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:singlesimpleview', 'gd_em_layouttemplates_singlesimpleview');
// function gd_em_layouttemplates_singlesimpleview($layouts) {

// 	return array_merge(
// 		$layouts,
// 		array(
// 			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_FUTURE => GD_TEMPLATE_SINGLELAYOUT_SIMPLEVIEW_EVENT,
// 			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_CURRENT => GD_TEMPLATE_SINGLELAYOUT_SIMPLEVIEW_EVENT,
// 			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_PAST => GD_TEMPLATE_SINGLELAYOUT_SIMPLEVIEW_PASTEVENT,
// 		)
// 	);
// }
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:singlefullview', 'gd_em_layouttemplates_singlefullview');
function gd_em_layouttemplates_singlefullview($layouts) {

	return array_merge(
		$layouts,
		array(
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_FUTURE => GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_EVENT,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_CURRENT => GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_EVENT,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_PAST => GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_PASTEVENT,
		)
	);
}
add_filter('GD_Template_Processor_MultiplePostLayouts:simpleview:customlayouts', 'gd_em_layouttemplates_simpleview_customlayouts');
function gd_em_layouttemplates_simpleview_customlayouts($layouts) {

	return array_merge(
		$layouts,
		array(
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_FUTURE => GD_TEMPLATE_MULTICOMPONENT_EVENT_DATELOCATION,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_CURRENT => GD_TEMPLATE_MULTICOMPONENT_EVENT_DATELOCATION,
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_PAST => GD_TEMPLATE_MULTICOMPONENT_EVENT_DATELOCATION,
		)
	);
}

/**---------------------------------------------------------------------------------------------------------------
 * Map format
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_Template_Processor_SectionTabPanelBlockGroups:blocks', 'gd_em_sectiontabpanelblockgroups_blocks', 10, 2);
function gd_em_sectiontabpanelblockgroups_blocks($blockgroup_blocks, $blockgroup) {

	$blockgroup_blockmaps = array(
		GD_TEMPLATE_BLOCKGROUP_TABPANEL_ALLUSERS => GD_TEMPLATE_BLOCK_ALLUSERS_SCROLLMAP,
		GD_TEMPLATE_BLOCKGROUP_TABPANEL_SEARCHUSERS => GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLLMAP,
	);
	if ($map = $blockgroup_blockmaps[$blockgroup]) {
		$blockgroup_blocks[] = $map;
	}

	return $blockgroup_blocks;
}
add_filter('GD_Template_Processor_SectionTabPanelBlockGroups:panel_headers', 'gd_em_sectiontabpanelblockgroups_panelheaders', 10, 2);
function gd_em_sectiontabpanelblockgroups_panelheaders($panelheaders, $blockgroup) {

	$blockgroup_blockmaps = array(
		GD_TEMPLATE_BLOCKGROUP_TABPANEL_ALLUSERS => GD_TEMPLATE_BLOCK_ALLUSERS_SCROLLMAP,
		GD_TEMPLATE_BLOCKGROUP_TABPANEL_SEARCHUSERS => GD_TEMPLATE_BLOCK_SEARCHUSERS_SCROLLMAP,
	);
	if ($map = $blockgroup_blockmaps[$blockgroup]) {
		$panelheaders[$map] = array();
	}

	return $panelheaders;
}
add_filter('GD_Template_Processor_AuthorSectionTabPanelBlockGroups:blocks', 'gd_em_authorsectiontabpanelblockgroups_blocks', 10, 2);
function gd_em_authorsectiontabpanelblockgroups_blocks($blockgroup_blocks, $blockgroup) {

	$blockgroup_blockmaps = array(
		GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORFOLLOWERS => GD_TEMPLATE_BLOCK_AUTHORFOLLOWERS_SCROLLMAP,
		GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORFOLLOWINGUSERS => GD_TEMPLATE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLLMAP,
	);
	if ($map = $blockgroup_blockmaps[$blockgroup]) {
		$blockgroup_blocks[] = $map;
	}

	return $blockgroup_blocks;
}
add_filter('GD_Template_Processor_AuthorSectionTabPanelBlockGroups:panel_headers', 'gd_em_authorsectiontabpanelblockgroups_panelheaders', 10, 2);
function gd_em_authorsectiontabpanelblockgroups_panelheaders($panelheaders, $blockgroup) {

	$blockgroup_blockmaps = array(
		GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORFOLLOWERS => GD_TEMPLATE_BLOCK_AUTHORFOLLOWERS_SCROLLMAP,
		GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORFOLLOWINGUSERS => GD_TEMPLATE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLLMAP,
	);
	if ($map = $blockgroup_blockmaps[$blockgroup]) {
		$panelheaders[$map] = array();
	}

	return $panelheaders;
}
add_filter('GD_Template_Processor_SingleSectionTabPanelBlockGroups:blocks', 'gd_em_singlesectiontabpanelblockgroups_blocks', 10, 2);
function gd_em_singlesectiontabpanelblockgroups_blocks($blockgroup_blocks, $blockgroup) {

	$blockgroup_blockmaps = array(
		GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEAUTHORS => GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLLMAP,
		GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERECOMMENDEDBY => GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLLMAP,
		GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEUPVOTEDBY => GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLLMAP,
		GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEDOWNVOTEDBY => GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLLMAP,
	);
	if ($map = $blockgroup_blockmaps[$blockgroup]) {
		$blockgroup_blocks[] = $map;
	}

	return $blockgroup_blocks;
}
add_filter('GD_Template_Processor_TagSectionTabPanelBlockGroups:blocks', 'gd_em_tagsectiontabpanelblockgroups_blocks', 10, 2);
function gd_em_tagsectiontabpanelblockgroups_blocks($blockgroup_blocks, $blockgroup) {

	$blockgroup_blockmaps = array(
		GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGSUBSCRIBERS => GD_TEMPLATE_BLOCK_TAGSUBSCRIBERS_SCROLLMAP,
	);
	if ($map = $blockgroup_blockmaps[$blockgroup]) {
		$blockgroup_blocks[] = $map;
	}

	return $blockgroup_blocks;
}
// add_filter('GD_Template_Processor_TopLevelCarouselBlockGroups:blockgroup_blocks', 'gd_em_websitefeatures_whoweare_blockgroups_blocks', 10, 2);
// add_filter('GetPoP_Template_Processor_SectionTabPanelBlockGroups:blockgroup_blocks', 'gd_em_websitefeatures_whoweare_blockgroups_blocks', 10, 2);
// function gd_em_websitefeatures_whoweare_blockgroups_blocks($blockgroup_blocks, $template_id) {

// 	$blockgroup_blocks[] = GD_TEMPLATE_BLOCK_WHOWEARE_SCROLLMAP;
// 	return $blockgroup_blocks;
// }
add_filter('GD_Template_Processor_TopLevelCarouselBlockGroups:panel_header_title', 'gd_em_websitefeatures_whoweare_panelheadertitle', 10, 2);
function gd_em_websitefeatures_whoweare_panelheadertitle($title, $blockunit) {

	if ($blockunit == GD_TEMPLATE_BLOCK_WHOWEARE_SCROLLMAP) {

		return sprintf(
			__('%sMap', 'poptheme-wassup'),
			'<i class="fa fa-fw fa-map-marker"></i>'
		);
	}
	return $title;
}
add_filter('GD_Template_Processor_SingleSectionTabPanelBlockGroups:panel_headers', 'gd_em_singlesectiontabpanelblockgroups_panelheaders', 10, 2);
function gd_em_singlesectiontabpanelblockgroups_panelheaders($panelheaders, $blockgroup) {

	$blockgroup_blockmaps = array(
		GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEAUTHORS => GD_TEMPLATE_BLOCK_SINGLEAUTHORS_SCROLLMAP,
		GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLERECOMMENDEDBY => GD_TEMPLATE_BLOCK_SINGLERECOMMENDEDBY_SCROLLMAP,
		GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEUPVOTEDBY => GD_TEMPLATE_BLOCK_SINGLEUPVOTEDBY_SCROLLMAP,
		GD_TEMPLATE_BLOCKGROUP_TABPANEL_SINGLEDOWNVOTEDBY => GD_TEMPLATE_BLOCK_SINGLEDOWNVOTEDBY_SCROLLMAP,
	);
	if ($map = $blockgroup_blockmaps[$blockgroup]) {
		$panelheaders[$map] = array();
	}

	return $panelheaders;
}
add_filter('GD_Template_Processor_TagSectionTabPanelBlockGroups:panel_headers', 'gd_em_tagsectiontabpanelblockgroups_panelheaders', 10, 2);
function gd_em_tagsectiontabpanelblockgroups_panelheaders($panelheaders, $blockgroup) {

	$blockgroup_blockmaps = array(
		GD_TEMPLATE_BLOCKGROUP_TABPANEL_TAGSUBSCRIBERS => GD_TEMPLATE_BLOCK_TAGSUBSCRIBERS_SCROLLMAP,
	);
	if ($map = $blockgroup_blockmaps[$blockgroup]) {
		$panelheaders[$map] = array();
	}

	return $panelheaders;
}


/**---------------------------------------------------------------------------------------------------------------
 * Section Filters
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('wassup_section_taxonomyterms', 'pop_em_section_taxonomyterms', 0);
function pop_em_section_taxonomyterms($section_taxonomyterms) {

	$section_taxonomyterms = array_merge_recursive(
		$section_taxonomyterms,
		array(EM_TAXONOMY_CATEGORY => array(POPTHEME_WASSUP_EM_CAT_ALL))
	);

	return $section_taxonomyterms;
}

add_filter('GD_FormInput_Sections:taxonomyterms:name', 'pop_em_section_taxonomyterms_name', 10, 3);
function pop_em_section_taxonomyterms_name($name, $taxonomy, $term) {

	switch ($taxonomy) {
		
		case EM_TAXONOMY_CATEGORY:
			if (POPTHEME_WASSUP_EM_PAGE_EVENTS) {
				return get_the_title(POPTHEME_WASSUP_EM_PAGE_EVENTS);
			}
			break;
	}

	return $name;
}
