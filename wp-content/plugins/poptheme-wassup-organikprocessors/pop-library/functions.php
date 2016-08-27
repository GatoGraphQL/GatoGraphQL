<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Functions for the MESYM Processors
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('GD_Template_Processor_SideTabPanelBlockGroups:navigator:blocks', 'op_navigatorblocks');
function op_navigatorblocks($blocks) {

	return array_merge(
		$blocks,
		array(
			GD_TEMPLATE_BLOCK_FARMS_SCROLL_NAVIGATOR,
		)
	);
}

add_filter('GD_Template_Processor_DropdownButtonControls:addrelatedpost-dropdown:buttons', 'op_addrelatedpost_buttons');
function op_addrelatedpost_buttons($buttons) {

	// Order here is different than in the main menu, since this order makes more sense for "Responses/additionals":
	// Most likely it will be replied to with a Discussion, so put it first
	if (POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARM) {
		$buttons[] = GD_CUSTOM_TEMPLATE_BUTTON_FARM_CREATE;
		$buttons[] = GD_CUSTOM_TEMPLATE_BUTTON_FARMLINK_CREATE;
	}

	return $buttons;
}

/**---------------------------------------------------------------------------------------------------------------
 * Pages with multiple opens
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_TemplateManager_Utils:multiple_open', 'op_multipleopenurls');
function op_multipleopenurls($multiple_open) {

	if (is_page()) {

		global $post;
		$create_pages = array(
			POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARM,
			POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARMLINK,
		);
		if (in_array($post->ID, $create_pages)) {

			return true;
		}
	}

	return $multiple_open;
}

/**---------------------------------------------------------------------------------------------------------------
 * All Content / Latest Counts Categories
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_template:allcontent:categories', 'op_allcontentcategories');
add_filter('gd_template:latestcounts:categories', 'op_allcontentcategories');
function op_allcontentcategories($categories) {

	// Using array_filter so that if a category was kept in false then it is not fed to allcontent. Eg: Farms not needed for TPP Debate
	return array_merge(
		$categories,
		array_filter(
			array( 
				POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS,
			)
		)
	);
}

/**---------------------------------------------------------------------------------------------------------------
 * Layout templates
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:navigator', 'op_layouttemplates_navigator');
function op_layouttemplates_navigator($layouts) {

	// If a category was kept in false then it is not fed to allcontent. Eg: Farms not needed for TPP Debate
	$cat_layouts = array(
		POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_NAVIGATOR,
	);
	foreach ($cat_layouts as $cat => $layout) {
		if ($cat) {
			$layouts['post-'.$cat] = $layout;
		}
	}

	return $layouts;
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:addons', 'op_layouttemplates_addons');
function op_layouttemplates_addons($layouts) {

	$cat_layouts = array(
		POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_ADDONS,
	);
	foreach ($cat_layouts as $cat => $layout) {
		if ($cat) {
			$layouts['post-'.$cat] = $layout;
		}
	}

	return $layouts;
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:details', 'op_layouttemplates_details');
function op_layouttemplates_details($layouts) {

	$cat_layouts = array(
		POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_DETAILS,
	);
	foreach ($cat_layouts as $cat => $layout) {
		if ($cat) {
			$layouts['post-'.$cat] = $layout;
		}
	}

	return $layouts;
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:thumbnail', 'op_layouttemplates_thumbnail');
function op_layouttemplates_thumbnail($layouts) {

	$cat_layouts = array(
		POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_THUMBNAIL,
	);
	foreach ($cat_layouts as $cat => $layout) {
		if ($cat) {
			$layouts['post-'.$cat] = $layout;
		}
	}

	return $layouts;
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:list', 'op_layouttemplates_list');
function op_layouttemplates_list($layouts) {

	$cat_layouts = array(
		POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_LIST,
	);
	foreach ($cat_layouts as $cat => $layout) {
		if ($cat) {
			$layouts['post-'.$cat] = $layout;
		}
	}

	return $layouts;
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:related', 'op_layouttemplates_related');
function op_layouttemplates_related($layouts) {

	$cat_layouts = array(
		POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_RELATED,
	);
	foreach ($cat_layouts as $cat => $layout) {
		if ($cat) {
			$layouts['post-'.$cat] = $layout;
		}
	}

	return $layouts;
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:edit', 'op_layouttemplates_edit');
function op_layouttemplates_edit($layouts) {

	$cat_layouts = array(
		POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_EDIT,
	);
	foreach ($cat_layouts as $cat => $layout) {
		if ($cat) {
			$layouts['post-'.$cat] = $layout;
		}
	}

	return $layouts;
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:simpleview', 'op_layouttemplates_simpleview');
function op_layouttemplates_simpleview($layouts) {

	$cat_layouts = array(
		POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_FARM_SIMPLEVIEW,
	);
	foreach ($cat_layouts as $cat => $layout) {
		if ($cat) {
			$layouts['post-'.$cat] = $layout;
		}
	}

	return $layouts;
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:fullview', 'op_layouttemplates_fullview');
function op_layouttemplates_fullview($layouts) {

	$cat_layouts = array(
		POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS => GD_TEMPLATE_LAYOUT_FULLVIEW_FARM,
	);
	foreach ($cat_layouts as $cat => $layout) {
		if ($cat) {
			$layouts['post-'.$cat] = $layout;
		}
	}

	return $layouts;
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:authorfullview', 'op_layouttemplates_authorfullview');
function op_layouttemplates_authorfullview($layouts) {

	$cat_layouts = array(
		POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS => GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_FARM,
	);
	foreach ($cat_layouts as $cat => $layout) {
		if ($cat) {
			$layouts['post-'.$cat] = $layout;
		}
	}

	return $layouts;
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:singlefullview', 'op_layouttemplates_singlefullview');
function op_layouttemplates_singlefullview($layouts) {

	$cat_layouts = array(
		POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS => GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_FARM,
	);
	foreach ($cat_layouts as $cat => $layout) {
		if ($cat) {
			$layouts['post-'.$cat] = $layout;
		}
	}

	return $layouts;
}
add_filter('GD_Template_Processor_MultiplePostLayouts:simpleview:customlayouts', 'op_custom_layouttemplates_simpleview_customlayouts');
function op_custom_layouttemplates_simpleview_customlayouts($layouts) {

	$cat_layouts = array(
		POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS => GD_TEMPLATE_MULTICOMPONENT_LOCATIONVOLUNTEER,
	);
	foreach ($cat_layouts as $cat => $layout) {
		if ($cat) {
			$layouts['post-'.$cat] = $layout;
		}
	}

	return $layouts;
}