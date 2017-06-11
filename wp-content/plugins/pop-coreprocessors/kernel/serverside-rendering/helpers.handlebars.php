<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-frontendengine/js/helpers.handlebars.js
*/
class PoP_Core_ServerSide_Helpers {

	function latestCountTargets($itemObject, $options) {

	    // Build the notification prompt targets, based on the data on the itemObject
	    $targets = array();
	    $selector = '.pop-block.'.GD_JS_INITIALIZED.' > .blocksection-latestcount > .pop-latestcount';

	    // By Sections (post type . categories)
	    $post_type = $itemObject['post-type'];
	    $cats = $itemObject['cats'];
	    if ($cats) {
		    $targets[] = $selector.'.'.$post_type.'-'.implode('.'.$post_type.'-', $cats);
		}

	    // By Tags
	    foreach($itemObject['tags'] as $tag) {

	        $target = $selector.'.tag'.$tag;
	        $targets[] = $target;
	    }

	    // By author pages
	    foreach($itemObject['authors'] as $author) {

			$target = $selector.'.author'.$author;
			if ($cats) {
				$target .= '.author-'.$post_type.'-'.implode('.author-'.$post_type.'-', $cats);
			}
			$targets[] = $target;
	    }

	    // By single relatedto posts
	    foreach($itemObject['references'] as $post_id) {

	        $target = $selector.'.single'.$post_id;
	        if ($cats) {
	            $target .= '.single-'.$post_type.'-'.implode('.single-'.$post_type.'-', $cats);
	        }
	        $targets[] = $target;
	    }

	    return new LS(implode(',', $targets));
	}

}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_core_serverside_helpers;
$pop_core_serverside_helpers = new PoP_Core_ServerSide_Helpers();