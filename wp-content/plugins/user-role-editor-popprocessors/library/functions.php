<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * URE Library functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_author_parentpageid', 'gd_ure_author_parentpageid_impl', 10, 2);
function gd_ure_author_parentpageid_impl($pageid, $author_id = null) {

	if (is_null($author_id)) {
		global $author;
		$author_id = $author;
	}

	if (gd_ure_is_organization($author_id)) {
		return POP_URE_POPPROCESSORS_PAGE_ORGANIZATIONS;
	}
	elseif (gd_ure_is_individual($author_id)) {
		return POP_URE_POPPROCESSORS_PAGE_INDIVIDUALS;
	}

	return $pageid;
}

/**---------------------------------------------------------------------------------------------------------------
 * login.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_get_login_html', 'gd_ure_get_login_html', 10, 2);
function gd_ure_get_login_html($html, $capitalize = false) {

	$li = '<li role="presentation"><a href="%s">%s</a></li>';
	return 
		sprintf(
			'<span class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">%s<span class="caret"></span></a>%s</span>',
			$capitalize ? __('Log in', 'ure-popprocessors') : __('log in', 'ure-popprocessors'),
			sprintf(
				'<ul class="dropdown-menu login" role="menu">%s%s%s</ul>',
				sprintf(
					$li,
					wp_login_url(),
					get_the_title(POP_WPAPI_PAGE_LOGIN)
				),
				sprintf(
					$li,
					get_permalink(POP_URE_POPPROCESSORS_PAGE_ADDPROFILEORGANIZATION),
					get_the_title(POP_URE_POPPROCESSORS_PAGE_ADDPROFILEORGANIZATION)
				),
				sprintf(
					$li,
					get_permalink(POP_URE_POPPROCESSORS_PAGE_ADDPROFILEINDIVIDUAL),
					get_the_title(POP_URE_POPPROCESSORS_PAGE_ADDPROFILEINDIVIDUAL)
				)
			)
		);
}


/**---------------------------------------------------------------------------------------------------------------
 * Add the filtercomponents to all filters
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_template:filter-users:filtercomponents', 'gd_ure_add_filtercomponent_communities_user');
function gd_ure_add_filtercomponent_communities_user($filtercomponents) {

	// Always add it after $gd_filtercomponent_name
	global $gd_ure_filtercomponent_communities_user, $gd_filtercomponent_name;
	array_splice($filtercomponents, array_search($gd_filtercomponent_name, $filtercomponents)+1, 0, array($gd_ure_filtercomponent_communities_user));
	return $filtercomponents;
}
add_filter('gd_template:filter-posts:filtercomponents', 'gd_ure_add_filtercomponent_communities_post');
function gd_ure_add_filtercomponent_communities_post($filtercomponents) {

	// Always add it after $gd_filtercomponent_profiles
	global $gd_filtercomponent_profiles, $gd_ure_filtercomponent_profiles_post, $gd_ure_filtercomponent_communities_post;
	// Place the 'communities' component before the 'profiles' one, so that we can use {{lastGeneratedId}} to reference it
	// Also replace the original 'profiles' component with the new one, so we can add the extra-templates attr into the selected typeahead template
	array_splice($filtercomponents, array_search($gd_filtercomponent_profiles, $filtercomponents), 1, array($gd_ure_filtercomponent_communities_post, $gd_ure_filtercomponent_profiles_post));
	return $filtercomponents;
}

// Add the author users filtercomponent on the Community author page
add_filter('gd_template:filter-authorposts:filtercomponents', 'gd_ure_add_filtercomponent_communityusers');
function gd_ure_add_filtercomponent_communityusers($filtercomponents) {

	global $author;

	// Check if the user is showing the community. If showing organization, then no need for this
	$vars = GD_TemplateManager_Utils::get_vars();
	if (gd_ure_is_community($author) && $vars['source'] == GD_URLPARAM_URECONTENTSOURCE_COMMUNITY) {

		// Add it after the search
		global $gd_filtercomponent_search, $gd_ure_filtercomponent_communities_post, $gd_ure_filtercomponent_communityusers;
		array_splice($filtercomponents, array_search($gd_filtercomponent_search, $filtercomponents)+1, 0, array($gd_ure_filtercomponent_communities_post, $gd_ure_filtercomponent_communityusers));
	}
	return $filtercomponents;
}

/**---------------------------------------------------------------------------------------------------------------
 * Allow organizations to include its members' content in the Organization Profile
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_template:dataload_query_args:authors', 'gd_ure_profile_organization_dataloadquery_addmembers');
function gd_ure_profile_organization_dataloadquery_addmembers($authors) {

	global $author;
	// Check if the user is showing the community. If showing organization, then no need for this
	$vars = GD_TemplateManager_Utils::get_vars();
	if (gd_ure_is_community($author) && $vars['source'] == GD_URLPARAM_URECONTENTSOURCE_COMMUNITY) {
		
		$authors = array_merge(
			$authors,
			gd_ure_get_activecontributingcontentcommunitymembers($author)
		);
	}

	return $authors;
}


