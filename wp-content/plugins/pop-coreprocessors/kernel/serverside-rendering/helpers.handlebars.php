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

	function formatFeedbackMessage($message, $options) {

		// ------------------------------------------------------
		// Comment Leo: Not needed in PHP => Commented out
		// ------------------------------------------------------
		// Please notice: this function will NEVER be executed, because if the block is multidomain, 
		// then it will always fetch data lazy-load, so the feedbackMessage will then never be printed on the server
		// $isMultiDomain = $options['hash']['is-multidomain'];
		// $domain = $options['hash']['domain'];
		// if ($isMultiDomain && $domain) {

		// 	// If specified the domain, then add its name in the message, through a customizable format
		// 	$websiteproperties = PoP_MultiDomain_Utils::get_multidomain_websites();
		// 	$name = $websiteproperties[$domain] ? $websiteproperties[$domain]['name'] : $domain;
		// 	$message = sprintf(
		// 		str_replace(array('{0}', '{1}'), array('%1$s', '%2$s'), GD_CONSTANT_FEEDBACKMSG_MULTIDOMAIN),
		// 		$name, 
		// 		$message
		// 	);
		// }

	    return new LS($message);
	}

}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_core_serverside_helpers;
$pop_core_serverside_helpers = new PoP_Core_ServerSide_Helpers();