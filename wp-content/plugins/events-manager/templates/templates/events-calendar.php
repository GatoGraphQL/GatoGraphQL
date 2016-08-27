<?php
/*
 * Default Events List Template
 * This page displays a list of events, called during the em_content() if this is an events list page.
 * You can override the default display settings pages by copying this file to yourthemefolder/plugins/events-manager/templates/ and modifying it however you need.
 * You can display events however you wish, there are a few variables made available to you:
 * 
 * $args - the args passed onto EM_Events::output()
 * 
 */ 
$args['full'] = 1;
$args['long_events'] = get_option('dbem_full_calendar_long_events');
echo EM_Calendar::output( apply_filters('em_content_calendar_args', $args) );