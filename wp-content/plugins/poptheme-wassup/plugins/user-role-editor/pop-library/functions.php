<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * User Role Editor plugin Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Add the source param whenever in an author
add_filter('GD_Template_Processor_CustomSubMenus:get_blockunititem_url:author', 'gd_ure_add_source_param_to_submenu', 10, 3);
function gd_ure_add_source_param_to_submenu($url, $user_id, $blockunit) {

	// Add for all the content blockunits: all of them except Description and Members
	$skip = array(
		GD_TEMPLATE_BLOCKGROUP_AUTHORDESCRIPTION,
		GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORMEMBERS,
		GD_TEMPLATE_BLOCKGROUP_TABPANEL_AUTHORFOLLOWERS,
	);
	if (in_array($blockunit, $skip)) {

		return $url;
	}

	return gd_ure_add_source_param($url, $user_id);
}

add_filter('GD_Template_Processor_PageTabPageSections:get_extra_intercept_urls:author', 'gd_ure_add_source_param_pagesections');
add_filter('GD_Template_Processor_TabPanePageSections:get_extra_intercept_urls:author', 'gd_ure_add_source_param_pagesections');
function gd_ure_add_source_param_pagesections($url) {

	$vars = GD_TemplateManager_Utils::get_vars();
	$author = $vars['global-state']['author']/*global $author*/;
	return gd_ure_add_source_param($url, $author);
}

