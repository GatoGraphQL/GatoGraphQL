<?php
/* WARNING!!! (2013-07-10) We intend to add a few more fields into this search form over the coming weeks/months. 
 * Overriding shouldn't hinder functionality at all but these new search options won't appear on your form! 
 */ 
/* 
 * By modifying this in your theme folder within plugins/events-manager/templates/events-search.php, you can change the way the search form will look.
 * To ensure compatability, it is recommended you maintain class, id and form name attributes, unless you now what you're doing. 
 * You also have an $args array available to you with search options passed on by your EM settings or shortcode
 */
/* @var $args array */
$args['search_action'] = 'search_locations';
$args['search_url'] = get_option('dbem_locations_page') ? get_permalink(get_option('dbem_locations_page')):EM_URI;
em_locate_template('templates/events-search.php', true, array('args'=>$args));
?>