Files in this folder or in wp-content/themes/yourthemedir/plugins/events-manager/formats/ would be used to replace formatting options in the settings page. This allows you a much greater degree of flexibility as you can use PHP go do all sorts of customizations to your events.

To overwrite a formatting option, you need to firstly create a file here with the same name as the setting name in the wp_options table without the dbem_ prefix. So, for example:

'dbem_event_list_item_format' - format for controlling a single event format in a list of events

becomes

event_list_item_format.php

Then, using the example above, to activate the overriding format, you need to add this to your theme's functions.php file.

<?php
function my_em_custom_formats( $array ){
	$my_formats = array('dbem_event_list_item_format'); //the format you want to override, corresponding to file above.
	return $array + $my_formats; //return the default array and your formats.
}
add_filter('em_formats_filter', 'my_em_custom_formats', 1, 1);
?>

If you want to add more than one format, add more option names to the $my_formats array and create the corresponding php file.