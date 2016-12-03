<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/


add_filter('gd_thumb_defaultlink:link_categories', 'wassupsp_main_linkcategories');
add_filter('gd_main_category:skip', 'wassupsp_main_linkcategories');
function wassupsp_main_linkcategories($cats) {

	// The following categories can never be the main one
	return array_merge(
		$cats,
		array_filter(
			array(
				POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTLINKS,
				
				POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES,
				POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORYLINKS,
				POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS,
				POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONLINKS,
				POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS,
				POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTLINKS,
			)
		)
	);
}


add_filter('gd_templatemanager:multilayout_labels', 'gd_custom_multilayout_labels');
function gd_custom_multilayout_labels($labels) {

	$label = '<span class="label label-%s">%s</span>';

	// If the values for the constants were kept in false (eg: Projects not needed for TPP Debate) then don't add them
	// if (POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS) {

	// 	$labels['post-'.POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS] = sprintf(
	// 		$label,
	// 		'locationposts',
	// 		gd_navigation_menu_item(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_LOCATIONPOSTS, true).__('Location post', 'poptheme-wassup-sectionprocessors')
	// 	);
	// }
	$cat_pages = array(
		POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_LOCATIONPOSTS,
	);
	foreach ($cat_pages as $cat => $page) {

		$category = get_category($cat);
		$labels['post-'.$cat] = sprintf(
			$label,
			$category->slug,
			gd_navigation_menu_item($page, true).gd_get_categoryname($cat)
		);
	}
	if (POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES) {
		$labels['post-'.POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES] = sprintf(
			$label,
			'stories',
			gd_navigation_menu_item(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES, true).__('Story', 'poptheme-wassup-sectionprocessors')
		);
	}
	if (POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS) {
		$labels['post-'.POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS] = sprintf(
			$label,
			'announcements',
			gd_navigation_menu_item(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS, true).__('Announcement', 'poptheme-wassup-sectionprocessors')
		);
	}
	if (POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS) {
		$labels['post-'.POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS] = sprintf(
			$label,
			'articles',//'discussions',
			gd_navigation_menu_item(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS, true).__('Article', 'poptheme-wassup-sectionprocessors')
		);
	}
	if (POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED) {
		$labels['post-'.POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED] = sprintf(
			$label,
			'featured',
			gd_navigation_menu_item(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FEATURED, true).__('Featured', 'poptheme-wassup-sectionprocessors')
		);
	}

	return $labels;
}

/**---------------------------------------------------------------------------------------------------------------
 * core.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_postname', 'gd_postname_impl', 10, 2);
function gd_postname_impl($name, $post_id) {

	if (get_post_type($post_id) == 'post') {

		$cat_id = gd_get_the_main_category($post_id);
		switch ($cat_id) {

			case POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED:
			case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_BLOG:
				return gd_get_categoryname($cat_id);
		}
	}

	return $name;
}
add_filter('gd_catname', 'sectionprocessors_catname', 10, 3);
function sectionprocessors_catname($name, $cat_id, $format) {

	switch ($cat_id) {

		// Comment Leo 03/12/2016: this must be set by the theme, since changing from 'Project' to the abstract 'Location post'
		// case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS:
		// 	return ($format == 'plural' || $format == 'plural-lc') ? __('Location posts', 'poptheme-wassup-sectionprocessors') : __('Location post', 'poptheme-wassup-sectionprocessors');

		case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES:
			return ($format == 'plural' || $format == 'plural-lc') ? __('Stories', 'poptheme-wassup-sectionprocessors') : __('Story', 'poptheme-wassup-sectionprocessors');

		case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS:
			return ($format == 'plural' || $format == 'plural-lc') ? __('Announcements', 'poptheme-wassup-sectionprocessors') : __('Announcement', 'poptheme-wassup-sectionprocessors');

		case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS:
			return ($format == 'plural' || $format == 'plural-lc') ? __('Articles', 'poptheme-wassup-sectionprocessors') : __('Article', 'poptheme-wassup-sectionprocessors');

		case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED:
			return __('Featured', 'poptheme-wassup-sectionprocessors');

		case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_BLOG:
			return __('Blog', 'poptheme-wassup-sectionprocessors');
	}

	return $name;
}

add_filter('gd_posticon', 'gd_posticon_impl', 10, 2);
function gd_posticon_impl($icon, $post_id) {

	if (get_post_type($post_id) == 'post') {

		switch (gd_get_the_main_category($post_id)) {

			case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS:
				return gd_navigation_menu_item(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_LOCATIONPOSTS, false);

			case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES:
				return gd_navigation_menu_item(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES, false);

			case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS:
				return gd_navigation_menu_item(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS, false);

			case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS:
				return gd_navigation_menu_item(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS, false);

			case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED:
				return gd_navigation_menu_item(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FEATURED, false);

			case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_BLOG:
				return gd_navigation_menu_item(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG, false);
		}
	}

	return $icon;
}


add_filter('gd_post_parentpageid', 'gd_post_parentpageid_impl', 10, 2);
function gd_post_parentpageid_impl($pageid, $post_id) {

	if (get_post_type($post_id) == 'post') {

		switch (gd_get_the_main_category($post_id)) {

			case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS:
				return POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_LOCATIONPOSTS;

			case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES:
				return POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES;

			case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS:
				return POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS;

			case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS:
				return POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS;

			case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED:
				return POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FEATURED;
		}
	}

	return $pageid;
}

/**---------------------------------------------------------------------------------------------------------------
 * createupdate-utils.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd-createupdateutils:cat:edit-url', 'gd_createupdateutils_cat_edit_url', 10, 3);
function gd_createupdateutils_cat_edit_url($url, $cat, $post_id) {

	$post = get_post($post_id);
	switch ($cat) {

		case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS:
			
			if (POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTLINKS && has_category(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTLINKS, $post)) {

				return get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITLOCATIONPOSTLINK);
			}
			return get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITLOCATIONPOST);

		case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES:
			
			if (POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORYLINKS && has_category(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORYLINKS, $post)) {

				return get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITSTORYLINK);
			}
			return get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITSTORY);

		case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS:
			
			if (POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTLINKS && has_category(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTLINKS, $post)) {

				return get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITANNOUNCEMENTLINK);
			}
			return get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITANNOUNCEMENT);

		case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS:
			
			if (POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONLINKS && has_category(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONLINKS, $post)) {

				return get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITDISCUSSIONLINK);
			}
			return get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITDISCUSSION);

		case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED:
			
			return get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_EDITFEATURED);
	}

	return $url;
}