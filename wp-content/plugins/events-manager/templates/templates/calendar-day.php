<?php
/*
 * Default Calendar day
 * This page displays a list of events or single event for a specific calendar day, called during the em_content() if this is an calendar day page.
 * You can override the default display settings pages by copying this file to yourthemefolder/plugins/events-manager/templates/ and modifying it however you need.
 * You can display events however you wish, there are a few variables made available to you:
 * 
 * $args - the args passed onto EM_Events::output()
 * 
 */ 
$args['scope'] = $_REQUEST['calendar_day'];
$events_count = EM_Events::count( apply_filters('em_content_calendar_day_args', $args) ); //Get events first, so we know how many there are in advance
if ( $events_count > 1 || get_option('dbem_display_calendar_day_single') == 1 ) {
	em_locate_template('templates/events-list.php', true, array('args' => apply_filters('em_content_calendar_day_output_args', $args)) );
} elseif( $events_count == 1 ) {
    $args['format'] = get_option('dbem_single_event_format');
	echo EM_Events::output(apply_filters('em_content_calendar_day_output_args', $args));
} else {
	echo get_option('dbem_no_events_message');
}