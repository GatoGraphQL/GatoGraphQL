<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Functions for the MESYM Processors
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('GD_Template_Processor_SideTabPanelBlockGroups:navigator:blocks', 'gd_custom_navigatorblocks');
function gd_custom_navigatorblocks($blocks) {

	return array_merge(
		$blocks,
		array(
			GD_TEMPLATE_BLOCK_PROJECTS_SCROLL_NAVIGATOR,
			GD_TEMPLATE_BLOCK_STORIES_SCROLL_NAVIGATOR,
			GD_TEMPLATE_BLOCK_ANNOUNCEMENTS_SCROLL_NAVIGATOR,
			GD_TEMPLATE_BLOCK_DISCUSSIONS_SCROLL_NAVIGATOR,
			GD_TEMPLATE_BLOCK_FEATURED_SCROLL_NAVIGATOR,
			// GD_TEMPLATE_BLOCK_RESOURCES_SCROLL_NAVIGATOR,
			// GD_TEMPLATE_BLOCK_BLOG_SCROLL_NAVIGATOR
		)
	);
}

add_filter('GD_Template_Processor_DropdownButtonControls:addrelatedpost-dropdown:buttons', 'gd_custom_addrelatedpost_buttons');
function gd_custom_addrelatedpost_buttons($buttons) {

	// Order here is different than in the main menu, since this order makes more sense for "Responses/additionals":
	// Most likely it will be replied to with a Discussion, so put it first
	if (POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDDISCUSSION) {
		$buttons[] = GD_CUSTOM_TEMPLATE_BUTTON_DISCUSSION_CREATE;
		$buttons[] = GD_CUSTOM_TEMPLATE_BUTTON_DISCUSSIONLINK_CREATE;
	}
	if (POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDANNOUNCEMENT) {
		$buttons[] = GD_CUSTOM_TEMPLATE_BUTTON_ANNOUNCEMENT_CREATE;
		$buttons[] = GD_CUSTOM_TEMPLATE_BUTTON_ANNOUNCEMENTLINK_CREATE;
	}
	if (POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDPROJECT) {
		$buttons[] = GD_CUSTOM_TEMPLATE_BUTTON_PROJECT_CREATE;
		$buttons[] = GD_CUSTOM_TEMPLATE_BUTTON_PROJECTLINK_CREATE;
	}
	if (POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDSTORY) {
		$buttons[] = GD_CUSTOM_TEMPLATE_BUTTON_STORY_CREATE;
		$buttons[] = GD_CUSTOM_TEMPLATE_BUTTON_STORYLINK_CREATE;
	}

	return $buttons;
}

/**---------------------------------------------------------------------------------------------------------------
 * Pages with multiple opens
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_TemplateManager_Utils:multiple_open', 'gd_custom_multipleopenurls');
function gd_custom_multipleopenurls($multiple_open) {

	if (is_page()) {

		global $post;
		$create_pages = array(
			POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDPROJECT,
			POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDPROJECTLINK,
			POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDSTORY,
			POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDSTORYLINK,
			POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDDISCUSSION,
			POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDDISCUSSIONLINK,
			POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDFEATURED,
			POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDANNOUNCEMENT,
			POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDANNOUNCEMENTLINK,
		);
		if (in_array($post->ID, $create_pages)) {

			return true;
		}
	}

	return $multiple_open;
}


/**---------------------------------------------------------------------------------------------------------------
 * Override with custom codes
 * ---------------------------------------------------------------------------------------------------------------*/
// add_filter('GD_Template_Processor_CustomBlocks:block-inners:oursponsorsintro', 'gd_custom_code_oursponsorsintro');
// function gd_custom_code_oursponsorsintro($code) {

// 	return GD_CUSTOM_TEMPLATE_CODE_OURSPONSORSINTRO;
// }