/**---------------------------------------------------------------------------------------------------------------
 * Navigator blocks
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_Template_Processor_SideTabPanelBlockGroups:navigator:blocks', 'gd_ure_navigatorblocks');
function gd_ure_navigatorblocks($blocks) {

	return array_merge(
		$blocks,
		array(
			GD_TEMPLATE_BLOCK_ORGANIZATIONS_SCROLL_NAVIGATOR,
			GD_TEMPLATE_BLOCK_INDIVIDUALS_SCROLL_NAVIGATOR,
		)
	);
}

/**---------------------------------------------------------------------------------------------------------------
 * User Dataloader: allow to select users only with at least role GD_ROLE_PROFILE
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_dataload_query:'.GD_DATALOADER_USERLIST, 'gd_ure_userlist_query');
// add_filter('gd_dataload_query:'.GD_DATALOADER_SECONDUSERLIST, 'gd_ure_userlist_query');
// add_filter('gd_dataload_query:'.GD_URE_DATALOADER_COMMUNITY_USERLIST, 'gd_ure_userlist_query');
function gd_ure_userlist_query($query) {

	// The role can only be Profile (Organization + Individual), force there's no other (protection against hackers).
	$roles = gd_roles();
	if (!in_array($query['role'], $roles)) {
	
		$query['role'] = GD_ROLE_PROFILE;
	}

	return $query;
}


/**---------------------------------------------------------------------------------------------------------------
 * Override the ActionExecuters with the local ones
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_URE_Template_Processor_CreateUpdateProfileActions:get_actionexecuter:profileorganization', 'gd_ure_custom_actionexecuter_organization');
function gd_ure_custom_actionexecuter_organization($actionexecuter) {

	return GD_CUSTOM_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_PROFILEORGANIZATION;
}

add_filter('GD_URE_Template_Processor_CreateUpdateProfileActions:get_actionexecuter:profileindividual', 'gd_ure_custom_actionexecuter_individual');
function gd_ure_custom_actionexecuter_individual($actionexecuter) {

	return GD_CUSTOM_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_PROFILEINDIVIDUAL;
}

/**---------------------------------------------------------------------------------------------------------------
 * Override the FormInners with the local ones
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_URE_Template_Processor_UpdateProfileForms:get_inner_template:profileorganization', 'gd_ure_custom_updateforminner_organization');
function gd_ure_custom_updateforminner_organization($forminner) {

	return GD_CUSTOM_TEMPLATE_FORMINNER_PROFILEORGANIZATION_UPDATE;
}

add_filter('GD_URE_Template_Processor_UpdateProfileForms:get_inner_template:profileindividual', 'gd_ure_custom_updateforminner_individual');
function gd_ure_custom_updateforminner_individual($forminner) {

	return GD_CUSTOM_TEMPLATE_FORMINNER_PROFILEINDIVIDUAL_UPDATE;
}
add_filter('GD_URE_Template_Processor_CreateProfileForms:get_inner_template:profileorganization', 'gd_ure_custom_createforminner_organization');
function gd_ure_custom_createforminner_organization($forminner) {

	return GD_CUSTOM_TEMPLATE_FORMINNER_PROFILEORGANIZATION_CREATE;
}

add_filter('GD_URE_Template_Processor_CreateProfileForms:get_inner_template:profileindividual', 'gd_ure_custom_createforminner_individual');
function gd_ure_custom_createforminner_individual($forminner) {

	return GD_CUSTOM_TEMPLATE_FORMINNER_PROFILEINDIVIDUAL_CREATE;
}

/**---------------------------------------------------------------------------------------------------------------
 * Add filtercomponents
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_ure_template:filter-individuals:filtercomponents', 'gd_ure_add_filtercomponent_individuals');
function gd_ure_add_filtercomponent_individuals($filtercomponents) {

	global $gd_ure_filtercomponent_communities_user, $gd_ure_filtercomponent_individualinterests;
	array_splice($filtercomponents, array_search($gd_ure_filtercomponent_communities_user, $filtercomponents)+1, 0, array($gd_ure_filtercomponent_individualinterests));
	return $filtercomponents;
}
add_filter('gd_ure_template:filter-organizations:filtercomponents', 'gd_ure_add_filtercomponent_organizations');
function gd_ure_add_filtercomponent_organizations($filtercomponents) {

	global $gd_ure_filtercomponent_communities_user, $gd_ure_filtercomponent_organizationtypes, $gd_ure_filtercomponent_organizationcategories;
	array_splice($filtercomponents, array_search($gd_ure_filtercomponent_communities_user, $filtercomponents)+1, 0, array($gd_ure_filtercomponent_organizationtypes, $gd_ure_filtercomponent_organizationcategories));
	return $filtercomponents;
}

/**---------------------------------------------------------------------------------------------------------------
 * Layout templates
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_Template_Processor_MultipleUserLayouts:layouts:popover', 'gd_ure_layouttemplates_popover');
function gd_ure_layouttemplates_popover($layouts) {

	return array_merge(
		$layouts,
		array(
			GD_ROLE_PROFILE => GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_POPOVER,
			GD_URE_ROLE_ORGANIZATION => GD_TEMPLATE_LAYOUT_PREVIEWUSER_ORGANIZATION_POPOVER,
			GD_URE_ROLE_INDIVIDUAL => GD_TEMPLATE_LAYOUT_PREVIEWUSER_INDIVIDUAL_POPOVER,
		)
	);
}
add_filter('GD_Template_Processor_MultipleUserLayouts:layouts:postauthor', 'gd_ure_layouttemplates_postauthor');
function gd_ure_layouttemplates_postauthor($layouts) {

	return array_merge(
		$layouts,
		array(
			GD_ROLE_PROFILE => GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_POSTAUTHOR,
			GD_URE_ROLE_ORGANIZATION => GD_TEMPLATE_LAYOUT_PREVIEWUSER_ORGANIZATION_POSTAUTHOR,
			GD_URE_ROLE_INDIVIDUAL => GD_TEMPLATE_LAYOUT_PREVIEWUSER_INDIVIDUAL_POSTAUTHOR,
		)
	);
}
add_filter('GD_Template_Processor_MultipleUserLayouts:layouts:navigator', 'gd_ure_layouttemplates_navigator');
function gd_ure_layouttemplates_navigator($layouts) {

	return array_merge(
		$layouts,
		array(
			GD_ROLE_PROFILE => GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_NAVIGATOR,
			GD_URE_ROLE_ORGANIZATION => GD_TEMPLATE_LAYOUT_PREVIEWUSER_ORGANIZATION_NAVIGATOR,
			GD_URE_ROLE_INDIVIDUAL => GD_TEMPLATE_LAYOUT_PREVIEWUSER_INDIVIDUAL_NAVIGATOR,
		)
	);
}
add_filter('GD_Template_Processor_MultipleUserLayouts:layouts:addons', 'gd_ure_layouttemplates_addons');
function gd_ure_layouttemplates_addons($layouts) {

	return array_merge(
		$layouts,
		array(
			GD_ROLE_PROFILE => GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_ADDONS,
			GD_URE_ROLE_ORGANIZATION => GD_TEMPLATE_LAYOUT_PREVIEWUSER_ORGANIZATION_ADDONS,
			GD_URE_ROLE_INDIVIDUAL => GD_TEMPLATE_LAYOUT_PREVIEWUSER_INDIVIDUAL_ADDONS,
		)
	);
}
add_filter('GD_Template_Processor_MultipleUserLayouts:layouts:details', 'gd_ure_layouttemplates_details');
function gd_ure_layouttemplates_details($layouts) {

	return array_merge(
		$layouts,
		array(
			GD_ROLE_PROFILE => GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_DETAILS,
			GD_URE_ROLE_ORGANIZATION => GD_TEMPLATE_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS,
			GD_URE_ROLE_INDIVIDUAL => GD_TEMPLATE_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS,
		)
	);
}
add_filter('GD_Template_Processor_MultipleUserLayouts:layouts:thumbnail', 'gd_ure_layouttemplates_thumbnail');
function gd_ure_layouttemplates_thumbnail($layouts) {

	return array_merge(
		$layouts,
		array(
			GD_ROLE_PROFILE => GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_THUMBNAIL,
			GD_URE_ROLE_ORGANIZATION => GD_TEMPLATE_LAYOUT_PREVIEWUSER_ORGANIZATION_THUMBNAIL,
			GD_URE_ROLE_INDIVIDUAL => GD_TEMPLATE_LAYOUT_PREVIEWUSER_INDIVIDUAL_THUMBNAIL,
		)
	);
}
add_filter('GD_Template_Processor_MultipleUserLayouts:layouts:list', 'gd_ure_layouttemplates_list');
function gd_ure_layouttemplates_list($layouts) {

	return array_merge(
		$layouts,
		array(
			GD_ROLE_PROFILE => GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_LIST,
			GD_URE_ROLE_ORGANIZATION => GD_TEMPLATE_LAYOUT_PREVIEWUSER_ORGANIZATION_LIST,
			GD_URE_ROLE_INDIVIDUAL => GD_TEMPLATE_LAYOUT_PREVIEWUSER_INDIVIDUAL_LIST,
		)
	);
}
add_filter('GD_Template_Processor_MultipleUserLayouts:layouts:fulluser', 'gd_ure_layouttemplates_fulluser');
function gd_ure_layouttemplates_fulluser($layouts) {

	return array_merge(
		$layouts,
		array(
			GD_ROLE_PROFILE => GD_TEMPLATE_LAYOUT_FULLUSER_PROFILE,
			GD_URE_ROLE_ORGANIZATION => GD_TEMPLATE_LAYOUT_FULLUSER_ORGANIZATION,
			GD_URE_ROLE_INDIVIDUAL => GD_TEMPLATE_LAYOUT_FULLUSER_INDIVIDUAL,
		)
	);
}
add_filter('GD_Template_Processor_MultipleUserLayouts:layouts:authordetails', 'gd_ure_layouttemplates_authordetails');
function gd_ure_layouttemplates_authordetails($layouts) {

	return array_merge(
		$layouts,
		array(
			GD_ROLE_PROFILE => GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_DETAILS,
			GD_URE_ROLE_ORGANIZATION => GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_ORGANIZATION_DETAILS,
			GD_URE_ROLE_INDIVIDUAL => GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_INDIVIDUAL_DETAILS,
		)
	);
}
add_filter('GD_Template_Processor_MultipleUserLayouts:layouts:authorthumbnail', 'gd_ure_layouttemplates_authorthumbnail');
function gd_ure_layouttemplates_authorthumbnail($layouts) {

	return array_merge(
		$layouts,
		array(
			GD_ROLE_PROFILE => GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_THUMBNAIL,
			GD_URE_ROLE_ORGANIZATION => GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_ORGANIZATION_THUMBNAIL,
			GD_URE_ROLE_INDIVIDUAL => GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_INDIVIDUAL_THUMBNAIL,
		)
	);
}
add_filter('GD_Template_Processor_MultipleUserLayouts:layouts:authorlist', 'gd_ure_layouttemplates_authorlist');
function gd_ure_layouttemplates_authorlist($layouts) {

	return array_merge(
		$layouts,
		array(
			GD_ROLE_PROFILE => GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_LIST,
			GD_URE_ROLE_ORGANIZATION => GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_ORGANIZATION_LIST,
			GD_URE_ROLE_INDIVIDUAL => GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_INDIVIDUAL_LIST,
		)
	);
}
add_filter('GD_Template_Processor_MultipleUserLayouts:layouts:authorfulluser', 'gd_ure_layouttemplates_authorfulluser');
function gd_ure_layouttemplates_authorfulluser($layouts) {

	return array_merge(
		$layouts,
		array(
			GD_ROLE_PROFILE => GD_TEMPLATE_AUTHORLAYOUT_FULLUSER_PROFILE,
			GD_URE_ROLE_ORGANIZATION => GD_TEMPLATE_AUTHORLAYOUT_FULLUSER_ORGANIZATION,
			GD_URE_ROLE_INDIVIDUAL => GD_TEMPLATE_AUTHORLAYOUT_FULLUSER_INDIVIDUAL,
		)
	);
}
add_filter('GD_Template_Processor_MultipleUserLayouts:layouts:singlefulluser', 'gd_ure_layouttemplates_singlefulluser');
function gd_ure_layouttemplates_singlefulluser($layouts) {

	return array_merge(
		$layouts,
		array(
			GD_ROLE_PROFILE => GD_TEMPLATE_SINGLELAYOUT_FULLUSER_PROFILE,
			GD_URE_ROLE_ORGANIZATION => GD_TEMPLATE_SINGLELAYOUT_FULLUSER_ORGANIZATION,
			GD_URE_ROLE_INDIVIDUAL => GD_TEMPLATE_SINGLELAYOUT_FULLUSER_INDIVIDUAL,
		)
	);
}