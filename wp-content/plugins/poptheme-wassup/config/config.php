<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_templatemanager:userloggedin_loadingmsg_target', 'gd_custom_userloggedin_loadingmsg_target');
function gd_custom_userloggedin_loadingmsg_target($target) {

	// Element placed in frame-top.tmpl
	return '#account-loading-msg';
}

add_filter('gd_main_category:skip', 'wassup_main_linkcategories');
function wassup_main_linkcategories($cats) {

	// The following categories can never be the main one
	return array_merge(
		$cats,
		array_filter(
			array(
				POPTHEME_WASSUP_CAT_WEBPOSTLINKS,
			)
		)
	);
}

add_filter('gd_templatemanager:multilayout_labels', 'wassup_multilayout_labels');
function wassup_multilayout_labels($labels) {

	$label = '<span class="label label-%s">%s</span>';
	return array_merge(
		array(
			// 'post-'.POPTHEME_WASSUP_CAT_WEBPOSTLINKS => sprintf(
			// 	$label,
			// 	'links',
			// 	gd_navigation_menu_item(POPTHEME_WASSUP_PAGE_WEBPOSTLINKS, true).__('Link', 'poptheme-wassup')
			// ),
			'post-'.POPTHEME_WASSUP_CAT_HIGHLIGHTS => sprintf(
				$label,
				'highlights',
				gd_navigation_menu_item(POPTHEME_WASSUP_PAGE_HIGHLIGHTS, true).__('Highlight', 'poptheme-wassup')
			),
			'post-'.POPTHEME_WASSUP_CAT_WEBPOSTS => sprintf(
				$label,
				'webposts',
				gd_navigation_menu_item(POPTHEME_WASSUP_PAGE_WEBPOSTS, true).__('Post', 'poptheme-wassup')
			),
		),
		$labels
	);
}

/**---------------------------------------------------------------------------------------------------------------
 * core.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_postname', 'wassup_postname', 10, 2);
function wassup_postname($name, $post_id) {

	if (get_post_type($post_id) == 'post') {
		
		switch (gd_get_the_main_category($post_id)) {

			case POPTHEME_WASSUP_CAT_WEBPOSTLINKS:
				return __('Link', 'poptheme-wassup');

			case POPTHEME_WASSUP_CAT_HIGHLIGHTS:
				return __('Highlight', 'poptheme-wassup');

			case POPTHEME_WASSUP_CAT_WEBPOSTS:
				return __('Post', 'poptheme-wassup');
		}
	}

	return $name;
}

add_filter('gd_posticon', 'wassup_posticon', 10, 2);
function wassup_posticon($icon, $post_id) {

	if (get_post_type($post_id) == 'post') {
		
		switch (gd_get_the_main_category($post_id)) {

			// case POPTHEME_WASSUP_CAT_WEBPOSTLINKS:
			// 	return gd_navigation_menu_item(POPTHEME_WASSUP_PAGE_WEBPOSTLINKS, false);

			case POPTHEME_WASSUP_CAT_HIGHLIGHTS:
				return gd_navigation_menu_item(POPTHEME_WASSUP_PAGE_HIGHLIGHTS, false);

			case POPTHEME_WASSUP_CAT_WEBPOSTS:
				return gd_navigation_menu_item(POPTHEME_WASSUP_PAGE_WEBPOSTS, false);
		}
	}

	return $icon;
}

add_filter('gd_post_parentpageid', 'wassup_post_parentpageid', 10, 2);
function wassup_post_parentpageid($pageid, $post_id) {

	if (get_post_type($post_id) == 'post') {

		switch (gd_get_the_main_category($post_id)) {

			// case POPTHEME_WASSUP_CAT_WEBPOSTLINKS:
			// 	return POPTHEME_WASSUP_PAGE_WEBPOSTLINKS;

			case POPTHEME_WASSUP_CAT_HIGHLIGHTS:
				return POPTHEME_WASSUP_PAGE_HIGHLIGHTS;

			case POPTHEME_WASSUP_CAT_WEBPOSTS:
				return POPTHEME_WASSUP_PAGE_WEBPOSTS;
		}
	}

	return $pageid;
}

/**---------------------------------------------------------------------------------------------------------------
 * createupdate-utils.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd-createupdateutils:cat:edit-url', 'wassup_createupdateutils_cat_edit_url', 10, 3);
function wassup_createupdateutils_cat_edit_url($url, $cat, $post_id) {

	$post = get_post($post_id);
	switch ($cat) {

		case POPTHEME_WASSUP_CAT_HIGHLIGHTS:
			return get_permalink(POPTHEME_WASSUP_PAGE_EDITHIGHLIGHT);

		case POPTHEME_WASSUP_CAT_WEBPOSTS:

			if (POPTHEME_WASSUP_CAT_WEBPOSTLINKS && has_category(POPTHEME_WASSUP_CAT_WEBPOSTLINKS, $post)) {

				return get_permalink(POPTHEME_WASSUP_PAGE_EDITWEBPOSTLINK);
			}
			return get_permalink(POPTHEME_WASSUP_PAGE_EDITWEBPOST);
	}

	return $url;
}