/**---------------------------------------------------------------------------------------------------------------
 * All Content / Latest Counts Categories
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_template:allcontent:categories', 'gd_custom_allcontentcategories');
add_filter('gd_template:latestcounts:categories', 'gd_custom_allcontentcategories');
function gd_custom_allcontentcategories($categories) {

	// Using array_filter so that if a category was kept in false then it is not fed to allcontent. Eg: Projects not needed for TPP Debate
	return array_merge(
		$categories,
		array_filter(
			array( 
				POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_PROJECTS,
				POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES,
				POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS,
				POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS,
				POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED,
			)
		)
	);
}

/**---------------------------------------------------------------------------------------------------------------
 * Layout templates
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:navigator', 'gd_custom_layouttemplates_navigator');
function gd_custom_layouttemplates_navigator($layouts) {

	// If a category was kept in false then it is not fed to allcontent. Eg: Projects not needed for TPP Debate
	$cat_layouts = array(
		POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_PROJECTS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_PROJECT_NAVIGATOR,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES => GD_TEMPLATE_LAYOUT_PREVIEWPOST_STORY_NAVIGATOR,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_ANNOUNCEMENT_NAVIGATOR,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_DISCUSSION_NAVIGATOR,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED => GD_TEMPLATE_LAYOUT_PREVIEWPOST_FEATURED_NAVIGATOR,
	);
	foreach ($cat_layouts as $cat => $layout) {
		if ($cat) {
			$layouts['post-'.$cat] = $layout;
		}
	}

	return $layouts;
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:addons', 'gd_custom_layouttemplates_addons');
function gd_custom_layouttemplates_addons($layouts) {

	$cat_layouts = array(
		POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_PROJECTS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_PROJECT_ADDONS,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES => GD_TEMPLATE_LAYOUT_PREVIEWPOST_STORY_ADDONS,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_ANNOUNCEMENT_ADDONS,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_DISCUSSION_ADDONS,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED => GD_TEMPLATE_LAYOUT_PREVIEWPOST_FEATURED_ADDONS,
	);
	foreach ($cat_layouts as $cat => $layout) {
		if ($cat) {
			$layouts['post-'.$cat] = $layout;
		}
	}

	return $layouts;
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:details', 'gd_custom_layouttemplates_details');
function gd_custom_layouttemplates_details($layouts) {

	$cat_layouts = array(
		POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_PROJECTS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_PROJECT_DETAILS,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES => GD_TEMPLATE_LAYOUT_PREVIEWPOST_STORY_DETAILS,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_ANNOUNCEMENT_DETAILS,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_DISCUSSION_DETAILS,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED => GD_TEMPLATE_LAYOUT_PREVIEWPOST_FEATURED_DETAILS,
	);
	foreach ($cat_layouts as $cat => $layout) {
		if ($cat) {
			$layouts['post-'.$cat] = $layout;
		}
	}

	return $layouts;
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:thumbnail', 'gd_custom_layouttemplates_thumbnail');
function gd_custom_layouttemplates_thumbnail($layouts) {

	$cat_layouts = array(
		POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_PROJECTS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_PROJECT_THUMBNAIL,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES => GD_TEMPLATE_LAYOUT_PREVIEWPOST_STORY_THUMBNAIL,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_ANNOUNCEMENT_THUMBNAIL,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_DISCUSSION_THUMBNAIL,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED => GD_TEMPLATE_LAYOUT_PREVIEWPOST_FEATURED_THUMBNAIL,
	);
	foreach ($cat_layouts as $cat => $layout) {
		if ($cat) {
			$layouts['post-'.$cat] = $layout;
		}
	}

	return $layouts;
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:list', 'gd_custom_layouttemplates_list');
function gd_custom_layouttemplates_list($layouts) {

	$cat_layouts = array(
		POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_PROJECTS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_PROJECT_LIST,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES => GD_TEMPLATE_LAYOUT_PREVIEWPOST_STORY_LIST,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_ANNOUNCEMENT_LIST,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_DISCUSSION_LIST,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED => GD_TEMPLATE_LAYOUT_PREVIEWPOST_FEATURED_LIST,
	);
	foreach ($cat_layouts as $cat => $layout) {
		if ($cat) {
			$layouts['post-'.$cat] = $layout;
		}
	}

	return $layouts;
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:related', 'gd_custom_layouttemplates_related');
function gd_custom_layouttemplates_related($layouts) {

	$cat_layouts = array(
		POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_PROJECTS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_PROJECT_RELATED,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES => GD_TEMPLATE_LAYOUT_PREVIEWPOST_STORY_RELATED,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_ANNOUNCEMENT_RELATED,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_DISCUSSION_RELATED,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED => GD_TEMPLATE_LAYOUT_PREVIEWPOST_FEATURED_RELATED,
	);
	foreach ($cat_layouts as $cat => $layout) {
		if ($cat) {
			$layouts['post-'.$cat] = $layout;
		}
	}

	return $layouts;
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:edit', 'gd_custom_layouttemplates_edit');
function gd_custom_layouttemplates_edit($layouts) {

	$cat_layouts = array(
		POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_PROJECTS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_PROJECT_EDIT,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES => GD_TEMPLATE_LAYOUT_PREVIEWPOST_STORY_EDIT,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_ANNOUNCEMENT_EDIT,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_DISCUSSION_EDIT,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED => GD_TEMPLATE_LAYOUT_PREVIEWPOST_FEATURED_EDIT,
	);
	foreach ($cat_layouts as $cat => $layout) {
		if ($cat) {
			$layouts['post-'.$cat] = $layout;
		}
	}

	return $layouts;
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:simpleview', 'gd_custom_layouttemplates_simpleview');
function gd_custom_layouttemplates_simpleview($layouts) {

	// Comment Leo 20/07/2016: layouts below commented because they all can use the default layout instead
	$cat_layouts = array(
		POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_PROJECTS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_PROJECT_SIMPLEVIEW,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES => GD_TEMPLATE_LAYOUT_PREVIEWPOST_STORY_SIMPLEVIEW,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_ANNOUNCEMENT_SIMPLEVIEW,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_DISCUSSION_SIMPLEVIEW,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED => GD_TEMPLATE_LAYOUT_PREVIEWPOST_FEATURED_SIMPLEVIEW,
	);
	foreach ($cat_layouts as $cat => $layout) {
		if ($cat) {
			$layouts['post-'.$cat] = $layout;
		}
	}

	return $layouts;
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:fullview', 'gd_custom_layouttemplates_fullview');
function gd_custom_layouttemplates_fullview($layouts) {

	$cat_layouts = array(
		POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_PROJECTS => GD_TEMPLATE_LAYOUT_FULLVIEW_PROJECT,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES => GD_TEMPLATE_LAYOUT_FULLVIEW_STORY,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS => GD_TEMPLATE_LAYOUT_FULLVIEW_ANNOUNCEMENT,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS => GD_TEMPLATE_LAYOUT_FULLVIEW_DISCUSSION,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED => GD_TEMPLATE_LAYOUT_FULLVIEW_FEATURED
	);
	foreach ($cat_layouts as $cat => $layout) {
		if ($cat) {
			$layouts['post-'.$cat] = $layout;
		}
	}

	return $layouts;
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:authorfullview', 'gd_custom_layouttemplates_authorfullview');
function gd_custom_layouttemplates_authorfullview($layouts) {

	$cat_layouts = array(
		POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_PROJECTS => GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_PROJECT,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES => GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_STORY,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS => GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_ANNOUNCEMENT,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS => GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_DISCUSSION,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED => GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_FEATURED
	);
	foreach ($cat_layouts as $cat => $layout) {
		if ($cat) {
			$layouts['post-'.$cat] = $layout;
		}
	}

	return $layouts;
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:singlefullview', 'gd_custom_layouttemplates_singlefullview');
function gd_custom_layouttemplates_singlefullview($layouts) {

	$cat_layouts = array(
		POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_PROJECTS => GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_PROJECT,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES => GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_STORY,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS => GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_ANNOUNCEMENT,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS => GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_DISCUSSION,
		// POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED => GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_FEATURED
	);
	foreach ($cat_layouts as $cat => $layout) {
		if ($cat) {
			$layouts['post-'.$cat] = $layout;
		}
	}

	return $layouts;
}
add_filter('GD_Template_Processor_MultiplePostLayouts:simpleview:customlayouts', 'gd_custom_layouttemplates_simpleview_customlayouts');
function gd_custom_layouttemplates_simpleview_customlayouts($layouts) {

	$cat_layouts = array(
		POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_PROJECTS => GD_TEMPLATE_MULTICOMPONENT_LOCATIONVOLUNTEER,
	);
	foreach ($cat_layouts as $cat => $layout) {
		if ($cat) {
			$layouts['post-'.$cat] = $layout;
		}
	}

	return $layouts;
}

