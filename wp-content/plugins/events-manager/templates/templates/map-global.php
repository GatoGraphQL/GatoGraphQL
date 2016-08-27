<?php 
/*
 * This file contains the HTML generated for maps. You can copy this file to yourthemefolder/plugins/events/templates and modify it in an upgrade-safe manner.
 * 
 * There is one argument passed to you, which is the $args variable. This contains the arguments you could pass into shortcodes, template tags or functions like EM_Events::get().
 * 
 * In this template, we encode the $args array into JSON for javascript to easily parse and request the locations from the server via AJAX.
 */

if (get_option('dbem_gmap_is_active') == '1') {
	?>
	<div class="em-location-map-container"  style='position:relative; background: #CDCDCD; width: <?php echo $args['width'] ?>; height: <?php echo $args['height'] ?>;'>
		<div class='em-locations-map' id='em-locations-map-<?php echo $args['random_id']; ?>' style="width:100%; height:100%"><em><?php _e('Loading Map....', 'events-manager'); ?></em></div>
		<div class='em-locations-map-coords' id='em-locations-map-coords-<?php echo $args['random_id']; ?>' style="display:none; visibility:hidden;"><?php echo EM_Object::json_encode($args); ?></div>
	</div>
	<?php
}
?>