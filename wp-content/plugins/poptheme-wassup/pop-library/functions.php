<?php

add_filter('GD_TemplateManager_Utils:get_hierarchy_page_id:default', 'get_custom_default_hierarchy_page_id');
function get_custom_default_hierarchy_page_id() {

	// Implement the default tabs for Author/Single
	if (is_author()) {
		
		return POP_COREPROCESSORS_PAGE_MAIN;
	}
	elseif (is_single()) {

		return POP_COREPROCESSORS_PAGE_DESCRIPTION;
	}

	return null;
}

/**---------------------------------------------------------------------------------------------------------------
 * Uniqueblocks
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_TemplateManager_Utils:get_unique_blockgroups', 'get_custom_unique_blockgroups');
function get_custom_unique_blockgroups($blockgroups) {

	$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SHAREBYEMAIL_MODAL;
	$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_EMBED_MODAL;
	$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_API_MODAL;
	$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_COPYSEARCHURL_MODAL;

	return $blockgroups;
}

add_filter('GD_TemplateManager_Utils:get_unique_blocks', 'get_custom_unique_blocks');
function get_custom_unique_blocks($blocks) {

	$blocks[] = GD_TEMPLATE_BLOCK_LATESTCOUNTS;

	return $blocks;
}


add_filter('gd_templatemanager:fetchtarget_settings', 'gd_custom_fetchtarget_settings');
function gd_custom_fetchtarget_settings($fetchtarget_settings) {

	return array_merge(
		$fetchtarget_settings, 
		array(
			GD_URLPARAM_TARGET_MAIN => GD_TEMPLATEID_PAGESECTIONID_MAIN,
			GD_URLPARAM_TARGET_QUICKVIEW => GD_TEMPLATEID_PAGESECTIONID_QUICKVIEWMAIN,
			GD_URLPARAM_TARGET_NAVIGATOR => GD_TEMPLATEID_PAGESECTIONID_NAVIGATOR,
			GD_URLPARAM_TARGET_ADDONS => GD_TEMPLATEID_PAGESECTIONID_ADDONS,
			GD_URLPARAM_TARGET_MODALS => GD_TEMPLATEID_PAGESECTIONID_MODALS,
		)
	);
}

add_filter('gd_templatemanager:fetchpagesection_settings', 'gd_custom_fetchpagesection_settings');
function gd_custom_fetchpagesection_settings($fetchpagesection_settings) {

	$settings_main = array(			
		'operation' => GD_URLPARAM_OPERATION_APPEND,
		'noparams-reload-url' => true,
		'updateDocument' => true,
		'maybeRedirect' => true,
		'activeLinks' => true,
	);
	$settings_append = array(			
		'operation' => GD_URLPARAM_OPERATION_APPEND,
	);
	$settings_activeappend = array(			
		'operation' => GD_URLPARAM_OPERATION_APPEND,
		'activeLinks' => true,
	);

	return array_merge(
		$fetchpagesection_settings, 
		array(
			GD_TEMPLATEID_PAGESECTIONID_MAIN => $settings_main,
			GD_TEMPLATEID_PAGESECTIONID_HOVER => $settings_main,
			GD_TEMPLATEID_PAGESECTIONID_PAGETABS => $settings_append, //$settings_main,
			GD_TEMPLATEID_PAGESECTIONID_SIDEINFO => $settings_activeappend,
			GD_TEMPLATEID_PAGESECTIONID_ADDONTABS => $settings_append,
			GD_TEMPLATEID_PAGESECTIONID_ADDONS => $settings_append,
			GD_TEMPLATEID_PAGESECTIONID_MODALS => $settings_append,
			GD_TEMPLATEID_PAGESECTIONID_NAVIGATOR => $settings_append,
			GD_TEMPLATEID_PAGESECTIONID_QUICKVIEWMAIN => $settings_append,
			GD_TEMPLATEID_PAGESECTIONID_QUICKVIEWSIDEINFO => $settings_append,
			GD_TEMPLATEID_PAGESECTIONID_OPERATIONAL => $settings_append, // Operational: do not update browser URL. Eg: for "Follow user" page
			GD_TEMPLATEID_PAGESECTIONID_COMPONENTS => $settings_append, // Operational: do not update browser URL. Eg: for "Follow user" page
		)
	);
}


/**---------------------------------------------------------------------------------------------------------------
 * Targets
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_TemplateManager_Utils:targets', 'get_custom_targets');
function get_custom_targets($targets) {

	return array_merge(
		$targets,
		array(
			GD_URLPARAM_TARGET_QUICKVIEW,
			GD_URLPARAM_TARGET_NAVIGATOR,
			GD_URLPARAM_TARGET_ADDONS,
			GD_URLPARAM_TARGET_MODALS,
		)
	);
}


/**---------------------------------------------------------------------------------------------------------------
 * Pages with multiple opens
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_TemplateManager_Utils:multiple_open', 'gd_multipleopenurls');
function gd_multipleopenurls($multiple_open) {

	if (is_page()) {

		global $post;
		$multiple = array(
			POPTHEME_WASSUP_PAGE_ADDWEBPOSTLINK,
			POPTHEME_WASSUP_PAGE_ADDWEBPOST,
			POPTHEME_WASSUP_PAGE_ADDHIGHLIGHT,
			POP_WPAPI_PAGE_ADDCOMMENT,
			POPTHEME_WASSUP_GF_PAGE_CONTACTUSER,
			POPTHEME_WASSUP_GF_PAGE_VOLUNTEER,
			POPTHEME_WASSUP_GF_PAGE_FLAG,
		);
		if (in_array($post->ID, $multiple)) {

			return true;
		}
	}

	return $multiple_open;
}


add_filter('GD_Template_Processor_DropdownButtonControls:addrelatedpost-dropdown:buttons', 'wassup_addrelatedpost_buttons', 0);
function wassup_addrelatedpost_buttons($buttons) {

	if (POPTHEME_WASSUP_PAGE_ADDWEBPOST) {
		$buttons[] = GD_CUSTOM_TEMPLATE_BUTTON_WEBPOST_CREATE;
	}
	if (POPTHEME_WASSUP_PAGE_ADDWEBPOSTLINK) {
		$buttons[] = GD_CUSTOM_TEMPLATE_BUTTON_WEBPOSTLINK_CREATE;
	}
	
	return $buttons;
}


/**---------------------------------------------------------------------------------------------------------------
 * All Content / Latest Counts Categories
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_template:allcontent:categories', 'wassup_allcontentcategories');
add_filter('gd_template:latestcounts:categories', 'wassup_allcontentcategories');
function wassup_allcontentcategories($categories) {

	// Add if both the Category and the Page IDs are defined
	// if (POPTHEME_WASSUP_CAT_WEBPOSTLINKS && POPTHEME_WASSUP_PAGE_WEBPOSTLINKS) {
		
	// 	$categories = array_merge(
	// 		$categories,
	// 		array( 
	// 			POPTHEME_WASSUP_CAT_WEBPOSTLINKS,
	// 		)
	// 	);
	// }

	// Add if both the Category and the Page IDs are defined
	if (POPTHEME_WASSUP_CAT_WEBPOSTS && POPTHEME_WASSUP_PAGE_WEBPOSTS) {
		
		$categories = array_merge(
			$categories,
			array( 
				POPTHEME_WASSUP_CAT_WEBPOSTS,
			)
		);
	}

	return $categories;
}

/**---------------------------------------------------------------------------------------------------------------
 * Layout templates
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:navigator', 'wassup_layouttemplates_navigator');
function wassup_layouttemplates_navigator($layouts) {

	return array_merge(
		$layouts,
		array(
			// 'post-'.POPTHEME_WASSUP_CAT_WEBPOSTLINKS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_NAVIGATOR,
			'post-'.POPTHEME_WASSUP_CAT_WEBPOSTS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_NAVIGATOR,
		)
	);
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:addons', 'wassup_layouttemplates_addons');
function wassup_layouttemplates_addons($layouts) {

	return array_merge(
		$layouts,
		array(
			// 'post-'.POPTHEME_WASSUP_CAT_WEBPOSTLINKS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_ADDONS,
			'post-'.POPTHEME_WASSUP_CAT_WEBPOSTS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_ADDONS,
		)
	);
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:details', 'wassup_layouttemplates_details');
function wassup_layouttemplates_details($layouts) {

	return array_merge(
		$layouts,
		array(
			// 'post-'.POPTHEME_WASSUP_CAT_WEBPOSTLINKS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_DETAILS,
			'post-'.POPTHEME_WASSUP_CAT_WEBPOSTS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_DETAILS,
		)
	);
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:thumbnail', 'wassup_layouttemplates_thumbnail');
function wassup_layouttemplates_thumbnail($layouts) {

	return array_merge(
		$layouts,
		array(
			// 'post-'.POPTHEME_WASSUP_CAT_WEBPOSTLINKS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_THUMBNAIL,
			'post-'.POPTHEME_WASSUP_CAT_WEBPOSTS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_THUMBNAIL,
		)
	);
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:list', 'wassup_layouttemplates_list');
function wassup_layouttemplates_list($layouts) {

	return array_merge(
		$layouts,
		array(
			// 'post-'.POPTHEME_WASSUP_CAT_WEBPOSTLINKS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_LIST,
			'post-'.POPTHEME_WASSUP_CAT_WEBPOSTS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_LIST,
		)
	);
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:related', 'wassup_layouttemplates_related');
function wassup_layouttemplates_related($layouts) {

	return array_merge(
		$layouts,
		array(
			// 'post-'.POPTHEME_WASSUP_CAT_WEBPOSTLINKS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_RELATED,
			'post-'.POPTHEME_WASSUP_CAT_WEBPOSTS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_RELATED,
		)
	);
}
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:edit', 'wassup_layouttemplates_edit');
function wassup_layouttemplates_edit($layouts) {

	return array_merge(
		$layouts,
		array(
			// 'post-'.POPTHEME_WASSUP_CAT_WEBPOSTLINKS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_EDIT,
			'post-'.POPTHEME_WASSUP_CAT_WEBPOSTS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_EDIT,
		)
	);
}
// add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:simpleview', 'wassup_layouttemplates_simpleview');
// function wassup_layouttemplates_simpleview($layouts) {

// 	// Comment Leo 20/07/2016: commented because they can use the default layout
// 	return $layouts;
// 	// return array_merge(
// 	// 	$layouts,
// 	// 	array(
// 	// 		'post-'.POPTHEME_WASSUP_CAT_WEBPOSTLINKS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_SIMPLEVIEW,
// 	// 		'post-'.POPTHEME_WASSUP_CAT_WEBPOSTS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_SIMPLEVIEW,
// 	// 	)
// 	// );
// }
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:fullview', 'wassup_layouttemplates_fullview');
function wassup_layouttemplates_fullview($layouts) {

	return array_merge(
		$layouts,
		array(
			// 'post-'.POPTHEME_WASSUP_CAT_WEBPOSTLINKS => GD_TEMPLATE_LAYOUT_FULLVIEW_LINK,
			'post-'.POPTHEME_WASSUP_CAT_WEBPOSTS => GD_TEMPLATE_LAYOUT_FULLVIEW_WEBPOST,
		)
	);
}
// add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:authorsimpleview', 'wassup_layouttemplates_authorsimpleview');
// function wassup_layouttemplates_authorsimpleview($layouts) {

// 	return array_merge(
// 		$layouts,
// 		array(
// 			'post-'.POPTHEME_WASSUP_CAT_WEBPOSTLINKS => GD_TEMPLATE_AUTHORLAYOUT_SIMPLEVIEW_LINK,
// 			'post-'.POPTHEME_WASSUP_CAT_WEBPOSTS => GD_TEMPLATE_AUTHORLAYOUT_SIMPLEVIEW_WEBPOST,
// 		)
// 	);
// }
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:authorfullview', 'wassup_layouttemplates_authorfullview');
function wassup_layouttemplates_authorfullview($layouts) {

	return array_merge(
		$layouts,
		array(
			// 'post-'.POPTHEME_WASSUP_CAT_WEBPOSTLINKS => GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_LINK,
			'post-'.POPTHEME_WASSUP_CAT_WEBPOSTS => GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_WEBPOST,
		)
	);
}
// add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:singlesimpleview', 'wassup_layouttemplates_singlesimpleview');
// function wassup_layouttemplates_singlesimpleview($layouts) {

// 	return array_merge(
// 		$layouts,
// 		array(
// 			'post-'.POPTHEME_WASSUP_CAT_WEBPOSTLINKS => GD_TEMPLATE_SINGLELAYOUT_SIMPLEVIEW_LINK,
// 			'post-'.POPTHEME_WASSUP_CAT_WEBPOSTS => GD_TEMPLATE_SINGLELAYOUT_SIMPLEVIEW_WEBPOST,
// 		)
// 	);
// }
add_filter('GD_Template_Processor_MultiplePostLayouts:layouts:singlefullview', 'wassup_layouttemplates_singlefullview');
function wassup_layouttemplates_singlefullview($layouts) {

	return array_merge(
		$layouts,
		array(
			// 'post-'.POPTHEME_WASSUP_CAT_WEBPOSTLINKS => GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_LINK,
			'post-'.POPTHEME_WASSUP_CAT_WEBPOSTS => GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_WEBPOST,
		)
	);
}