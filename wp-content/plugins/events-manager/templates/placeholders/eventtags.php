<?php
/* @var $EM_Event EM_Event */
$tags = get_the_terms($EM_Event->post_id, EM_TAXONOMY_TAG);
if( is_array($tags) && count($tags) > 0 ){
	$tags_list = array();
	foreach($tags as $tag){ 
		$link = get_term_link($tag->slug, EM_TAXONOMY_TAG);
		if ( is_wp_error($link) ) $link = '';
		$tags_list[] = '<a href="'. $link .'">'. $tag->name .'</a>';
	}
	echo implode(', ', $tags_list);
}