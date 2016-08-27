<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_templatemanager:multilayout_labels', 'votingprocessors_multilayout_labels');
function votingprocessors_multilayout_labels($labels) {

	$label = '<span class="label label-%s">%s</span>';
	return array_merge(
		array(
			'post-'.POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES => sprintf(
				$label,
				'opinionatedvotes',
				gd_navigation_menu_item(POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES, true).gd_get_categoryname(POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES)//__('Thought on TPP', 'poptheme-wassup-votingprocessors')
			),
		),
		$labels
	);
}

// add_filter('GD_Template_Processor_CustomCodes:updownvote:label', 'votingprocessors_updownvote_label');
// function votingprocessors_updownvote_label($label) {

// 	return sprintf(
// 		'<span class="pop-updownvote-label">%s</span><span class="pop-updownvote-opinionatedvotelabel">%s</span>',
// 		$label,
// 		__('Agree?', 'poptheme-wassup-votingprocessors')
// 	);
// }

/**---------------------------------------------------------------------------------------------------------------
 * core.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_catname', 'votingprocessors_catname', 10, 2);
function votingprocessors_catname($name, $cat_id) {

	switch ($cat_id) {

		case POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES:
			return __('Vote', 'poptheme-wassup-votingprocessors');
	}

	return $name;
}
add_filter('gd_format_catname', 'votingprocessors_format_catname', 10, 3);
function votingprocessors_format_catname($name, $post_id, $format) {

	switch ($cat_id) {

		case POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES:

			if ($format == 'lc') {
				return __('vote', 'poptheme-wassup-votingprocessors');
			}
			elseif ($format == 'plural-lc') {
				return __('votes', 'poptheme-wassup-votingprocessors');
			}
			break;
	}

	return $name;
}
add_filter('gd_postname', 'votingprocessors_postname', 10, 2);
function votingprocessors_postname($name, $post_id = null) {

	if (get_post_type($post_id) == 'post') {

		$cat_id = gd_get_the_main_category($post_id);
		switch ($cat_id) {

			case POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES:
				return gd_get_categoryname($cat_id);
		}
	}

	return $name;
}
add_filter('gd_format_postname', 'votingprocessors_format_postname', 10, 3);
function votingprocessors_format_postname($name, $post_id, $format) {

	if (get_post_type($post_id) == 'post') {

		$cat_id = gd_get_the_main_category($post_id);
		switch ($cat_id) {

			case POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES:

				if ($format == 'lc') {
					return gd_get_categoryname($cat_id, $format);
				}
				break;
		}
	}

	return $name;
}
add_filter('gd_posticon', 'votingprocessors_posticon', 10, 2);
function votingprocessors_posticon($icon, $post_id = null) {

	if (get_post_type($post_id) == 'post') {

		switch (gd_get_the_main_category($post_id)) {

			case POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES:
				return gd_navigation_menu_item(POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES, false);
		}
	}

	return $icon;
}

add_filter('gd_post_parentpageid', 'votingprocessors_post_parentpageid', 10, 2);
function votingprocessors_post_parentpageid($pageid, $post_id) {

	if (get_post_type($post_id) == 'post') {

		switch (gd_get_the_main_category($post_id)) {

			case POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES:
				return POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES;
		}
	}

	return $pageid;
}

/**---------------------------------------------------------------------------------------------------------------
 * createupdate-utils.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd-createupdateutils:cat:edit-url', 'votingprocessors_createupdateutils_cat_edit_url', 10, 2);
function votingprocessors_createupdateutils_cat_edit_url($url, $cat) {

	switch ($cat) {

		case POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES:
			$url = get_permalink(POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_EDITOPINIONATEDVOTE);
			break;
	}

	return $url;
}