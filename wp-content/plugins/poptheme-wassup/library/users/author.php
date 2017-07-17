<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Author functionality
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * Change Author permalink from 'author' to 'u'
 * ---------------------------------------------------------------------------------------------------------------*/

// change author/username base to users/userID
add_action('init','change_author_permalinks');
function change_author_permalinks() {

	// Comment Leo 25/04/2016: Split the logic below into 2, to be executed in "init" and "PoP:system-build",
  // because flush_rules() would execute a massive update in the DB with each single request, which takes performance waaaay down and kills the server,
  // as I found out on the AWS servers
  global $wp_rewrite;
	$wp_rewrite->author_base = 'u';
	// $wp_rewrite->flush_rules();
}
add_action('PoP:system-build','change_author_flushrules');
function change_author_flushrules() {

  // Comment Leo 05/05/2016: Executing flush_rules() is SO expensive, that we'd rather not do it unless really really have to
  // It will generate a HUGE sql query, whose execution takes the latency way up, and it will consume a HUGE bandwidth between EC2 and the DB, costing real $$$
  if (PoPTheme_Wassup_ServerUtils::enable_flush_rules()) {

    change_author_permalinks();

    global $wp_rewrite;
    $wp_rewrite->flush_rules();
  }
}
add_filter('query_vars', 'users_query_vars');
function users_query_vars($vars) {

    // add lid to the valid list of variables
    $new_vars = array('u');
    $vars = $new_vars + $vars;
    return $vars;
}
add_filter('generate_rewrite_rules','user_rewrite_rules');
function user_rewrite_rules( $wp_rewrite ) {

  $newrules = array();
  $new_rules['u/(\d*)$'] = 'index.php?author=$matches[1]';
  $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
