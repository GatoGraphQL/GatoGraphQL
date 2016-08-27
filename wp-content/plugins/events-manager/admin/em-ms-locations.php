<?php
/**
 * Looks at the request values, saves/updates and then displays the right menu in the admin
 * @return null
 */
function em_admin_ms_locations() {  
	//TODO EM_Location is globalized, use it fully here
	global $EM_Location;
	//Take actions
	if( !empty($_REQUEST['action']) && ($_REQUEST['action'] == "edit" || $_REQUEST['action'] == "location_save")) { 
		em_admin_location();
	} else { 
		// no action, just a locations list
		em_admin_locations();
  	}
}  

function em_admin_locations($message='', $fill_fields = false) {
	global $current_site;
	?>
		<div class='wrap'>
			<div id='icon-edit' class='icon32'>
				<br/>
			</div>
 	 		<h2>
 	 			<?php _e('Locations', 'events-manager'); ?>
 	 		</h2>   
			<?php em_locations_admin(array('url' => $_SERVER['REQUEST_URI'])); ?>
		</div>
  	<?php 
}

function em_admin_location($message = "") {
	global $EM_Location;
	if( empty($EM_Location) || !is_object($EM_Location) ){
		$title = __('Add location', 'events-manager');
		$EM_Location = new EM_Location();
	}else{
		$title = __('Edit location', 'events-manager');
	}
	?>
	<div class='wrap'>
		<div id='icon-edit' class='icon32'>
			<br/>
		</div>
		<h2><?php echo $title ?></h2>
		<?php em_location_form(); ?>
	</div>
	<?php	
}

?>