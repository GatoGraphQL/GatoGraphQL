<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * URE Library functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * Add the Sidebars to the Author page
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_template:sidebar_author:configuration', 'gd_ure_authorsidebars_configuration');
function gd_ure_authorsidebars_configuration($configuration) {

	// Merge in the configuration
	$panel_conf = array(
		'widget-class' => 'panel panel-default',
		'title-wrapper-class' => 'panel-heading',
		'title-class' => 'panel-title',
		'body-class' => 'panel-body'
	);
	$listgroup_conf = array_merge(
		$panel_conf,
		array(
			'body-class' => 'list-group',
			'body-wrapper' => 'list-group-item'
		)
	);
	$ure_components_conf = array(
		GD_URE_TEMPLATE_WIDGET_PROFILEORGANIZATION_DETAILS => array_merge(
			array(
				'menu-title' => __('Details', 'poptheme-wassup'),
				'fontawesome' => 'fa-info-circle'
			),
			$listgroup_conf
		),
		GD_URE_TEMPLATE_WIDGET_COMMUNITIES => array_merge(
			array(
				// 'menu-title' => __('Communities', 'poptheme-wassup'),
				'menu-title' => __('Member of', 'poptheme-wassup'),
				'fontawesome' => 'fa-institution'
			),
			$listgroup_conf
		),
		GD_URE_TEMPLATE_WIDGET_PROFILEINDIVIDUAL_DETAILS => array_merge(
			array(
				'menu-title' => __('Details', 'poptheme-wassup'),
				'fontawesome' => 'fa-info-circle'
			),
			$listgroup_conf
		)
	);
	$configuration = array_merge(
		$configuration,
		$ure_components_conf
	);

	// Override the title of the Contact info sidebar
	// $configuration[GD_TEMPLATE_WIDGETWRAPPER_AUTHOR_CONTACT]['menu-title'] = __('Contact info / Social media', 'poptheme-wassup');

	return $configuration;
}
add_filter('gd_template:sidebar_author:components', 'gd_ure_authorsidebars_components', 10, 2);
function gd_ure_authorsidebars_components($components, $section) {

	if (PoPTheme_Wassup_Utils::add_author_widget_details()) {

		$extra_components = array();
		if ($section == GD_SIDEBARSECTION_ORGANIZATION) {
			$extra_components[] = GD_URE_TEMPLATE_WIDGET_PROFILEORGANIZATION_DETAILS;
		}
		elseif ($section == GD_SIDEBARSECTION_INDIVIDUAL) {
			$extra_components[] = GD_URE_TEMPLATE_WIDGET_PROFILEINDIVIDUAL_DETAILS;
		}
		$extra_components[] = GD_URE_TEMPLATE_WIDGETWRAPPER_COMMUNITIES;

		// Embed the extra components
		array_splice($components, array_search(GD_TEMPLATE_VIEWCOMPONENT_BUTTON_USER_SENDMESSAGE_FULL, $components)+1, 0, $extra_components);
	}

	return $components;
}
// add_filter('gd_template:compact_sidebar_author:components', 'gd_ure_compactauthorsidebars_components', 10, 2);
// function gd_ure_compactauthorsidebars_components($components, $template_id) {

// 	$extra_components = array();

// 	if ($template_id == GD_TEMPLATE_SIDEBARMULTICOMPONENT_ORGANIZATION) {
// 		$extra_components[] = GD_URE_TEMPLATE_WIDGET_PROFILEORGANIZATION_DETAILS;
// 	}
// 	elseif ($template_id == GD_TEMPLATE_SIDEBARMULTICOMPONENT_INDIVIDUAL) {
// 		$extra_components[] = GD_URE_TEMPLATE_WIDGET_PROFILEINDIVIDUAL_DETAILS;
// 	}
// 	$extra_components[] = GD_URE_TEMPLATE_WIDGETWRAPPER_COMMUNITIES;

// 	// Embed the extra components
// 	array_splice($components, array_search(GD_TEMPLATE_QUICKLINKGROUP_USER, $components)+1, 0, $extra_components);

// 	return $components;
// }

/**---------------------------------------------------------------------------------------------------------------
 * Add the 'members' tab for the Organizations author page
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_Template_Processor_CustomSubMenus:author:blockgroupitems', 'gd_ure_profile_community_add_members_tab');
function gd_ure_profile_community_add_members_tab($blockunititems) {

	$vars = GD_TemplateManager_Utils::get_vars();
	$author = $vars['global-state']['author']/*global $author*/;
	if (gd_ure_is_community($author) && POP_URE_POPPROCESSORS_PAGE_MEMBERS) {
		
		// Place the Members tab before the Followers tab
		// unset($blockunititems[GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORFOLLOWERS]);
		$blockunititems[GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORMEMBERS] = array();
		// $blockunititems[GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORFOLLOWERS] = array();
	}

	return $blockunititems;
}

/**---------------------------------------------------------------------------------------------------------------
 * Add the 'members' tab for the Organizations author page
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_Template_Processor_SubMenus:get_blockgroupitems', 'gd_ure_profile_createprofiles_links');
function gd_ure_profile_createprofiles_links($blockunititems) {

	$blockunititems[GD_TEMPLATE_BLOCK_PROFILEINDIVIDUAL_CREATE] = array();
	$blockunititems[GD_TEMPLATE_BLOCK_PROFILEORGANIZATION_CREATE] = array();

	return $blockunititems;
}

/**---------------------------------------------------------------------------------------------------------------
 * Add the 'members' tab on the quicklinkbuttongroup
 * ---------------------------------------------------------------------------------------------------------------*/
// add_filter('GD_Template_Processor_CustomDropdownButtonQuicklinks:get_modules', 'gd_ure_userquicklinks_memberstab');
// function gd_ure_userquicklinks_memberstab($links) {

// 	$links[] = GD_URE_TEMPLATE_BUTTONWRAPPER_USERLINK_MEMBERS;

// 	return $links;
// }
