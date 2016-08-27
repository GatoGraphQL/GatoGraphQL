<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/


add_filter('gd_thumb_defaultlink:link_categories', 'organikprocessors_linkcategories');
add_filter('gd_main_category:skip', 'organikprocessors_linkcategories');
function organikprocessors_linkcategories($cats) {

	// The following categories can never be the main one
	return array_merge(
		$cats,
		array_filter(
			array(
				POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMLINKS,
			)
		)
	);
}


add_filter('gd_templatemanager:multilayout_labels', 'organikprocessors_multilayout_labels');
function organikprocessors_multilayout_labels($labels) {

	$label = '<span class="label label-%s">%s</span>';

	// If the values for the constants were kept in false (eg: Farms not needed for TPP Debate) then don't add them
	if (POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS) {

		$labels['post-'.POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS] = sprintf(
			$label,
			'farms',
			gd_navigation_menu_item(POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS, true).__('Farm', 'poptheme-wassup-organikprocessors')
		);
	}

	return $labels;
}

/**---------------------------------------------------------------------------------------------------------------
 * core.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_postname', 'organikprocessors_postname', 10, 2);
function organikprocessors_postname($name, $post_id) {

	if (get_post_type($post_id) == 'post') {

		$cat_id = gd_get_the_main_category($post_id);
		switch ($cat_id) {

			case POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS:
				return gd_get_categoryname($cat_id);
		}
	}

	return $name;
}
add_filter('gd_catname', 'organikprocessors_catname', 10, 3);
function organikprocessors_catname($name, $cat_id, $format) {

	switch ($cat_id) {

		case POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS:
			return ($format == 'plural' || $format == 'plural-lc') ? __('Farms', 'poptheme-wassup-organikprocessors') : __('Farm', 'poptheme-wassup-organikprocessors');
	}

	return $name;
}

add_filter('gd_posticon', 'organikprocessors_posticon', 10, 2);
function organikprocessors_posticon($icon, $post_id) {

	if (get_post_type($post_id) == 'post') {

		switch (gd_get_the_main_category($post_id)) {

			case POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS:
				return gd_navigation_menu_item(POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS, false);
		}
	}

	return $icon;
}


add_filter('gd_post_parentpageid', 'organikprocessors_post_parentpageid', 10, 2);
function organikprocessors_post_parentpageid($pageid, $post_id) {

	if (get_post_type($post_id) == 'post') {

		switch (gd_get_the_main_category($post_id)) {

			case POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS:
				return POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS;
		}
	}

	return $pageid;
}

/**---------------------------------------------------------------------------------------------------------------
 * createupdate-utils.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd-createupdateutils:cat:edit-url', 'op_createupdateutils_cat_edit_url', 10, 3);
function op_createupdateutils_cat_edit_url($url, $cat, $post_id) {

	$post = get_post($post_id);
	switch ($cat) {

		case POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS:
			
			if (POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMLINKS && has_category(POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMLINKS, $post)) {

				return get_permalink(POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_EDITFARMLINK);
			}
			return get_permalink(POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_EDITFARM);
	}

	return $url;
}