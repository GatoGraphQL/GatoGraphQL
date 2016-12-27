<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_DataLoad_IOHandler_Query extends GD_DataLoad_IOHandler_Block {

    function get_vars($atts, $iohandler_atts) {
    
		$ret = parent::get_vars($atts, $iohandler_atts);

		$paged = $atts[GD_URLPARAM_PAGED];
		$limit = $atts[GD_URLPARAM_LIMIT];
		// $timestamp = $atts[GD_URLPARAM_TIMESTAMP];

		// If limit passed explicitly from the block data-setting, then use that one
		// if ($iohandler_atts[GD_URLPARAM_LIMIT]) {

		// 	$limit = $iohandler_atts[GD_URLPARAM_LIMIT];
		// }

		// Do not allow more than 10 times the set amount (so that hackers cannot bring the website down)
		$posts_per_page = get_option('posts_per_page');
		if ($limit > $posts_per_page * 10) {
			$limit = $posts_per_page * 10;
		}

		$ret[GD_URLPARAM_PAGED] = $paged ? intval($paged) : 1;
		// Allow for Limit to be 0 (eg: Events Calendar), in that case it's valid, keep it
		$ret[GD_URLPARAM_LIMIT] = $limit ? intval($limit) : $posts_per_page; 

		// Timestamp needed for the loadLatest block
		// $ret[GD_URLPARAM_TIMESTAMP] = $timestamp ? $timestamp : POP_CONSTANT_CURRENTTIMESTAMP;//current_time('timestamp'); 
		$ret[GD_URLPARAM_TIMESTAMP] = $atts[GD_URLPARAM_TIMESTAMP]; 

		if ($atts['post-status']) {
			$ret['post-status'] = $atts['post-status'];
		}

		return $ret;
	}

}
