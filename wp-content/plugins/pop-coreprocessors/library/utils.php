<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Util functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

function get_slug($id) {
	$post_data = get_post($id, ARRAY_A);
	return $post_data['post_name'];
}

function get_url_no_domain($url) {

	return substr($url, strlen(get_settings('home'))); 
}

function startsWith($haystack, $needle)
{
    return $needle === "" || strpos($haystack, $needle) === 0;
}
function endsWith($haystack, $needle)
{
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}

function get_permalink_no_domain($page_id = null) {

	return get_url_no_domain(get_permalink($page_id)); 
}

function get_author_posts_url_no_domain($user_id = null) {

	return get_url_no_domain(get_author_posts_url($user_id)); 
}

/**
 * Extracts the path to the page without language info or domain
 */
function gd_extract_path($id, $type = 'page') {

	// if qTranslate is installed, remove the filter first
	// This is because we need to print the path without language info
	if ($type == 'page') {
		
		do_action('gd_extract_path-page-before');
		$path = get_permalink_no_domain($id);		
		do_action('gd_extract_path-page-after');
	}
	elseif ($type == 'user') {
		
		do_action('gd_extract_path-user-before');
		$path = get_author_posts_url_no_domain($id);
		do_action('gd_extract_path-user-after');
	}
	
	return $path;
}

/* Return the download link and target: if it is in our website, add forcedownload=true param, otherwise is same link in new window */
function gd_download_link($url) {

	// Check if the $url is local
	$target = '';
	if (strpos($url, get_settings('home')) === 0) {
		$url = add_query_arg('forcedownload', 'true', $url);
	}
	else {
		$target = '_blank';
	}

	return array('url' => $url, 'target' => $target);
}

/**---------------------------------------------------------------------------------------------------------------
 * Little bit of everything
 * ---------------------------------------------------------------------------------------------------------------*/


/**
 * From the array created through gd_options, returns the value to display to the user from the selected key
 */
function gd_obtain_value_from_options($options_name, $key) {

	$options = gd_get_option($options_name);
	$options_map = gd_build_select_options($options);
	return __($options_map[$key], 'pop-coreprocessors');
}

/*
 * Builds the array with key => value for the industries
 */
function gd_build_select_options($options, $label = null) {

	$return = array();
	
	// First option: Please Select
	if ($label) {
		$return[""] = __(sprintf('Select %s', $label), 'pop-coreprocessors');
	}
	
	if ($options) {
	
		foreach ($options as $option) {
		
			$return[sanitize_title($option)] = __($option, 'pop-coreprocessors');
		}
	}
	
	return $return;
}

// Returns an implode of all the keys of the Arrays inside the Array $pieces
function implode_key($glue = "", $pieces = array()) {
	
	$keys = array_keys($pieces);
	return implode($glue, $keys);
}


// Returns the id of a category
function gd_get_term_id($category) {

	return $category->term_id;
}


// Returns the id of a category
function gd_get_the_id($object) {

	return $object->ID;
}



function implode_with_key($assoc, $inglue = '=', $outglue = '&', $arrayinglue = ',') {
    $return = '';
 
    foreach ($assoc as $tk => $tv) {
    
    	if (is_array($tv))
    		$tv = implode($arrayinglue, $tv);
    
        $return .= $outglue . $tk . $inglue . $tv;
    }
 
    return substr($return, strlen($outglue));
}




// Shortens up to including a full paragraph: if a paragraph is found within $char characters, return up to there. Otherwise, check
// for the first paragraph after $char characters, and return up to there
function string_shorten_by_paragraphs($text, $length = null) {

	if (!$length) {
		$length = apply_filters('excerpt_length', 250);
	}

    // If it finds a '</p>' before the $char characters, return that substring
    $text_beg = substr($text, 0, $length);
    if(strrpos($text_beg, '</p>') !== false) {
    
    	return substr($text_beg, 0, strrpos($text_beg, '</p>') + 4);
    }
    
    // If not, check the first '</p>' on the rest of the string
    if(strpos($text, '</p>') !== false) {
    
    	return substr($text, 0, strpos($text, '</p>') + 4);
    }
    
    return $text;
}


/**
 * Makes a regex to deliver the content between HTML tag
 */
function getTextBetweenTags($string, $tagname) {
    $pattern = "/<$tagname ?.*>(.*)<\/$tagname>/";
    preg_match($pattern, $string, $matches);
    return $matches[1];
}
function getHtmlAttribute($html, $tag, $att) {
    
    // With images from FB, this produces an exception:
    // Warning: DOMDocument::loadHTML(): htmlParseEntityRef: expecting ';' in Entity,
    // explanation in http://stackoverflow.com/questions/1685277/warning-domdocumentloadhtml-htmlparseentityref-expecting-in-entity

    if (!$html) {
    	return null;
    }
	
    $dom = new DOMDocument;
	$dom->loadHTML($html);
	foreach ($dom->getElementsByTagName($tag) as $node) {
		return $node->getAttribute( $att );
	}	
}
function getHtmlTag( $tag, $html ) {
	$regex_pattern = "/<".$tag." [^>]*>/";
	preg_match_all($regex_pattern,$html,$matches);

	return $matches[0];
}


/**
 * Iterates on each item in $array and builds an array map with $prop_key and $prop_value evaluated on each $item
 */
function gd_build_property_arraymap($prop_key, $prop_value, $items) {

	$map = array();
	foreach ($items as $item) {
	
		$map[$item->$prop_key] = $item->$prop_value;
	}
	
	return $map;
}

function array_to_quoted_string($array, $quote = '"') {

	return $quote.implode($quote.', '.$quote, $array).$quote;
}

// Taken from https://stackoverflow.com/questions/2915864/php-how-to-find-the-time-elapsed-since-a-date-time
function humanTiming ($time)
{

    $time = time() - $time; // to get the time since that moment
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }

}