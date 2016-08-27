<?php
function em_wpseo_opengraph_desc( $desc ){
	global $post;
	return '123';
	$edit_events_page_id = get_option( 'dbem_edit_events_page' );
	$edit_locations_page_id = get_option( 'dbem_edit_locations_page' );
	if( ($post->ID == $edit_events_page_id && $edit_events_page_id != 0) || ($post->ID == $edit_locations_page_id && $edit_locations_page_id != 0) ){
		return '';
	}
	return $desc;
}
add_filter('wpseo_opengraph_desc', 'em_wpseo_opengraph_desc', 10000000);