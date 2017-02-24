<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_thumb_defaultlink:link_categories', 'categoryprocessors_skipcategories');
add_filter('gd_main_category:skip', 'categoryprocessors_skipcategories');
function categoryprocessors_skipcategories($cats) {

	// The following categories can never be the main one
	return array_merge(
		$cats,
		PoPTheme_Wassup_CategoryProcessors_ConfigUtils::get_cats(array(POP_CATEGORYPROCESSORS_CONFIGUTILS_WEBPOSTS))
	);
}

add_filter('gd_templatemanager:multilayout_labels', 'categoryprocessors_multilayout_labels');
function categoryprocessors_multilayout_labels($labels) {

	$label = '<span class="label label-%s">%s</span>';

	foreach (PoPTheme_Wassup_CategoryProcessors_ConfigUtils::get_cat_pages() as $cat => $page) {

		$category = get_category($cat);
		$labels['post-'.$cat] = sprintf(
			$label,
			$category->slug,
			gd_navigation_menu_item($page, true).gd_get_categoryname($cat)
		);
	}

	return $labels;
}

/**---------------------------------------------------------------------------------------------------------------
 * core.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_postname', 'categoryprocessors_postname', 10, 2);
function categoryprocessors_postname($name, $post_id) {

	if (get_post_type($post_id) == 'post') {

		// All categories in this plug-in are secondary to "Posts"
		if (gd_get_the_main_category($post_id) == POPTHEME_WASSUP_CAT_WEBPOSTS) {

			$cats = PoPTheme_Wassup_CategoryProcessors_ConfigUtils::get_cats(array(POP_CATEGORYPROCESSORS_CONFIGUTILS_WEBPOSTS));
			$post_cats = get_the_category($post_id);
			for ($i=0; $i < count($post_cats); $i++) { 
				$cat = $post_cats[$i];
				if (in_array($cat->term_id, $cats)) {
					return gd_get_categoryname($cat->term_id);
				}
			}
		}
	}

	return $name;
}


add_filter('gd_posticon', 'categoryprocessors_posticon', 10, 2);
function categoryprocessors_posticon($icon, $post_id) {

	if (get_post_type($post_id) == 'post') {

		// All categories in this plug-in are secondary to "Posts"
		if (gd_get_the_main_category($post_id) == POPTHEME_WASSUP_CAT_WEBPOSTS) {

			$cats = PoPTheme_Wassup_CategoryProcessors_ConfigUtils::get_cats(array(POP_CATEGORYPROCESSORS_CONFIGUTILS_WEBPOSTS));
			$post_cats = get_the_category($post_id);
			for ($i=0; $i < count($post_cats); $i++) { 
				$cat = $post_cats[$i];
				if (in_array($cat->term_id, $cats)) {

					$cat_pages = PoPTheme_Wassup_CategoryProcessors_ConfigUtils::get_cat_pages();
					return gd_navigation_menu_item($cat_pages[$cat->term_id], false);
				}
			}
		}
	}

	return $icon;
}

add_filter('gd_post_parentpageid', 'categoryprocessors_post_parentpageid', 10, 2);
function categoryprocessors_post_parentpageid($pageid, $post_id) {

	if (get_post_type($post_id) == 'post') {

		$cat_id = gd_get_the_main_category($post_id);
		if (in_array($cat_id, PoPTheme_Wassup_CategoryProcessors_ConfigUtils::get_cats(array(POP_CATEGORYPROCESSORS_CONFIGUTILS_WEBPOSTS)))) {

			$cat_pages = PoPTheme_Wassup_CategoryProcessors_ConfigUtils::get_cat_pages(array(POP_CATEGORYPROCESSORS_CONFIGUTILS_WEBPOSTS));
			return $cat_pages[$cat_id];
		}
	}

	return $pageid;
}