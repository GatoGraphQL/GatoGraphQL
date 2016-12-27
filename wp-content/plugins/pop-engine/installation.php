<?php
class PoPEngine_Installation {

	function maybe_install() {

		$version = pop_version();
		$saved_version = get_option('PoP:version');

		// Install if the current version has changed, or if forcing an update passing a parameter in the request
		if ($version > $saved_version || !$saved_version || (has_role('administrator') && $_REQUEST['action'] == 'pop_update')){
		 	
			// do_action('PoP:install', $version);
			
			// //Update Version	
			// update_option('PoP:version', $version);
			$this->install();
		}
	}

	function install() {

		$version = pop_version();
		 	
		do_action('PoP:install', $version);
		
		//Update Version	
		update_option('PoP:version', $version);
	}
}