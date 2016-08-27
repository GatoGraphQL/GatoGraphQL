<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Events Manager plugin Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('GD_Template_CacheProcessor:add_categories', 'gd_em_cacheprocessor_addcategories', 10, 2);
function gd_em_cacheprocessor_addcategories($filename, $post) {

	if ($post->post_type == EM_POST_TYPE_EVENT) {

		$event = em_get_event($post->ID, 'post_id');
		$cats = $event->get_categories()->categories;
		foreach ($cats as $cat) {
			$filename .= '_'.str_replace('-', '', $cat->output('#_CATEGORYSLUG')).$cat->output('#_CATEGORYID');
		}
	}

	return $filename;
}