<?php 
/*
 * This file contains the HTML generated for a single location Google map. You can copy this file to yourthemefolder/plugins/events/templates and modify it in an upgrade-safe manner.
 * 
 * There is one argument passed to you, which is the $args variable. This contains the arguments you could pass into shortcodes, template tags or functions like EM_Events::get().
 * 
 * In this template, we encode the $args array into JSON for javascript to easily parse and request the locations from the server via AJAX.
 */
	/* @var $EM_Location EM_Location */
	if ( get_option('dbem_gmap_is_active') && ( is_object($EM_Location) && $EM_Location->location_latitude != 0 && $EM_Location->location_longitude != 0 ) ) {
	    //get dimensions with px or % added in
		$width = (!empty($args['width'])) ? $args['width']:get_option('dbem_map_default_width','400px');
		$width = preg_match('/(px)|%/', $width) ? $width:$width.'px';
		$height = (!empty($args['height'])) ? $args['height']:get_option('dbem_map_default_height','300px');
		$height = preg_match('/(px)|%/', $height) ? $height:$height.'px';
		//assign random number for element id reference
		$rand = substr(md5(rand().rand()),0,5);
		?>
		<div class="em-location-map-container"  style='position:relative; background: #CDCDCD; width: <?php echo $width ?>; height: <?php echo $height ?>;'>
   			<div class='em-location-map' id='em-location-map-<?php echo $rand ?>' style="width: 100%; height: 100%;">
   				<?php _e('Loading Map....', 'events-manager'); ?>
   			</div>
		</div>
   		<div class='em-location-map-info' id='em-location-map-info-<?php echo $rand ?>' style="display:none; visibility:hidden;">
   			<div class="em-map-balloon" style="font-size:12px;">
   				<div class="em-map-balloon-content" ><?php echo $EM_Location->output(get_option('dbem_location_baloon_format')); ?></div>
   			</div>
   		</div>
		<div class='em-location-map-coords' id='em-location-map-coords-<?php echo $rand ?>' style="display:none; visibility:hidden;">
			<span class="lat"><?php echo $EM_Location->location_latitude; ?></span>
			<span class="lng"><?php echo $EM_Location->location_longitude; ?></span>
		</div>
		<?php
	}elseif( is_object($EM_Location) && $EM_Location->location_latitude == 0 && $EM_Location->location_longitude == 0 ){
		echo '<i>'. __('Map Unavailable', 'events-manager') .'</i>';
	}