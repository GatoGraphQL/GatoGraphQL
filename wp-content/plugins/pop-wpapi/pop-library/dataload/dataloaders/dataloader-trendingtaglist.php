<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Users Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_TRENDINGTAGLIST', 'trendingtag-list');

class GD_DataLoader_TrendingTagList extends GD_DataLoader_TagList {

	function get_name() {
    
		return GD_DATALOADER_TRENDINGTAGLIST;
	}

	function get_query($vars = array()) {
	
		$query = parent::get_query($vars);

		$days = $vars['days'];

		// One Week by default for the trending topics
		$query['days'] = $days ? intval($days) : POP_WPAPI_DAYS_TRENDINGTAGS;
		
		return $query;
	}
	
	function execute_query_ids($query) {
    
    	// Solution to get the Trending Tags taken from https://wordpress.org/support/topic/limit-tags-by-date
    	global $wpdb;
		$days = $query['days'];
		$time_difference = get_option('gmt_offset') * HOUR_IN_SECONDS;
		$timenow = time() + $time_difference;
		$timelimit = $timenow - ($days * 24 * HOUR_IN_SECONDS);
		$now = gmdate('Y-m-d H:i:s', $timenow);
		$datelimit = gmdate('Y-m-d H:i:s', $timelimit);
		$sql = "SELECT $wpdb->terms.term_id, COUNT($wpdb->terms.term_id) as count FROM $wpdb->posts, $wpdb->term_relationships, $wpdb->term_taxonomy, $wpdb->terms WHERE $wpdb->posts.ID=$wpdb->term_relationships.object_id AND $wpdb->term_taxonomy.term_taxonomy_id=$wpdb->term_relationships.term_taxonomy_id AND $wpdb->term_taxonomy.term_id=$wpdb->terms.term_id AND post_status = 'publish' AND post_date < '$now' AND post_date > '$datelimit' AND $wpdb->term_taxonomy.taxonomy='post_tag' GROUP BY $wpdb->terms.term_id ORDER BY count DESC";

		// Use pagination if a limit was set
		if ($query['number'] && (intval($query['number']) > 0)) {

			$sql .= sprintf(
				' LIMIT %s',
				intval($query['number'])
			);
		}
		if ($query['offset'] && (intval($query['offset']) > 0)) {

			$sql .= sprintf(
				' OFFSET %s',
				intval($query['offset'])
			);
		}
		
		$ids = array();
		if ($results = $wpdb->get_results($sql)) {
			foreach ($results as $result) {
				$ids[] = $result->term_id;
			}
		}

		return $ids;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_TrendingTagList();